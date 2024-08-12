<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'doctor_id',
        'status',
        'contact_name',
        'phone',
        'email',
        'address',
        'rfc',
    ];
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
