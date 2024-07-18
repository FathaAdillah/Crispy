<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kuisioner;

class Variable extends Model
{
    use HasFactory;


    protected $table = 'avariable';

    protected $fillable = [
        'name',
        'is_delete',
        'is_active',
    ];

    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany(Kuisioner::class, 'variable_id');
    }
}
