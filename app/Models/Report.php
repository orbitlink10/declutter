<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    public const REASONS = [
        'fraud',
        'spam',
        'offensive',
        'prohibited',
        'other',
    ];

    public const STATUSES = [
        'pending',
        'reviewed',
        'dismissed',
    ];

    protected $fillable = [
        'user_id',
        'item_id',
        'reason',
        'details',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
