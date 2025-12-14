<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'photo' => 'required|image|max:2048', // Max 2MB
            'otp' => 'required|string|size:6',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $class = \App\Models\ClassSession::findOrFail($request->class_id);
        if ($class->otp !== $request->otp) {
            return response()->json(['message' => 'Kode OTP salah.'], 422);
        }
        
        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('attendance_photos', 'public');
        }

        $attendance = Attendance::firstOrCreate([
            'class_id' => $request->class_id,
            'student_id' => $request->user()->id,
        ], [
            'timestamp' => now(),
            'photo_path' => $path,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return $attendance;
    }

    public function index(Request $request)
    {
        return $request->user()->attendances()
            ->with('classSession')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'class_name' => $attendance->classSession->name,
                    'timestamp' => $attendance->created_at->toIso8601String(),
                    'photo_url' => $attendance->photo_path ? url('storage/' . $attendance->photo_path) : null,
                ];
            });
    }
}
