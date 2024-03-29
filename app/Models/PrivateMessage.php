<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    use HasFactory;

    protected $fillable = ['from','to','message'];

    public function user()
    {
        return $this->belongsTo(User::class, 'from');
    }
}
