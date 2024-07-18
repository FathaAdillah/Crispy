<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kuisioner;

class Category extends Model
{
    use HasFactory;


    protected $table = 'acategory';

    protected $fillabel = [
        'name',
    ];

    public $timestamps = false;

}
