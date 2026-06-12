<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all chats for current user
        $chats = Chat::where('user_id', $user->id)
            ->orWhere('admin_id', $user->id)
            ->with(['lastMessage', 'user', 'admin'])
            ->latest('updated_at')
            ->get();
        
        $unreadCount = ChatMessage::whereHas('chat', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return view('user.pages.chat', compact('chats', 'unreadCount'));
    }

    public function room($id)
    {
        $user = Auth::user();
        
        $chat = Chat::where(function($q) use ($user, $id) {
                $q->where('id', $id);
                $q->where(function($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->orWhere('admin_id', $user->id);
                });
            })
            ->with(['messages' => function($q) {
                $q->latest()->limit(50);
            }, 'messages.user', 'messages.admin'])
            ->firstOrFail();
        
        // Mark messages as read
        ChatMessage::where('chat_id', $chat->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return view('user.pages.chat-room', compact('chat'));
    }

    public function sendMessage(Request $request, $id)
    {
        $user = Auth::user();
        
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $chat = Chat::findOrFail($id);
        
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'receiver_id' => $user->is_admin ? $chat->user_id : $chat->admin_id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        
        $chat->update(['updated_at' => now()]);
        
        // Send real-time notification via Pusher/Laravel Echo
        // event(new NewChatMessage($message));
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'time' => $message->created_at->format('H:i'),
        ]);
    }

    public function getNewMessages($id)
    {
        $user = Auth::user();
        
        $messages = ChatMessage::where('chat_id', $id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->get();
        
        // Mark as read
        ChatMessage::whereIn('id', $messages->pluck('id'))->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function startChat(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        
        // Cek apakah sudah ada chat aktif
        $existingChat = Chat::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
        
        if ($existingChat) {
            // Kirim pesan ke chat yang sudah ada
            return $this->sendMessage($request, $existingChat->id);
        }
        
        // Buat chat baru
        $chat = Chat::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'status' => 'active',
        ]);
        
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        
        return redirect()->route('user.chat.room', $chat->id)
            ->with('success', 'Pesan telah terkirim. Admin akan merespon segera.');
    }
}