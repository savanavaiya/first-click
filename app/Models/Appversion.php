<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appversion extends Model
{
    use HasFactory;

    protected $fillable = ['current_version'];
}
