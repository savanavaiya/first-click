<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pricechangereq extends Model
{
    use HasFactory;

    protected $fillable = ['modify_name', 'user_id','station_id','diesel','gasoline','detail_photo','comments','status'];

    public function storedata()
    {
        return $this->hasOne(Importdata::class,'id','station_id');
    }

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
