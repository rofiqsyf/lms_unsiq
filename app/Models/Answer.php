<?php
namespace App\Models;

class Answer extends BaseModel
{
    protected string $table = 'answers';
    protected array $fillable = ['attempt_id', 'question_id', 'answer_text', 'is_correct', 'points_earned'];
}
