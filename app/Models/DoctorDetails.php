<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDetails extends Model
{
    use HasFactory;

    protected $table = 'doctor_details';

    protected $fillable = [
        'user_id',
        'specialty',
        'license_number',
        'office_address',
        'office_hours',
        'joining_date',
        'additional_contact_info',
        'state',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
