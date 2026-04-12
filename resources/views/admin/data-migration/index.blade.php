@extends('layouts.app')

@section('title', 'Data Migration')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Data Migration</h1>

            <p class="text-gray-600 mb-6">
                Import doctors, patients, medical records, and diagnoses from CSV, Excel, JSON, or SQL dump files.
            </p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.data-migration.parse') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">File</label>
                    <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls,.json,.sql"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Maximum file size: 50MB. Supported formats: CSV, Excel (.xlsx, .xls), JSON, SQL</p>
                </div>

                <div class="mb-4">
                    <label for="entity_type" class="block text-sm font-medium text-gray-700 mb-2">Entity Type</label>
                    <select name="entity_type" id="entity_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">-- Select --</option>
                        <option value="doctor">Doctor</option>
                        <option value="patient">Patient</option>
                        <option value="patient_data">Patient Data / Medical Records</option>
                        <option value="diagnosis">Diagnosis</option>
                    </select>
                    @error('entity_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="source_system" class="block text-sm font-medium text-gray-700 mb-2">Source System (Optional)</label>
                    <input type="text" name="source_system" id="source_system"
                           placeholder="e.g., clinic_xyz"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Name of the source system for tracking data provenance</p>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Upload & Parse
                </button>
            </form>
        </div>

        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Imports</h2>
            @php
                $recentLogs = \App\Models\DataMigrationLog::with('admin')
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp

            @if($recentLogs->isEmpty())
                <p class="text-gray-500">No migration logs yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">Date</th>
                            <th class="pb-2">File</th>
                            <th class="pb-2">Type</th>
                            <th class="pb-2">Imported</th>
                            <th class="pb-2">Skipped</th>
                            <th class="pb-2">Failed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                            <tr class="border-b">
                                <td class="py-2">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                <td class="py-2">{{ Str::limit($log->file_name, 20) }}</td>
                                <td class="py-2">{{ ucfirst($log->entity_type) }}</td>
                                <td class="py-2 text-green-600">{{ $log->imported_count }}</td>
                                <td class="py-2 text-yellow-600">{{ $log->skipped_count }}</td>
                                <td class="py-2 text-red-600">{{ $log->failed_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
