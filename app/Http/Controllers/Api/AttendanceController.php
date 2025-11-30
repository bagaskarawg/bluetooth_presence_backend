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
        ]);
        
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
