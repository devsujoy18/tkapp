<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model
{
    use HasFactory;
    use Sluggable;

     protected $fillable = [
        'user_id', 'name', 'organisation_id', 'type_id', 'category_id', 'venu_type', 'venu_location', 'address_one', 'address_two', 'post_code', 'online_link', 'starts_date', 'ends_date', 'starts_time', 'ends_time'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id', 'id');
    }
}
