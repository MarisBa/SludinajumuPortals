<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function otherParty(User $user): ?User
    {
        if ($user->id === $this->buyer_id) {
            return $this->seller;
        }
        if ($user->id === $this->seller_id) {
            return $this->buyer;
        }
        return null;
    }

    public function unreadCountFor(User $user): int
    {
        if ($user->id === $this->buyer_id) {
            return (int) $this->buyer_unread_count;
        }
        if ($user->id === $this->seller_id) {
            return (int) $this->seller_unread_count;
        }
        return 0;
    }

    public function markReadFor(User $user): void
    {
        if ($user->id === $this->buyer_id) {
            $this->update(['buyer_unread_count' => 0]);
        } elseif ($user->id === $this->seller_id) {
            $this->update(['seller_unread_count' => 0]);
        }

        $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function canBeAccessedBy(User $user): bool
    {
        return $user->id === $this->buyer_id || $user->id === $this->seller_id;
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('buyer_id', $userId)
              ->orWhere('seller_id', $userId);
        });
    }
}
