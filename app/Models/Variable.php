<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kuisioner;

class Variable extends Model
{
    use HasFactory;


    protected $table = 'avariable';

    protected $fillabel = [
        'name',
    ];

    public function questions()
    {
        return $this->hasMany(Kuisioner::class, 'variable_id');
    }
}
