<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'prijs',
        'beschikbare_aantal',
        'gereserveerd_aantal',
        'categorie',
    ];

    protected $casts = [
        'prijs' => 'decimal:2',
        'beschikbare_aantal' => 'integer',
        'gereserveerd_aantal' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
