<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'phone_number',
        'role',
        'avatar',
    ];

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            
            if (Storage::disk('public')->exists($this->avatar)) {
                return Storage::url($this->avatar);
            }
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&size=200';
    }

    /**
     * Delete the user's avatar file when updating or deleting avatar
     */
    public function deleteAvatar()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            Storage::disk('public')->delete($this->avatar);
        }
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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

    public function bookings(): HasMany
    {
        // Asumsi: tabel 'bookings' memiliki kolom 'user_id'
        return $this->hasMany(Booking::class, 'user_id');
    }
    
    /**
     * Memeriksa apakah pengguna adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Memeriksa apakah pengguna adalah pengguna biasa
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    
    /**
     * Mendapatkan label role yang readable
     */
    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'user' => 'Pengguna',
            default => 'Tidak Diketahui',
        };
    }
}
