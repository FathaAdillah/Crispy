<?php

namespace App\Models;

use App\Models\Code;
use App\Models\Variable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kuisioner extends Model
{
    use HasFactory;

    protected $table = 'aquestion';

    protected $fillable = [
        'question',
        'code_id',
        'variable_id',
        'created_at',
        'user_create',
    ];

    public function variable()
    {
        return $this->belongsTo(Variable::class, 'variable_id');
    }
    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }
}
