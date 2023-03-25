<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner'
    ];
    protected $with = [
        'user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner')->select('id', 'name', 'email');
    }
    public function messages()
    {
        return $this->hasMany(PublicMessage::class, 'to');
    }
}
