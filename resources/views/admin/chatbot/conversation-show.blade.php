@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Conversation Details</h1>
            <p class="text-gray-600 mt-2">Conversation #{{ $conversation->id }}</p>
        </div>
        <a href="{{ route('chatbot.conversations') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition">
            Back to Conversations
        </a>
    </div>

    <!-- Conversation Info -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <span class="text-sm font-medium text-gray-500">Patient</span>
                <p class="text-lg font-semibold text-gray-900">
                    @if($conversation->patient)
                        {{ $conversation->patient->name }}
                    @else
                        Unidentified
                    @endif
                </p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">Platform</span>
                <p class="text-lg font-semibold text-gray-900">
                    <span class="px-2 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $conversation->platform === 'whatsapp' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($conversation->platform) }}
                    </span>
                </p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">Platform User ID</span>
                <p class="text-lg font-semibold text-gray-900">{{ $conversation->platform_user_id }}</p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">State</span>
                <p class="text-lg font-semibold text-gray-900">{{ str_replace('_', ' ', $conversation->state) }}</p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">Created</span>
                <p class="text-lg font-semibold text-gray-900">{{ $conversation->created_at->format('M d, Y g:i A') }}</p>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500">Last Activity</span>
                <p class="text-lg font-semibold text-gray-900">{{ $conversation->last_activity_at?->diffForHumans() ?? 'Never' }}</p>
            </div>
        </div>

        @if($conversation->context)
        <div class="mt-4 pt-4 border-t">
            <span class="text-sm font-medium text-gray-500">Context</span>
            <pre class="mt-2 bg-gray-100 p-3 rounded text-sm overflow-x-auto">{{ json_encode($conversation->context, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>

    <!-- Messages -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Messages ({{ $conversation->messages->count() }})</h2>
        
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md {{ $message->direction === 'outbound' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }} rounded-lg px-4 py-2">
                        <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>
                        <div class="mt-1 flex justify-between items-center">
                            <span class="text-xs {{ $message->direction === 'outbound' ? 'text-blue-200' : 'text-gray-500' }}">
                                {{ $message->created_at->format('g:i A') }}
                            </span>
                            <span class="text-xs {{ $message->direction === 'outbound' ? 'text-blue-200' : 'text-gray-500' }}">
                                {{ $message->status }}
                            </span>
                        </div>
                        @if($message->error_message)
                            <p class="mt-1 text-xs text-red-300">{{ $message->error_message }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No messages yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-3">
        <form method="POST" action="{{ route('chatbot.conversation.delete', $conversation) }}" onsubmit="return confirm('Are you sure you want to delete this conversation?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition">
                Delete Conversation
            </button>
        </form>
    </div>
</div>
@endsection
