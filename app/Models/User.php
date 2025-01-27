<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, Authorizable;

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

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function assemblyConstituency() {
        return $this->belongsTo(AssemblyConstituency::class, 'assembly_id');
    }

    public function religion() {
        return $this->belongsTo(Religion::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function caste() {
        return $this->belongsTo(Caste::class);
    }

    public function education() {
        return $this->belongsTo(Education::class);
    }

    public function profession() {
        return $this->belongsTo(Profession::class);
    }

    public function familyMembers() {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function relationship() {
        return $this->belongsTo(Relationship::class);
    }

    public function reffered_user() {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function zila() {
        return $this->belongsTo(Zilla::class);
    }

    public function mandal() {
        return $this->belongsTo(Mandal::class);
    }

    public function booth() {
        return $this->belongsTo(Booth::class);
    }

    public function village() {
        return $this->belongsTo(Village::class);
    }

    public function bloodGroup() {
        return $this->belongsTo(BloodGroup::class);
    }
}
