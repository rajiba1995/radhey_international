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
    protected $table = 'users';
    protected $fillable = [
        'emergency_contact_person',
        'emergency_mobile',
        'emergency_whatsapp',
        'emergency_address',
        'branch_id',
        'employee_id',
        'country_id',
        'name',
        'surname',
        'prof_name',
        'dob',
        'emp_code',
        'user_type',
        'business_type',
        'designation',
        'company_name',
        'employee_rank',
        'email',
        'password',
        'phone',
        'whatsapp_no',
        'aadhar_name',
        'gst_number',
        'gst_certificate_image',
        'credit_limit',
        'credit_days',
        'image',
        'passport_id_front',
        'passport_id_back',
        'passport_expiry_date',
        'passport_issued_date',
        'passport_no',
        'visa_no',
        'profile_image',
        'verified_video',
        
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
    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_user');
    }
    public function designationDetails()
    {
        return $this->belongsTo(Designation::class, 'designation', 'id');
    }
    public function bank()
    {
        return $this->hasOne(UserBank::class);
    }
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function UserAddress()
    {
        return $this->hasMany(UserAddress::class);
    }


    public function billingAddress()
    {
        return $this->hasOne(UserAddress::class)->where('address_type', 1);
    }

    public function shippingAddress()
    {
        return $this->hasOne(UserAddress::class)->where('address_type', 2);
    }
    
    public function billingAddressLatest()
    {
        return $this->hasOne(UserAddress::class)->where('address_type', 1) ->latest('created_at');
    }

    public function shippingAddressLatest()
    {
        return $this->hasOne(UserAddress::class)->where('address_type', 2) ->latest('created_at');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->address()->delete(); // Delete related UserAddress
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }
    public function ordersAsCustomer()
    {
        return $this->hasMany(Order::class, 'customer_id'); // 'customer_id' is the foreign key in the orders table
    }

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Add relationship to Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'supplier_id');
    }
}
