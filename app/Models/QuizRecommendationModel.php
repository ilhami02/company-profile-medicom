<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizRecommendationModel extends Model
{
    protected $table = 'cms_quiz_recommendations';
    protected $allowedFields = ['division_name', 'description'];
}
