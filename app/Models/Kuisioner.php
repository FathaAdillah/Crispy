<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Variable;

class Kuisioner extends Model
{
    use HasFactory;

    protected $table = 'aquestion';

    protected $fillable = [
        'question',
        'variable_id',
        'created_at',
        'user_create',
    ];

    public function variable()
    {
        return $this->belongsTo(Variable::class, 'variable_id');
    }
}
