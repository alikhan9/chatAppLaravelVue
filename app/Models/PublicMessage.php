<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicMessage extends Model
{
    use HasFactory;

    protected $fillable = ['to','from','message'];
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'from');
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'to');
    }
}
