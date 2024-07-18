<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kuisioner;
use App\Models\Responden;

class AnswerHarapan extends Model
{
    use HasFactory;

    protected $table = 'aanswer_harapan';

    protected $fillable = [
        'question_id',
        'responden_id',
        'category_id',
        'jawaban1',
        'jawaban2',
        'jawaban3',
        'jawaban4',
        'jawaban5',
    ];

    public function question()
    {
        return $this->belongsTo(Kuisioner::class, 'question_id');
    }

    public function respondent()
    {
        return $this->belongsTo(Responden::class, 'responden_id');
    }
}
