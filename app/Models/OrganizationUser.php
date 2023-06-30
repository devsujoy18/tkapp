<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasFactory;
    protected $table = 'organisation_user';
    // Specify any additional columns or configurations as needed
    protected $fillable = [
        'organisation_id', 'user_id', 'role_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


}
