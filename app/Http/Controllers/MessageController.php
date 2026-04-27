<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::forUser($user->id)
            ->with(['advertisement', 'buyer', 'seller', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        $totalUnread = $user->totalUnreadMessages();

        return view('messages.index', compact('conversations', 'totalUnread'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->canBeAccessedBy($user)) {
            abort(403, 'Tev nav atļaujas piekļūt šai sarunai.');
        }

        $conversation->load(['advertisement', 'buyer', 'seller']);
        $messages = $conversation->messages()->with('sender')->get();

        $conversation->markReadFor($user);

        $otherParty = $conversation->otherParty($user);

        $allConversations = Conversation::forUser($user->id)
            ->with(['advertisement', 'buyer', 'seller'])
            ->orderByDesc('last_message_at')
            ->limit(20)
            ->get();

        return view('messages.show', compact(
            'conversation',
            'messages',
            'otherParty',
            'allConversations'
        ));
    }

    public function start(Request $request)
    {
        $request->validate([
            'advertisement_id' => 'required|integer|exists:advertisements,id',
            'body' => 'required|string|min:1|max:2000',
        ], [
            'advertisement_id.required' => 'Sludinājums ir obligāts.',
            'advertisement_id.exists' => 'Sludinājums neeksistē.',
            'body.required' => 'Ievadi ziņas tekstu.',
            'body.min' => 'Ziņa nevar būt tukša.',
            'body.max' => 'Ziņa pārāk gara (max 2000 rakstzīmes).',
        ]);

        $user = Auth::user();
        $ad = Advertisement::findOrFail($request->advertisement_id);

        if ((int) $ad->user_id === (int) $user->id) {
            return back()->with('error', 'Nevar rakstīt ziņas pats sev.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'advertisement_id' => $ad->id,
                'buyer_id' => $user->id,
                'seller_id' => $ad->user_id,
            ],
            [
                'last_message_at' => now(),
                'last_message_preview' => mb_substr($request->body, 0, 200),
            ]
        );

        DB::transaction(function () use ($conversation, $user, $request) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => $request->body,
            ]);

            $conversation->update([
                'last_message_at' => now(),
                'last_message_preview' => mb_substr($request->body, 0, 200),
            ]);

            $conversation->increment('seller_unread_count');
        });

        return redirect()
            ->route('messages.show', $conversation->id)
            ->with('message', 'Ziņa nosūtīta!');
    }

    public function store(Conversation $conversation, Request $request)
    {
        $user = Auth::user();

        if (!$conversation->canBeAccessedBy($user)) {
            abort(403, 'Tev nav atļaujas šajā sarunā.');
        }

        $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ], [
            'body.required' => 'Ievadi ziņas tekstu.',
            'body.max' => 'Ziņa pārāk gara.',
        ]);

        $message = DB::transaction(function () use ($conversation, $user, $request) {
            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => $request->body,
            ]);

            $unreadField = $user->id === (int) $conversation->buyer_id
                ? 'seller_unread_count'
                : 'buyer_unread_count';

            $conversation->update([
                'last_message_at' => now(),
                'last_message_preview' => mb_substr($request->body, 0, 200),
            ]);

            $conversation->increment($unreadField);

            return $msg;
        });

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $user->name,
                    'created_at' => $message->created_at->toISOString(),
                    'is_mine' => true,
                ],
            ]);
        }

        return back()->with('message', 'Ziņa nosūtīta!');
    }

    public function poll(Conversation $conversation, Request $request)
    {
        $user = Auth::user();

        if (!$conversation->canBeAccessedBy($user)) {
            abort(403);
        }

        $afterId = (int) $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $afterId)
            ->with('sender')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($m) use ($user) {
                return [
                    'id' => $m->id,
                    'body' => $m->body,
                    'sender_id' => $m->sender_id,
                    'sender_name' => $m->sender->name ?? '?',
                    'created_at' => $m->created_at->toISOString(),
                    'is_mine' => $m->sender_id === $user->id,
                ];
            });

        if ($messages->isNotEmpty()) {
            $hasOtherPartyMessages = $messages->contains(fn ($m) => !$m['is_mine']);
            if ($hasOtherPartyMessages) {
                $conversation->markReadFor($user);
            }
        }

        return response()->json([
            'messages' => $messages->values(),
        ]);
    }

    public function unreadCount()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['total' => 0]);
        }

        return response()->json([
            'total' => $user->totalUnreadMessages(),
        ]);
    }
}
