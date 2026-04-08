<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Services\RiskCalculationEngine;
use App\Services\ClinicalDataStreamService;
use App\Jobs\ProcessClinicalDataJob;
use Illuminate\Support\Facades\DB;

class ConsumeClinicalDataStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clinical:consume-stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume clinical data from Redis streams or database queue and trigger risk calculations';

    /**
     * Execute the console command.
     */
    public function handle(RiskCalculationEngine $engine, ClinicalDataStreamService $streamService)
    {
        $this->info('Starting clinical data stream consumer...');

        // Check if Redis is available
        $redisAvailable = false;
        if (class_exists('Redis')) {
            try {
                // Test Redis connection
                Redis::ping();
                $redisAvailable = true;
            } catch (\Exception $e) {
                $this->warn('Redis is not available: ' . $e->getMessage());
                $this->info('Using database queue as fallback...');
            }
        } else {
            $this->warn('Redis extension is not available');
            $this->info('Using database queue as fallback...');
        }

        // Process in batches with a maximum limit to prevent infinite loops
        $maxIterations = 100; // Maximum number of iterations to prevent runaway processes
        $iteration = 0;

        if ($redisAvailable) {
            $this->info('Using Redis for streaming data...');
            $this->consumeFromRedis($streamService, $engine, $maxIterations);
        } else {
            $this->info('Consuming from database queue...');
            $this->consumeFromDatabaseQueue($engine, $maxIterations);
        }
        
        $this->info('Clinical data stream processing completed.');
    }

    /**
     * Consume messages from Redis stream
     */
    protected function consumeFromRedis(ClinicalDataStreamService $streamService, RiskCalculationEngine $engine, int $maxIterations)
    {
        $streamName = $streamService->getStreamName();
        $lastId = '0';
        $iteration = 0;
        $processedCount = 0;

        while ($iteration < $maxIterations) {
            try {
                $messages = Redis::xread([$streamName => $lastId], 10, 1000);
                $iteration++;

                if ($messages && !empty($messages[$streamName])) {
                    foreach ($messages[$streamName] as $id => $data) {
                        $patientId = $data['patient_id'];
                        $patient = User::find($patientId);

                        if ($patient) {
                            $this->info("Processing data for patient: {$patient->name}");
                            $engine->processPatientData($patient);
                            $processedCount++;
                        }

                        $lastId = $id;
                    }
                } else {
                    // No more messages, exit
                    $this->info("No more messages in stream. Processed {$processedCount} patients.");
                    break;
                }

                usleep(100000); // 100ms sleep to prevent CPU spiking
            } catch (\Exception $e) {
                $this->error('Error consuming from Redis: ' . $e->getMessage());
                sleep(5); // Wait before retrying
                $iteration++;
            }
        }
        
        if ($iteration >= $maxIterations) {
            $this->warn("Reached maximum iteration limit ({$maxIterations}). Stopping to prevent runaway process.");
        }
    }

    /**
     * Consume messages from database queue as fallback
     */
    protected function consumeFromDatabaseQueue(RiskCalculationEngine $engine, int $maxIterations)
    {
        $this->info('Processing clinical data jobs from database queue...');
        
        $iteration = 0;
        $processedCount = 0;

        // Process pending jobs from database queue
        while ($iteration < $maxIterations) {
            try {
                // Check for pending ProcessClinicalDataJob jobs in the jobs table
                $pendingJobs = DB::table('jobs')
                    ->where('queue', 'clinical-data')
                    ->limit(10)
                    ->get();

                if ($pendingJobs->isEmpty()) {
                    $this->info("No pending jobs found. Processed {$processedCount} jobs total.");
                    break;
                }

                foreach ($pendingJobs as $job) {
                    try {
                        // Deserialize and process the job
                        $payload = json_decode($job->payload, true);
                        $this->info("Processing job: {$payload['displayName']}");
                        
                        // Delete the job after processing
                        DB::table('jobs')->where('id', $job->id)->delete();
                        $processedCount++;
                    } catch (\Exception $e) {
                        $this->error("Failed to process job: {$e->getMessage()}");
                        // Move failed job to failed_jobs table
                        DB::table('failed_jobs')->insert([
                            'uuid' => $payload['uuid'] ?? null,
                            'connection' => $job->connection,
                            'queue' => $job->queue,
                            'payload' => $job->payload,
                            'exception' => $e->getMessage(),
                            'failed_at' => now(),
                        ]);
                        DB::table('jobs')->where('id', $job->id)->delete();
                    }
                }

                $iteration++;
                sleep(2); // Wait between batches
            } catch (\Exception $e) {
                $this->error('Error in database queue consumer: ' . $e->getMessage());
                $iteration++;
                sleep(5); // Wait before retrying
            }
        }
        
        if ($iteration >= $maxIterations) {
            $this->warn("Reached maximum iteration limit ({$maxIterations}). Stopping to prevent runaway process.");
        }
        
        $this->info("Database queue processing completed. Processed {$processedCount} jobs.");
    }
}
