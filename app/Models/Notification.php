<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'type',
        'message',
        'sent_at',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
     public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
