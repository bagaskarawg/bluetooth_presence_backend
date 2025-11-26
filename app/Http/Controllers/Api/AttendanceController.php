<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:classes,id']);
        
        $attendance = Attendance::firstOrCreate([
            'class_id' => $request->class_id,
            'student_id' => $request->user()->id,
        ], [
            'timestamp' => now(),
        ]);

        return $attendance;
    }
}
