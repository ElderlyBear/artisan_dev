<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\ChangeLogs;
use Mockery\Exception;
use Laravel\Fortify\TwoFactorAuthenticatable;
use PragmaRX\Google2FA\Google2FA;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_two_factor_auth',
        'two_factor_secret',
        'two_factor_enabled_at',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }


    public function generateTwoFactorSecret(){
        $google2fa = new Google2FA();
        $this->two_factor_secret = $google2fa->generateSecretKey(); // корректный ключ
        $this->is_two_factor_auth = true;
        $this->save();;
    }


    public function enableTwoFactorAuthentication(){
        $this->is_two_factor_auth = true;
        $this->two_factor_enabled_at = now();
        $this->save();
    }

    public function disableTwoFactorAuthentication(){
        $this->is_two_factor_auth = false;
        $this->two_factor_secret = null;
        $this->two_factor_enabled_at = null;
        $this->save();
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

    public function rollback($historyId){
        $mutation = ChangeLogs::find($historyId);
        if($mutation->entity_type == "App\Models\User" || $mutation->entity_id != $this->id){
            throw new Exception('This mutation is not allowed to perform this action');
        }

        $data = json_decode($mutation->data);

        DB::transaction(function() use($data, $historyId){
            $this->update([
                'name' => $data->name,
                'email' => $data->email,
            ]);
        });

        return response()->json(['message' => 'User rollback successfully']);
    }

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
}
