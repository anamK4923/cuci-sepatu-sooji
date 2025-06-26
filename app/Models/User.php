<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nisn',
        'no_hp',
        'asal_sekolah',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'dark_mode',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfileImageAttribute()
    {
        // Kalau kamu pakai column foto di table users
        if ($this->foto) {
            return asset('storage/profile/' . $this->foto);
        }
        // default avatar
        return asset('images/ame.jpg');
    }

    public function adminlte_image()
    {
        return $this->profile_image;
    }

    /**
     * Get the user's average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get the user's total reviews count
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
