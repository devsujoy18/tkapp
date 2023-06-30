<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id', 'name', 'country_id','address','contact_email','contact_phone_prefix','contact_phone','contact_person'
    ];

   public function users()
   {
       return $this->belongsToMany(User::class, 'organisation_user')->withPivot('role_id');
   }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'organisation_user')->withPivot('organisation_id');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
