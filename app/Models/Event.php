<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'capacity',
        'price',
        'images', // JSON column
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'images' => 'array', // Automatically cast JSON to array
    ];

    /**
     * Relationship: Event belongs to a User (creator/owner)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
