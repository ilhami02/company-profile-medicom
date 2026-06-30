<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizOptionModel extends Model
{
    protected $table = 'cms_quiz_options';
    protected $allowedFields = ['question_id', 'option_text'];
}
