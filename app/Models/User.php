<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\EmailVerificationNotification;

class User extends Authenticatable implements MustVerifyEmail {

    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'account_type',
        'phone_number',
        'email_verified_at'
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
    protected $appends = [
        'name',
    ];

    public function getNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function projectDetail() {
        return $this->hasOne('App\Models\ProjectDetail', 'user_id', 'id');
    }

    public function fundusDetail() {
        return $this->hasOne('App\Models\FundusDetail', 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPasswordNotification($token, $this->name, $this->email));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new EmailVerificationNotification($this->name, $this->account_type));
    }

}
