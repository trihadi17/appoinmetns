<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use App\Models\UserAppointment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // USER
        $user = [
            [
                'name' => 'Asep',
                'username' => 'asep',
                'preferred_timezone' => 'Asia/Jakarta',
            ],
            [
                'name' => 'Agus',
                'username' => 'agus',
                'preferred_timezone' => 'Asia/Jayapura',
            ],
            [
                'name' => 'Ujang',
                'username' => 'ujang',
                'preferred_timezone' => 'Asia/Jakarta',
            ],
        ];

        User::insert($user);

        // APPOINTMENT
        $appoinment = [
            [
                'title' => 'Meeting with agus',
                'creator_id' => 1,
                'start' => Carbon::now()->setTimezone('UTC'),
                'end' => Carbon::now()->setTimezone('UTC'),
            ],
            [
                'title' => 'Meeting with asep',
                'creator_id' => 3,
                'start' => Carbon::now()->setTimezone('UTC'),
                'end' => Carbon::now()->setTimezone('UTC'),
            ],
        ];

        Appointment::insert($appoinment);

        // USER APPOINTMENT
        $userAppointment = [
            [
                'user_id' => 2,
                'appointment_id' => 1,
            ],
            [
                'user_id' => 1,
                'appointment_id' => 2,
            ],
        ];

        UserAppointment::insert($userAppointment);
    }
}
