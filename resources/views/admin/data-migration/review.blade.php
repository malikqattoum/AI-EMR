@extends('layouts.app')

@section('title', 'Review Mappings - Data Migration')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Review Field Mappings</h1>

            <div class="mb-4 flex items-center justify-between">
                <div>
                    <span class="text-gray-600">Entity Type:</span>
                    <span class="font-semibold">{{ ucfirst($entityType) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Records:</span>
                    <span class="font-semibold">{{ $recordCount }}</span>
                </div>
            </div>

            <form action="{{ route('admin.data-migration.import') }}" method="POST">
                @csrf

                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b bg-gray-50">
                                <th class="p-3">Source Column</th>
                                <th class="p-3">Mapped To</th>
                                <th class="p-3">Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mappings as $mapping)
                                <tr class="border-b">
                                    <td class="p-3">{{ $mapping->sourceColumn }}</td>
                                    <td class="p-3">
                                        <select name="mappings[{{ $mapping->sourceColumn }}]"
                                                class="w-full px-2 py-1 border border-gray-300 rounded">
                                            <option value="">-- Unmapped --</option>
                                            @foreach($targetFields[$entityType] ?? [] as $field)
                                                <option value="{{ $field }}"
                                                        {{ $mapping->targetField === $field ? 'selected' : '' }}>
                                                    {{ $field }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        @if($mapping->confidence >= 0.8)
                                            <span class="text-green-600">High ({{ number_format($mapping->confidence * 100, 0) }}%)</span>
                                        @elseif($mapping->confidence >= 0.5)
                                            <span class="text-yellow-600">Medium ({{ number_format($mapping->confidence * 100, 0) }}%)</span>
                                        @else
                                            <span class="text-red-600">Low ({{ number_format($mapping->confidence * 100, 0) }}%)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!empty($preview))
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Preview (First 3 Records)</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        @foreach($preview[0]->data ?? [] as $key => $value)
                                            <th class="p-2 border">{{ $key }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preview as $record)
                                        <tr>
                                            @foreach($record->data ?? [] as $val)
                                                <td class="p-2 border">{{ Str::limit($val, 30) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="flex gap-4">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        Confirm & Import
                    </button>
                    <a href="{{ route('admin.data-migration.upload') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
