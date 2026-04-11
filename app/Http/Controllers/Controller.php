<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

/**
 * Base Controller for all application controllers.
 * 
 * Provides common response methods, validation helpers, and standardized
 * error handling to ensure consistency across the application.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a successful JSON response.
     *
     * @param mixed $data The response data
     * @param string $message Success message
     * @param int $statusCode HTTP status code (default: 200)
     * @return JsonResponse
     */
    protected function success(mixed $data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message Error message
     * @param mixed $data Additional error data
     * @param int $statusCode HTTP status code (default: 400)
     * @return JsonResponse
     */
    protected function error(string $message = 'Error', mixed $data = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a not found JSON response.
     *
     * @param string $resource The resource that was not found
     * @return JsonResponse
     */
    protected function notFound(string $resource = 'Resource'): JsonResponse
    {
        return $this->error(
            message: "{$resource} not found",
            statusCode: 404
        );
    }

    /**
     * Return an unauthorized JSON response.
     *
     * @param string $message The unauthorized message
     * @return JsonResponse
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error(
            message: $message,
            statusCode: 401
        );
    }

    /**
     * Return a forbidden JSON response.
     *
     * @param string $message The forbidden message
     * @return JsonResponse
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error(
            message: $message,
            statusCode: 403
        );
    }

    /**
     * Return a validation error JSON response.
     *
     * @param array $errors Validation errors
     * @param string $message Error message
     * @return JsonResponse
     */
    protected function validationFailed(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->error(
            message: $message,
            data: ['errors' => $errors],
            statusCode: 422
        );
    }

    /**
     * Return a created JSON response.
     *
     * @param mixed $data The created resource data
     * @param string $message Success message
     * @return JsonResponse
     */
    protected function created(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->success(data: $data, message: $message, statusCode: 201);
    }

    /**
     * Return an accepted JSON response (for async processing).
     *
     * @param string $message Status message
     * @param mixed $data Additional data
     * @return JsonResponse
     */
    protected function accepted(string $message = 'Request accepted for processing', mixed $data = null): JsonResponse
    {
        return $this->success(data: $data, message: $message, statusCode: 202);
    }

    /**
     * Return a no content JSON response.
     *
     * @param string $message Status message
     * @return JsonResponse
     */
    protected function noContent(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return $this->success(message: $message, statusCode: 204);
    }

    /**
     * Paginate a query and return standardized results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder
     * @param int $perPage Items per page
     * @param string $message Success message
     * @return JsonResponse
     */
    protected function paginate($query, int $perPage = 15, string $message = 'Success'): JsonResponse
    {
        $paginator = $query->paginate($perPage);

        return $this->success([
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ],
        ], $message);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @param Request $request The incoming request
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function deny($message = 'This action is unauthorized.'): void
    {
        $this->authorize($message);
    }

    /**
     * Log an error with context.
     *
     * @param string $message The error message
     * @param array $context Additional context data
     * @param mixed $exception Optional exception to log
     * @return void
     */
    protected function logError(string $message, array $context = [], mixed $exception = null): void
    {
        $logContext = array_merge($context, [
            'exception' => $exception?->getMessage(),
            'trace' => $exception?->getTraceAsString(),
        ]);

        Log::error($message, $logContext);
    }

    /**
     * Log a warning with context.
     *
     * @param string $message The warning message
     * @param array $context Additional context data
     * @return void
     */
    protected function logWarning(string $message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    /**
     * Log an info message with context.
     *
     * @param string $message The info message
     * @param array $context Additional context data
     * @return void
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }
}
