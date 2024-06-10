<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'weight',
        'height',
        'user_id',
        'doctor_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
