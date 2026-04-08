@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Chatbot Settings</h1>
        <p class="text-gray-600 mt-2">Manage WhatsApp and Messenger chatbot configuration</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Conversations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_conversations'] }}</p>
                </div>
                <div class="text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Conversations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_conversations'] }}</p>
                </div>
                <div class="text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">WhatsApp</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['whatsapp_conversations'] }}</p>
                </div>
                <div class="text-green-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Messenger</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['messenger_conversations'] }}</p>
                </div>
                <div class="text-blue-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.301 2.246.464 3.443.464 6.627 0 12-4.975 12-11.111S18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L9.793 8.2l3.131 3.26 5.886-3.26-5.619 6.763z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Intents Configuration -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Intent Configuration</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intent</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($intents as $intent)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $intent->label }}</div>
                                <div class="text-sm text-gray-500">{{ $intent->name }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $intent->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $intent->enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form method="POST" action="{{ route('chatbot.intent.toggle', $intent) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        {{ $intent->enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-4">
                <a href="{{ route('chatbot.conversations') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded transition">
                    View All Conversations
                </a>
                
                <div class="border-t pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Test Chatbot</h3>
                    <form method="POST" action="{{ route('chatbot.test-whatsapp') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" placeholder="+1234567890" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea name="message" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>Hi</textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition">
                            Send Test WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Guide -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Setup Guide</h2>
        <div class="prose max-w-none">
            <h3 class="text-lg font-semibold">WhatsApp Business API Setup</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li>Create a Meta Business account at <a href="https://business.facebook.com" class="text-blue-600 hover:underline" target="_blank">business.facebook.com</a></li>
                <li>Set up WhatsApp Business API in Meta Business Suite</li>
                <li>Get your Access Token and Phone Number ID</li>
                <li>Add them to your .env file:
                    <pre class="bg-gray-100 p-3 rounded mt-2"><code>WHATSAPP_BUSINESS_ACCESS_TOKEN=your_token_here
WHATSAPP_BUSINESS_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_WEBHOOK_VERIFY_TOKEN=medcura-webhook-verify</code></pre>
                </li>
                <li>Configure webhook URL: <code class="bg-gray-100 px-2 py-1 rounded">https://yourdomain.com/webhooks/whatsapp</code></li>
            </ol>

            <h3 class="text-lg font-semibold mt-6">Facebook Messenger Setup</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li>Create a Facebook Page (if you don't have one)</li>
                <li>Create a Facebook App at <a href="https://developers.facebook.com" class="text-blue-600 hover:underline" target="_blank">developers.facebook.com</a></li>
                <li>Add Messenger product to your app</li>
                <li>Get your Page Access Token and App Secret</li>
                <li>Add them to your .env file:
                    <pre class="bg-gray-100 p-3 rounded mt-2"><code>MESSENGER_ACCESS_TOKEN=your_access_token_here
MESSENGER_APP_SECRET=your_app_secret_here
MESSENGER_VERIFY_TOKEN=medcura-messenger-verify
MESSENGER_PAGE_ID=your_page_id_here</code></pre>
                </li>
                <li>Configure webhook URL: <code class="bg-gray-100 px-2 py-1 rounded">https://yourdomain.com/webhooks/messenger</code></li>
                <li>Subscribe to messages and messaging_postbacks events</li>
            </ol>
        </div>
    </div>
</div>
@endsection
