@extends('layouts.app')

@section('title', 'Import Summary')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Import Summary</h1>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Source System</div>
                    <div class="font-semibold">{{ $log->source_system ?? 'N/A' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Entity Type</div>
                    <div class="font-semibold">{{ ucfirst($log->entity_type) }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">File Name</div>
                    <div class="font-semibold">{{ $log->file_name }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">Completed At</div>
                    <div class="font-semibold">{{ $log->completed_at?->format('M d, Y H:i') ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-green-100 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-700">{{ $log->imported_count }}</div>
                    <div class="text-sm text-green-600">Imported</div>
                </div>
                <div class="p-4 bg-yellow-100 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-700">{{ $log->skipped_count }}</div>
                    <div class="text-sm text-yellow-600">Skipped</div>
                </div>
                <div class="p-4 bg-red-100 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-700">{{ $log->failed_count }}</div>
                    <div class="text-sm text-red-600">Failed</div>
                </div>
            </div>

            @if($log->failure_log && count($log->failure_log) > 0)
                <div class="mb-6">
                    <h3 class="font-semibold mb-2 text-red-600">Failures ({{ count($log->failure_log) }})</h3>
                    <div class="max-h-64 overflow-y-auto bg-red-50 rounded-lg p-4">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="pb-2">Row</th>
                                    <th class="pb-2">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log->failure_log as $failure)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $failure['row'] ?? 'N/A' }}</td>
                                        <td class="py-2">{{ $failure['reason'] ?? 'Unknown error' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <a href="{{ route('admin.data-migration.upload') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                New Import
            </a>
        </div>
    </div>
</div>
@endsection
