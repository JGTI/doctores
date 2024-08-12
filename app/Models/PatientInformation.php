<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInformation extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_name',
        'curp',
        'rfc',
        'address',
        'dob',
        'gender',
        'medical_history',
        'allergies',
        'current_medication',
        'phone',
        'email'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
