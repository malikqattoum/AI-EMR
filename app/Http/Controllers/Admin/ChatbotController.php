<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatbotIntent;
use App\Models\ChatbotMessage;
use App\Models\User;
use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\Platforms\WhatsAppPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Display chatbot settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $stats = [
            'total_conversations' => ChatbotConversation::count(),
            'active_conversations' => ChatbotConversation::active()->count(),
            'total_messages' => ChatbotMessage::count(),
            'total_intents' => ChatbotIntent::count(),
            'enabled_intents' => ChatbotIntent::where('enabled', true)->count(),
            'whatsapp_conversations' => ChatbotConversation::where('platform', 'whatsapp')->count(),
            'messenger_conversations' => ChatbotConversation::where('platform', 'messenger')->count(),
        ];

        $intents = ChatbotIntent::orderedByPriority()->get();

        return view('admin.chatbot.settings', compact('stats', 'intents'));
    }

    /**
     * Update chatbot settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'ai_enabled' => 'boolean',
            'ai_model' => 'string|max:50',
            'max_conversation_age_hours' => 'integer|min:1',
            'idle_timeout_minutes' => 'integer|min:5',
        ]);

        // Update system settings using SystemSetting model
        $settings = [
            'chatbot_ai_enabled' => $request->boolean('ai_enabled'),
            'chatbot_ai_model' => $request->ai_model,
            'chatbot_max_conversation_age_hours' => $request->max_conversation_age_hours,
            'chatbot_idle_timeout_minutes' => $request->idle_timeout_minutes,
        ];

        foreach ($settings as $key => $value) {
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear config cache
        config(['chatbot.ai_enabled' => $request->boolean('ai_enabled')]);

        return redirect()->route('admin.chatbot.settings')
            ->with('success', 'Chatbot settings updated successfully.');
    }

    /**
     * Display chatbot conversations.
     */
    public function conversations(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = ChatbotConversation::with(['patient', 'messages'])
            ->orderBy('last_activity_at', 'desc');

        // Filter by platform
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        // Filter by state
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $conversations = $query->paginate(20);

        $platforms = ['whatsapp', 'messenger'];
        $states = ChatbotConversation::distinct()->pluck('state');
        $patients = User::where('role', 'patient')
            ->orderBy('name')
            ->get();

        return view('admin.chatbot.conversations', compact('conversations', 'platforms', 'states', 'patients'));
    }

    /**
     * Show a specific conversation with messages.
     */
    public function showConversation(ChatbotConversation $conversation)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $conversation->load(['patient', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return view('admin.chatbot.conversation-show', compact('conversation'));
    }

    /**
     * Delete a conversation and its messages.
     */
    public function deleteConversation(ChatbotConversation $conversation)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $conversation->messages()->delete();
        $conversation->delete();

        return redirect()->route('admin.chatbot.conversations')
            ->with('success', 'Conversation deleted successfully.');
    }

    /**
     * Toggle intent status.
     */
    public function toggleIntent(ChatbotIntent $intent)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $intent->update(['enabled' => !$intent->enabled]);

        return redirect()->route('admin.chatbot.settings')
            ->with('success', "Intent '{$intent->label}' " . ($intent->enabled ? 'enabled' : 'disabled') . '.');
    }

    /**
     * Send a test message through the chatbot.
     */
    public function sendTestMessage(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'platform' => 'required|in:whatsapp,messenger',
            'platform_user_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $chatbotService = app(ChatbotService::class);
        $result = $chatbotService->processMessage(
            $request->platform,
            $request->platform_user_id,
            $request->message
        );

        return response()->json([
            'success' => $result['success'] ?? false,
            'result' => $result,
        ]);
    }

    /**
     * Test WhatsApp platform connection.
     */
    public function testWhatsApp(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        $platform = new WhatsAppPlatform();
        $result = $platform->sendMessage($request->phone_number, $request->message);

        if ($result['success']) {
            return redirect()->route('admin.chatbot.settings')
                ->with('success', 'Test WhatsApp message sent successfully.');
        }

        return redirect()->route('admin.chatbot.settings')
            ->withErrors(['error' => 'Failed to send test message: ' . ($result['error'] ?? 'Unknown error')]);
    }
}
