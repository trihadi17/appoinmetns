<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppointment extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'user_id',
        'appointment_id',
    ];

    // Nonaktifkan timestamps
    public $timestamps = false;

      public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
