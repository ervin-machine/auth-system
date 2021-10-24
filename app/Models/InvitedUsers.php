<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedUsers extends Model
{
    use HasFactory;
    protected $table = 'invitedUsers';
    protected $fillable = [
        'name',
        'user_name',
        'avatar',
        'email',
        'user_role',
    ];
}
