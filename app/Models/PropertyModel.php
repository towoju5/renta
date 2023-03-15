<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        "property_features" =>  "array",
        "property_images" =>  "array"
    ];

    protected $hidden = [
        'user_id', 'created_at', 'deleted_at'
    ];

    public function user(){
        return $this->hasMany(User::class);
    }
}
