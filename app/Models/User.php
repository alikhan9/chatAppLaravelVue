<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function friendsTo()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
        ->withPivot('accepted')
        ->withTimestamps();
    }

    public function friendsFrom()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
        ->withPivot('accepted')
        ->withTimestamps();
    }

    public function pendingFriendsTo()
    {
        return $this->friendsTo()->wherePivot('accepted', 0);
    }

    public function pendingFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('accepted', 0);
    }

    public function acceptedFriendsTo()
    {
        return $this->friendsTo()->wherePivot('accepted', 1);
    }

    public function acceptedFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('accepted', 1);
    }

    public function friends()
    {
        return $this
        ->mergedRelationWithModel(User::class, 'friends_view')
        ->select('id', 'name', 'email');
    }

    public function privateMessages()
    {
        return  $this->hasMany(PrivateMessage::class, 'from')
        ->orWhere('to', '=', $this->id)->orderBy('created_at');
    }
    public function groups()
    {
        return $this->hasMany(Group::class, 'owner');
    }
}
