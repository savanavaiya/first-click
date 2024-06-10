<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Forapprovalstation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','brand','store_name','store_address', 'city', 'store_location','store_location_latitude','store_location_longitude','diesel','gasoline','landmarks','brand_logo','store_image','forfil_price_diesel','forfil_price_gasoline','nick_name','comments','priceboardimage','status'];


    protected function diesel(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function gasoline(): Attribute
    {
        return Attribute::make(
            get: fn ($value2) => json_decode($value2, true),
            set: fn ($value2) => json_encode($value2),
        );
    }
}
