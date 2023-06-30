<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Organisationuserrequest extends Model
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
        'user_id','organisation_id', 'role_id', 'email'
    ];
}
