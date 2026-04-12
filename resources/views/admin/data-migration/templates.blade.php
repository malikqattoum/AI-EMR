@extends('layouts.app')

@section('title', 'Mapping Templates')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Mapping Templates</h1>

            <p class="text-gray-600 mb-6">
                Saved mapping templates for quick import of data from known source systems.
            </p>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($templates->isEmpty())
                <p class="text-gray-500">No saved templates yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-3">Source System</th>
                            <th class="pb-3">Entity Type</th>
                            <th class="pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                            <tr class="border-b">
                                <td class="py-3">{{ $template->source_system }}</td>
                                <td class="py-3">{{ ucfirst($template->entity_type) }}</td>
                                <td class="py-3">
                                    <a href="#" class="text-blue-600 hover:underline">View Mappings</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
