<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_opening_id',
        'desired_position',
        'name',
        'email',
        'phone',
        'cover_letter',
        'cv_path',
        'status',
    ];

    protected $casts = [
        'job_opening_id' => 'integer',
    ];

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function getCvUrlAttribute()
    {
        return $this->cv_path ? asset('storage/' . $this->cv_path) : null;
    }
}
