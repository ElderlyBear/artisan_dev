<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAndRole extends Model
{
    protected $table = 'user_roles';
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

}
