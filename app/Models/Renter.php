<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use \Carbon\Carbon;

class Renter extends Authenticatable
{
     use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'renters';  
    protected $primaryKey = 'rentersid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'avatar',
        'username',
        'email',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'mobile_primary',
        'mobile_secondary',
        'homeno',
        'branchid',
        'branchname',
        'cabid',
        'cabinetname',
        'duedate',
        'rnotes',
        'BLID',
        'accesstype',
        'created_by',
        'updated_by',
        'timerecorded',
        'mod',
        'copied',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];
}
