<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\User;
use App\Models\Appointment;
use App\Models\UserAppointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            // Current User
            $currentUser = JWTAuth::user()->id;

            // DISATUKAN
            $data = Appointment::with(['creator','participants.user'])->where('creator_id', $currentUser)
            ->orWhereHas('participants.user', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser);
            })->get();

            // DIPISAH - PISAH
            // BERDASARKAN APPOINTMENT YANG DIBUAT (CREATE)
            // $data = Appointment::with(['creator','participants.user'])->where('creator_id', $currentUser)->get();
        
            // BERDASARKAN APPOINTMENT YANG DiINVITE USER LAIN (INVITE)
            // $dati = Appointment::with(['creator','participants.user'])->whereHas('participants.user', function ($query) use ($currentUser) {
            //     $query->where('user_id', $currentUser);
            // })->get();

            return response([
                'success' => true,
                'message' => 'Appointment data',
                'data' => $data,
            ],200);

        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'An error occurred, please try again.' . $e
            ],500);
        }
    }

    // Get Other Users
    public function otherUsers(){

        try {
            
            $users = User::where('id', '!=', JWTAuth::user()->id)->get();

            return response([
                'success' => true,
                'message' => 'User data',
                'data' => $users,
            ],200);

        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'An error occurred, please try again.' . $e
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        try {
            DB::beginTransaction();

            // Konversi Input waktu ke UTC berdasarkan zona waktu current user
            $start = Carbon::parse($request->start, JWTAuth::user()->preferred_timezone)->setTimezone('UTC');
            $end = Carbon::parse($request->end, JWTAuth::user()->preferred_timezone)->setTimezone('UTC');
            if ($start >= $end) {
                return response([
                    'message' => 'The start time cannot be greater than or equal to the end time.'
                ], 400);
            }

            // Get Other User
            $otherUser = User::find($request->user_id);
            if (!$otherUser) {
                return response([
                        'success' => true,
                        'message' => 'User data not found'
                ], 404);
            }

            // Konversi waktu ke zona waktu pengguna yang diundang
            $otherStart = $start->setTimezone($otherUser->preferred_timezone);
            $otherEnd = $end->setTimezone($otherUser->preferred_timezone);

            // Cek jam kerja 8-17
            if ($otherStart->hour < 8 || $otherEnd->hour > 16) {
                return response([
                        'message' => 'The time is invalid for the other users time zone. Must be between 8am and 5pm.',
                ], 400);
            }

            // Create (Appointment)
            $appointment = Appointment::create([
                'title' => $request->title,
                'creator_id' => JWTAuth::user()->id,
                'start' =>$start->utc(),
                'end' => $end->utc(),
            ]);

            // Create (User appointment)
            $userAppointment = UserAppointment::create([
                'user_id' => $request->user_id,
                'appointment_id' => $appointment->id,
            ]);

            DB::commit();

            return response([
                    'success' => true,
                    'message' => 'Appointment data has been successfully added',
                    'data' => $appointment
                ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            
            return response([
                'status' => false,
                'message' => 'An error occurred, please try again.' . $e
            ], 500);
        }
    }
   
}
