<?php

namespace App\Models;

use App\Models\AnswerHarapan;
use App\Models\AnswerKepuasan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Responden extends Model
{
    use HasFactory;

    protected $table = 'aresponden';

    protected $fillable = [
        'name',
        'email',
        'pekerjaan',
        'pekerjaan_lain',
        'instansi',
        'jenis_kelamin',
        'bukti',
    ];

    public function answersHarapan()
    {
        return $this->hasMany(AnswerHarapan::class, 'responden_id');
    }

    public function answersKepuasan()
    {
        return $this->hasMany(AnswerKepuasan::class, 'responden_id');
    }
}
