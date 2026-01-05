<?php
# language: php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'starts_at',
        'ticket_sale_start_at',
        'is_favorite',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ticket_sale_start_at' => 'datetime',
        'is_favorite' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class)->orderBy('order');
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class)->orderBy('categorie');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')->withTimestamps();
    }
}
