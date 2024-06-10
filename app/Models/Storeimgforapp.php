<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storeimgforapp extends Model
{
    use HasFactory;

    protected $fillable = ['modify_name','station_id','storefornt_img'];

    public function storedata()
    {
        return $this->hasOne(Importdata::class,'id','station_id');
    }
}
