<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $table = 'acode';

    protected $fillable = [
        'name',
        'is_delete',
        'is_active',
    ];

    public function questions()
    {
        return $this->hasMany(Kuisioner::class, 'code_id');
    }
}
