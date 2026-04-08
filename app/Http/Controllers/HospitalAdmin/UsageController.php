<?php

namespace App\Http\Controllers\HospitalAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Diagnosis;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsageController extends Controller
{
    /**
     * Display usage reports for the hospital.
     */
    public function index()
    {
        $user = Auth::user();
        $hospital = $user->hospital;

        if (!$hospital) {
            return redirect()->route('hospital-admin.dashboard')
                ->with('error', 'No hospital associated with your account.');
        }

        // Get hospital doctors
        $doctors = User::where('hospital_id', $hospital->id)
            ->where('role', 'doctor')
            ->get();

        $doctorIds = $doctors->pluck('id')->toArray();

        // Get usage statistics
        $usageStats = [
            'total_doctors' => $doctors->count(),
            'active_doctors' => $doctors->where('is_active', true)->count(),
            'total_diagnoses' => Diagnosis::whereIn('doctor_id', $doctorIds)->count(),
            'total_appointments' => Appointment::whereIn('doctor_id', $doctorIds)->count(),
        ];

        // Get monthly usage data for the current year
        $currentYear = now()->year;
        $monthlyUsage = collect(range(1, 12))->map(function ($month) use ($doctorIds, $currentYear) {
            $startDate = "$currentYear-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $diagnosesCount = Diagnosis::whereIn('doctor_id', $doctorIds)
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->count();
            
            $appointmentsCount = Appointment::whereIn('doctor_id', $doctorIds)
                ->whereBetween('appointment_date', [$startDate, $endDate . ' 23:59:59'])
                ->count();
            
            return [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'diagnoses' => $diagnosesCount,
                'appointments' => $appointmentsCount,
            ];
        });

        return view('hospital-admin.usage.index', compact('hospital', 'doctors', 'usageStats', 'monthlyUsage'));
    }

    /**
     * Export usage data.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $hospital = $user->hospital;

        if (!$hospital) {
            return redirect()->route('hospital-admin.dashboard')
                ->with('error', 'No hospital associated with your account.');
        }

        // Get export data
        $doctors = User::where('hospital_id', $hospital->id)
            ->where('role', 'doctor')
            ->get();

        $filename = 'hospital_usage_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($doctors, $hospital) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Hospital',
                'Doctor Name',
                'Email',
                'Status',
                'Created Date',
                'Total Diagnoses',
                'Total Appointments'
            ]);

            // CSV data
            foreach ($doctors as $doctor) {
                $diagnosesCount = Diagnosis::where('doctor_id', $doctor->id)->count();
                $appointmentsCount = Appointment::where('doctor_id', $doctor->id)->count();
                
                fputcsv($file, [
                    $hospital->name,
                    $doctor->name,
                    $doctor->email,
                    $doctor->is_active ? 'Active' : 'Inactive',
                    $doctor->created_at->format('Y-m-d'),
                    $diagnosesCount,
                    $appointmentsCount,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}