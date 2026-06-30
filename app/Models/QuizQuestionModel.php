<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizQuestionModel extends Model
{
    protected $table = 'cms_quiz_questions';
    protected $allowedFields = ['question_text', 'sort_order'];
}
