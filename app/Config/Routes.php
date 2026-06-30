<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

# ============================================
#                 USER ROUTES
# ============================================

// Halaman umum
$routes->get('/', 'MainUsers::index');
$routes->get('/prestasi', 'MainUsers::prestasi');
$routes->get('/about', 'MainUsers::about');
$routes->get('/pengurus', 'MainUsers::pengurus');
$routes->get('/laporan', 'MainUsers::laporan');

// Program kerja
$routes->get('/program-kerja/1', 'MainUsers::proker1');
$routes->get('/program-kerja/2', 'MainUsers::proker2');
$routes->get('/program-kerja/3', 'MainUsers::proker3');

# ============================================
#                 QUIZ ROUTES
# ============================================

// Halaman daftar divisi kuis
$routes->get('/quiz', 'QuizController::quiz');

// Tombol mulai
$routes->get('/quiz/start', 'QuizController::startQuiz');

// Isi data peserta
$routes->get('/quiz/isidata', 'QuizController::isiDataQuiz');
$routes->post('/quiz/isidata', 'QuizController::saveDataQuiz');

// Mulai proses kuis
$routes->post('/quiz/start-process', 'QuizController::startQuizProcess');

// Redirect default pertanyaan → ke nomor 1
$routes->get('/quiz/pertanyaan', fn() => redirect()->to('/quiz/pertanyaan/1'));

// Tampilkan pertanyaan nomor N
$routes->get('/quiz/pertanyaan/(:num)', 'QuizController::showPertanyaan/$1');

// Proses jawaban pertanyaan N
$routes->post('/quiz/jawab/(:num)', 'QuizController::prosesJawaban/$1');

// Hasil kuis
$routes->post('/quiz/result', 'QuizController::hasilQuiz');

# ============================================
#                 AUTH ROUTES
# ============================================
$routes->get('/login', 'AuthController::index');
$routes->post('/login/process', 'AuthController::loginProcess');
$routes->get('/logout', 'AuthController::logout');

# ============================================
#                 ADMIN ROUTES
# ============================================
$routes->group('admin', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('/', 'Admin::index');

    // Beranda editor
    $routes->get('hero', 'Admin::hero');
    $routes->post('updateHero', 'Admin::updateHero');

    $routes->get('partners', 'Admin::partners');
    $routes->post('addPartner', 'Admin::addPartner');
    $routes->get('deletePartner/(:num)', 'Admin::deletePartner/$1');

    $routes->get('programs', 'Admin::programs');
    $routes->post('addProgram', 'Admin::addProgram');
    $routes->get('deleteProgram/(:num)', 'Admin::deleteProgram/$1');

    $routes->get('achievements', 'Admin::achievements');
    $routes->post('addAchievement', 'Admin::addAchievement');
    $routes->get('deleteAchievement/(:num)', 'Admin::deleteAchievement/$1');

    $routes->get('divisions', 'Admin::divisions');
    $routes->post('addDivision', 'Admin::addDivision');
    $routes->get('deleteDivision/(:num)', 'Admin::deleteDivision/$1');

    $routes->get('gallery', 'Admin::gallery');
    $routes->post('addGallery', 'Admin::addGallery');
    $routes->get('deleteGallery/(:num)', 'Admin::deleteGallery/$1');
    $routes->post('gallery/update', 'Admin::updateGallery');

    // About Page
    $routes->get('about', 'Admin::about');
    $routes->post('updateAbout', 'Admin::updateAbout');
    $routes->post('addObjective', 'Admin::addObjective');
    $routes->get('deleteObjective/(:num)', 'Admin::deleteObjective/$1');

    // Prestasi Page
    $routes->get('prestasiPage', 'Admin::prestasiPage');
    $routes->post('updatePrestasiPage', 'Admin::updatePrestasiPage');

    // Pengurus
    $routes->get('pengurus', 'Admin::pengurus');
    $routes->post('addMember', 'Admin::addMember');
    $routes->get('deleteMember/(:num)', 'Admin::deleteMember/$1');

    // Laporan
    $routes->get('laporan', 'Admin::laporan');
    $routes->post('updateLaporan', 'Admin::updateLaporan');

    // Quiz Management
    $routes->get('quiz-questions', 'Admin::quizQuestions');
    $routes->post('addQuizQuestion', 'Admin::addQuizQuestion');
    $routes->get('deleteQuizQuestion/(:num)', 'Admin::deleteQuizQuestion/$1');

    $routes->get('quiz-recommendations', 'Admin::quizRecommendations');
    $routes->post('addQuizRecommendation', 'Admin::addQuizRecommendation');
    $routes->get('deleteQuizRecommendation/(:num)', 'Admin::deleteQuizRecommendation/$1');
});

