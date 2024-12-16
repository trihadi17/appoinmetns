<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'creator_id',
        'start',
        'end',
    ];

    // Nonaktifkan timestamps
    public $timestamps = false;

    public function creator(){
        return $this->belongsTo(User::class,'creator_id','id');
    }

    public function participants(){
        return $this->hasOne(UserAppointment::class,'appointment_id','id');
    }

}
