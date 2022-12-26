<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    public function sitesByEmployees()
    {
        // employees => data entry operator
        return $this->hasMany(Sites::class, 'employees', 'id',);
    }

    public function sitesByProjectManagers()
    {
        // site_admin => project managers
        return $this->hasMany(Sites::class, 'site_admin', 'id');
    }

    public function sites(){
        return $this->hasMany(Sites::class)->where('site_admin', 'id')->orWhere('employees', 'id');
    }
    public $roles = [
        1 => 'Super Admin',
        2 => 'Admin',
        3 => 'Project Manager',
        4 => 'Site Data Entry',
    ];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'status',
        'raw_password'
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
}
