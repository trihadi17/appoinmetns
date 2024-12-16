<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'preferred_timezone',
    ];

     // Implementasi JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengambil primary key sebagai identifier
    }

    public function getJWTCustomClaims()
    {
        return []; // kustomisasi klaim (contoh : menambah role/status)
    }

    public function createdappointment(){
        return $this->hasMany(Appointment::class, 'creator_id', 'id');
    }

    public function invitedAppointment(){
        return $this->hasMany(UserAppointment::class, 'user_id', 'id');
    }
}
