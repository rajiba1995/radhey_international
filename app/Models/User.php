<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'password',
        'phone',
        'whatsapp_no',
        'gst_number',
        'gst_certificate_image',
        'credit_limit',
        'credit_days',

    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function addresses()
    // {
    //     return $this->hasMany(UserAddress::class);
    // }
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($user) {
        $user->address()->delete(); // Delete related UserAddress
    });
}
}
