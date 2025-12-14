<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'class_id',
        'student_id',
        'timestamp',
        'photo_path',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
