<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->classes()->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        
        $class = $request->user()->classes()->create([
            'name' => $request->name,
            'is_active' => true,
            'start_time' => now(),
            'otp' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        return $class;
    }

    public function end(Request $request, $id)
    {
        $class = $request->user()->classes()->findOrFail($id);
        
        $class->update([
            'is_active' => false,
            'end_time' => now(),
        ]);

        return $class;
    }

    public function attendance(Request $request, $id)
    {
        $class = $request->user()->classes()->findOrFail($id);
        
        $attendances = $class->attendances()
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'classSessionId' => $attendance->class_id,
                    'studentId' => $attendance->student->nidn_npm,
                    'studentName' => $attendance->student->name,
                    'timestamp' => $attendance->created_at->toIso8601String(),
                    'photo_url' => $attendance->photo_path ? url('storage/' . $attendance->photo_path) : null,
                ];
            });

        return $attendances;
    }

    public function show($id)
    {
        return \App\Models\ClassSession::findOrFail($id);
    }
}
