<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    public const CONDITIONS = ['new', 'like_new', 'good', 'fair', 'for_parts'];

    public const STATUSES = ['draft', 'active', 'sold', 'removed'];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'condition',
        'price',
        'negotiable',
        'county',
        'town',
        'contact_phone',
        'status',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'negotiable' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Item $item): void {
            if (blank($item->slug)) {
                $item->slug = static::generateUniqueSlug($item->title);
            }

            if (blank($item->status)) {
                $item->status = 'draft';
            }
        });

        static::updating(function (Item $item): void {
            if ($item->isDirty('title')) {
                $item->slug = static::generateUniqueSlug($item->title, $item->id);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function getPrimaryImageAttribute(): ?ItemImage
    {
        if ($this->relationLoaded('images')) {
            return $this->images->first();
        }

        return $this->images()->first();
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'active';
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