# ============================================
#              API ROUTES (REST)
# ============================================

// Public API (no auth required) - with CORS
$routes->group('api', ['filter' => 'corsFilter'], function($routes) {

    // Handle preflight OPTIONS requests for all API routes
    $routes->options('(:any)', static function () {});

    // Auth
    $routes->post('auth/login', 'Api::login');

    // Public GET endpoints
    $routes->get('hero',               'Api::hero');
    $routes->get('partners',           'Api::partners');
    $routes->get('programs',           'Api::programs');
    $routes->get('achievements',       'Api::achievements');
    $routes->get('divisions',          'Api::divisions');
    $routes->get('members',            'Api::members');
    $routes->get('gallery',            'Api::gallery');
    $routes->get('objectives',         'Api::objectives');
    $routes->get('pages/(:segment)',   'Api::pages/$1');
    $routes->get('reports',            'Api::reports');
    $routes->get('quiz/questions',     'Api::quizQuestions');
    $routes->get('quiz/recommendations', 'Api::quizRecommendations');

    // Protected endpoints (require JWT)
    $routes->group('', ['filter' => 'apiAuth'], function($routes) {
        // Hero
        $routes->post('hero', 'Api::updateHero');

        // Partners
        $routes->post('partners',            'Api::addPartner');
        $routes->delete('partners/(:num)',   'Api::deletePartner/$1');

        // Programs
        $routes->post('programs',            'Api::addProgram');
        $routes->delete('programs/(:num)',   'Api::deleteProgram/$1');

        // Achievements
        $routes->post('achievements',            'Api::addAchievement');
        $routes->delete('achievements/(:num)',   'Api::deleteAchievement/$1');

        // Divisions
        $routes->post('divisions',            'Api::addDivision');
        $routes->delete('divisions/(:num)',   'Api::deleteDivision/$1');

        // Gallery
        $routes->post('gallery',            'Api::addGallery');
        $routes->put('gallery/(:num)',      'Api::updateGallery/$1');
        $routes->delete('gallery/(:num)',   'Api::deleteGallery/$1');

        // Members
        $routes->post('members',            'Api::addMember');
        $routes->delete('members/(:num)',   'Api::deleteMember/$1');

        // Pages
        $routes->put('pages/(:segment)', 'Api::updatePage/$1');

        // Objectives
        $routes->post('objectives',            'Api::addObjective');
        $routes->delete('objectives/(:num)',   'Api::deleteObjective/$1');

        // Reports
        $routes->put('reports', 'Api::updateReports');

        // Quiz Questions
        $routes->post('quiz/questions',            'Api::addQuizQuestion');
        $routes->put('quiz/questions/(:num)',       'Api::updateQuizQuestion/$1');
        $routes->delete('quiz/questions/(:num)',    'Api::deleteQuizQuestion/$1');

        // Quiz Recommendations
        $routes->post('quiz/recommendations',            'Api::addQuizRecommendation');
        $routes->put('quiz/recommendations/(:num)',      'Api::updateQuizRecommendation/$1');
        $routes->delete('quiz/recommendations/(:num)',   'Api::deleteQuizRecommendation/$1');
    });
});

