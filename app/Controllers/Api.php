<?php

namespace App\Controllers;

use App\Models\HeroModel;
use App\Models\PageModel;
use App\Models\ReportModel;
use App\Models\MemberModel;
use App\Models\PartnerModel;
use App\Models\ProgramModel;
use App\Models\GalleryModel;
use App\Models\DivisionModel;
use App\Models\ObjectiveModel;
use App\Models\AchievementModel;
use App\Models\QuizQuestionModel;
use App\Models\QuizOptionModel;
use App\Models\QuizRecommendationModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Api extends BaseController
{
    protected $heroModel, $partnerModel, $programModel, $achievementModel;
    protected $galleryModel, $pageModel, $objectiveModel, $reportModel;
    protected $memberModel, $divisionModel;
    protected $quizQuestionModel, $quizOptionModel, $quizRecommendationModel;

    public function __construct()
    {
        $this->heroModel        = new HeroModel();
        $this->pageModel        = new PageModel();
        $this->reportModel      = new ReportModel();
        $this->memberModel      = new MemberModel();
        $this->partnerModel     = new PartnerModel();
        $this->programModel     = new ProgramModel();
        $this->galleryModel     = new GalleryModel();
        $this->divisionModel    = new DivisionModel();
        $this->objectiveModel   = new ObjectiveModel();
        $this->achievementModel = new AchievementModel();
        $this->quizQuestionModel      = new QuizQuestionModel();
        $this->quizOptionModel        = new QuizOptionModel();
        $this->quizRecommendationModel = new QuizRecommendationModel();
    }

    // =====================================================
    //                  HELPER METHODS
    // =====================================================

    private function jsonResponse($data, int $code = 200)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'status' => ($code >= 200 && $code < 300) ? 'success' : 'error',
                'data'   => $data
            ]);
    }

    private function errorResponse(string $message, int $code = 400)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'status'  => 'error',
                'message' => $message
            ]);
    }

    private function uploadImage($file, $subfolder = '')
    {
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getName();
            $path = 'src/img' . $subfolder;
            $file->move(FCPATH . $path, $newName);
            return '/' . $path . '/' . $newName;
        }
        return null;
    }

    // =====================================================
    //                  AUTH ENDPOINTS
    // =====================================================

    /**
     * POST /api/auth/login
     */
    public function login()
    {
        $username = $this->request->getJSON(true)['username'] ?? $this->request->getPost('username');
        $password = $this->request->getJSON(true)['password'] ?? $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return $this->errorResponse('Username dan password wajib diisi.', 400);
        }

        $db = \Config\Database::connect();
        $user = $db->table('cms_users')->getWhere(['username' => $username])->getRowArray();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->errorResponse('Username atau password salah.', 401);
        }

        $key = getenv('JWT_SECRET_KEY') ?: 'medicom-secret-key-2025';
        $payload = [
            'iss' => 'medicom-api',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // 24 jam
            'uid' => $user['id'],
            'username' => $user['username']
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->jsonResponse([
            'token'    => $token,
            'username' => $user['username'],
            'expires_in' => 86400
        ]);
    }

    // =====================================================
    //               PUBLIC GET ENDPOINTS
    // =====================================================

    /**
     * GET /api/hero
     */
    public function hero()
    {
        return $this->jsonResponse($this->heroModel->first());
    }

    /**
     * GET /api/partners
     */
    public function partners()
    {
        return $this->jsonResponse($this->partnerModel->findAll());
    }

    /**
     * GET /api/programs
     */
    public function programs()
    {
        return $this->jsonResponse($this->programModel->findAll());
    }

    /**
     * GET /api/achievements
     */
    public function achievements()
    {
        return $this->jsonResponse(
            $this->achievementModel->orderBy('year', 'DESC')->findAll()
        );
    }

    /**
     * GET /api/divisions
     */
    public function divisions()
    {
        return $this->jsonResponse($this->divisionModel->findAll());
    }

    /**
     * GET /api/members
     */
    public function members()
    {
        return $this->jsonResponse($this->memberModel->findAll());
    }

    /**
     * GET /api/gallery
     */
    public function gallery()
    {
        return $this->jsonResponse($this->galleryModel->findAll());
    }

    /**
     * GET /api/objectives
     */
    public function objectives()
    {
        return $this->jsonResponse($this->objectiveModel->findAll());
    }

    /**
     * GET /api/pages/(:segment)
     */
    public function pages($slug)
    {
        $page = $this->pageModel->find($slug);
        if (!$page) {
            return $this->errorResponse('Halaman tidak ditemukan.', 404);
        }
        return $this->jsonResponse($page);
    }

    /**
     * GET /api/reports?year=2024
     */
    public function reports()
    {
        $year = $this->request->getGet('year') ?? date('Y');
        $reports = $this->reportModel->where('year', $year)->findAll();

        $mappedReports = [];
        foreach ($reports as $r) {
            $mappedReports[] = $r;
        }

        return $this->jsonResponse([
            'year'    => (int) $year,
            'reports' => $mappedReports
        ]);
    }

    /**
     * GET /api/quiz/questions
     */
    public function quizQuestions()
    {
        $questions = $this->quizQuestionModel->orderBy('sort_order', 'ASC')->findAll();

        foreach ($questions as &$q) {
            $q['options'] = $this->quizOptionModel
                ->where('question_id', $q['id'])
                ->findAll();
        }

        return $this->jsonResponse($questions);
    }

    /**
     * GET /api/quiz/recommendations
     */
    public function quizRecommendations()
    {
        return $this->jsonResponse($this->quizRecommendationModel->findAll());
    }

    // =====================================================
    //            PROTECTED CRUD ENDPOINTS
    // =====================================================

    // --- Hero ---

    /**
     * POST /api/hero
     */
    public function updateHero()
    {
        $file = $this->request->getFile('hero_image');
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File hero_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file);
        $id = $this->request->getPost('id') ?? 1;
        $this->heroModel->save(['id' => $id, 'image_path' => $path]);

        return $this->jsonResponse(['message' => 'Hero berhasil diperbarui.', 'image_path' => $path]);
    }

    // --- Partners ---

    /**
     * POST /api/partners
     */
    public function addPartner()
    {
        $file = $this->request->getFile('partner_image');
        $name = $this->request->getPost('name');

        if (empty($name)) {
            return $this->errorResponse('Nama partner wajib diisi.', 400);
        }
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File partner_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/partners');
        $this->partnerModel->save([
            'name'       => $name,
            'image_path' => $path
        ]);

        return $this->jsonResponse(['message' => 'Partner berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/partners/(:num)
     */
    public function deletePartner($id)
    {
        $partner = $this->partnerModel->find($id);
        if (!$partner) {
            return $this->errorResponse('Partner tidak ditemukan.', 404);
        }

        if ($partner['image_path'] && file_exists(FCPATH . $partner['image_path'])) {
            unlink(FCPATH . $partner['image_path']);
        }
        $this->partnerModel->delete($id);

        return $this->jsonResponse(['message' => 'Partner berhasil dihapus.']);
    }

    // --- Programs ---

    /**
     * POST /api/programs
     */
    public function addProgram()
    {
        $title = $this->request->getPost('title');
        $desc  = $this->request->getPost('description');
        $file  = $this->request->getFile('program_image');

        if (empty($title) || empty($desc)) {
            return $this->errorResponse('Title dan description wajib diisi.', 400);
        }
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File program_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/programs');
        $this->programModel->save([
            'title'       => $title,
            'description' => $desc,
            'image_path'  => $path
        ]);

        return $this->jsonResponse(['message' => 'Program berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/programs/(:num)
     */
    public function deleteProgram($id)
    {
        $program = $this->programModel->find($id);
        if (!$program) {
            return $this->errorResponse('Program tidak ditemukan.', 404);
        }

        if ($program['image_path'] && file_exists(FCPATH . $program['image_path'])) {
            unlink(FCPATH . $program['image_path']);
        }
        $this->programModel->delete($id);

        return $this->jsonResponse(['message' => 'Program berhasil dihapus.']);
    }

    // --- Achievements ---

    /**
     * POST /api/achievements
     */
    public function addAchievement()
    {
        $name  = $this->request->getPost('name');
        $year  = $this->request->getPost('year');
        $level = $this->request->getPost('level');
        $file  = $this->request->getFile('image');

        if (empty($name) || empty($year) || empty($level)) {
            return $this->errorResponse('Name, year, dan level wajib diisi.', 400);
        }
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/prestasi');
        $this->achievementModel->save([
            'name'       => $name,
            'year'       => $year,
            'level'      => $level,
            'image_path' => $path ?? '/src/img/default.jpg'
        ]);

        return $this->jsonResponse(['message' => 'Prestasi berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/achievements/(:num)
     */
    public function deleteAchievement($id)
    {
        $item = $this->achievementModel->find($id);
        if (!$item) {
            return $this->errorResponse('Prestasi tidak ditemukan.', 404);
        }

        $this->achievementModel->delete($id);
        return $this->jsonResponse(['message' => 'Prestasi berhasil dihapus.']);
    }

    // --- Divisions ---

    /**
     * POST /api/divisions
     */
    public function addDivision()
    {
        $name  = $this->request->getPost('name');
        $color = $this->request->getPost('color');
        $file  = $this->request->getFile('division_image');

        if (empty($name)) {
            return $this->errorResponse('Nama divisi wajib diisi.', 400);
        }
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File division_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/divisi');

        $colorClass = 'bg-white text-gray-800';
        if ($color == 'blue') {
            $colorClass = 'bg-blue-500 text-white';
        } elseif ($color == 'red') {
            $colorClass = 'bg-red-500 text-white';
        }

        $this->divisionModel->save([
            'name'        => $name,
            'description' => $this->request->getPost('description'),
            'image_path'  => $path,
            'color_class' => $colorClass
        ]);

        return $this->jsonResponse(['message' => 'Divisi berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/divisions/(:num)
     */
    public function deleteDivision($id)
    {
        $item = $this->divisionModel->find($id);
        if (!$item) {
            return $this->errorResponse('Divisi tidak ditemukan.', 404);
        }

        if ($item['image_path'] && file_exists(FCPATH . $item['image_path'])) {
            unlink(FCPATH . $item['image_path']);
        }
        $this->divisionModel->delete($id);

        return $this->jsonResponse(['message' => 'Divisi berhasil dihapus.']);
    }

    // --- Gallery ---

    /**
     * POST /api/gallery
     */
    public function addGallery()
    {
        $currentCount = $this->galleryModel->countAllResults();
        if ($currentCount >= 9) {
            return $this->errorResponse('Maksimal 9 gambar galeri telah tercapai.', 400);
        }

        $file = $this->request->getFile('gallery_image');
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File gallery_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/galeri');
        if ($path) {
            $this->galleryModel->save(['image_path' => $path]);
            return $this->jsonResponse(['message' => 'Galeri berhasil ditambahkan.'], 201);
        }

        return $this->errorResponse('Gagal mengupload gambar.', 500);
    }

    /**
     * PUT /api/gallery/(:num)
     */
    public function updateGallery($id)
    {
        $oldItem = $this->galleryModel->find($id);
        if (!$oldItem) {
            return $this->errorResponse('Galeri tidak ditemukan.', 404);
        }

        $file = $this->request->getFile('gallery_image');
        $path = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $path = $this->uploadImage($file, '/galeri');
            if ($oldItem['image_path'] && file_exists(FCPATH . $oldItem['image_path'])) {
                unlink(FCPATH . $oldItem['image_path']);
            }
        }

        $dataToUpdate = ['id' => $id];
        if ($path) {
            $dataToUpdate['image_path'] = $path;
        }

        $this->galleryModel->save($dataToUpdate);
        return $this->jsonResponse(['message' => 'Galeri berhasil diperbarui.']);
    }

    /**
     * DELETE /api/gallery/(:num)
     */
    public function deleteGallery($id)
    {
        $item = $this->galleryModel->find($id);
        if (!$item) {
            return $this->errorResponse('Galeri tidak ditemukan.', 404);
        }

        if ($item['image_path'] && file_exists(FCPATH . $item['image_path'])) {
            unlink(FCPATH . $item['image_path']);
        }
        $this->galleryModel->delete($id);

        return $this->jsonResponse(['message' => 'Galeri berhasil dihapus.']);
    }

    // --- Members ---

    /**
     * POST /api/members
     */
    public function addMember()
    {
        $name = $this->request->getPost('name');
        $file = $this->request->getFile('member_image');

        if (empty($name)) {
            return $this->errorResponse('Nama pengurus wajib diisi.', 400);
        }
        if (!$file || !$file->isValid()) {
            return $this->errorResponse('File member_image wajib diupload.', 400);
        }

        $path = $this->uploadImage($file, '/pengurus');
        $this->memberModel->save([
            'division_id' => $this->request->getPost('division_id'),
            'name'        => $name,
            'position'    => $this->request->getPost('position'),
            'image_path'  => $path
        ]);

        return $this->jsonResponse(['message' => 'Pengurus berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/members/(:num)
     */
    public function deleteMember($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            return $this->errorResponse('Pengurus tidak ditemukan.', 404);
        }

        if ($member['image_path'] && file_exists(FCPATH . $member['image_path'])) {
            unlink(FCPATH . $member['image_path']);
        }
        $this->memberModel->delete($id);

        return $this->jsonResponse(['message' => 'Pengurus berhasil dihapus.']);
    }

    // --- Pages ---

    /**
     * PUT /api/pages/(:segment)
     */
    public function updatePage($slug)
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $data = [
            'page_slug'        => $slug,
            'hero_title'       => $json['hero_title'] ?? null,
            'hero_description' => $json['hero_description'] ?? null,
            'main_title'       => $json['main_title'] ?? null,
            'main_content'     => $json['main_content'] ?? null,
            'video_url'        => $json['video_url'] ?? null,
        ];

        $this->pageModel->save($data);
        return $this->jsonResponse(['message' => "Halaman '$slug' berhasil diperbarui."]);
    }

    // --- Objectives ---

    /**
     * POST /api/objectives
     */
    public function addObjective()
    {
        $json = $this->request->getJSON(true);
        $content = $json['content'] ?? $this->request->getPost('content');

        if (empty($content)) {
            return $this->errorResponse('Content wajib diisi.', 400);
        }

        $this->objectiveModel->save(['content' => $content]);
        return $this->jsonResponse(['message' => 'Tujuan berhasil ditambahkan.'], 201);
    }

    /**
     * DELETE /api/objectives/(:num)
     */
    public function deleteObjective($id)
    {
        $item = $this->objectiveModel->find($id);
        if (!$item) {
            return $this->errorResponse('Tujuan tidak ditemukan.', 404);
        }

        $this->objectiveModel->delete($id);
        return $this->jsonResponse(['message' => 'Tujuan berhasil dihapus.']);
    }

    // --- Reports ---

    /**
     * PUT /api/reports
     */
    public function updateReports()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $year   = $json['year'] ?? null;
        $months = $json['urls'] ?? null;

        if (empty($year) || empty($months)) {
            return $this->errorResponse('Year dan urls wajib diisi.', 400);
        }

        foreach ($months as $month => $url) {
            $existing = $this->reportModel->where(['year' => $year, 'month' => $month])->first();

            if ($url) {
                $data = ['year' => $year, 'month' => $month, 'url' => $url];
                if ($existing) {
                    $data['id'] = $existing['id'];
                }
                $this->reportModel->save($data);
            } elseif ($existing) {
                $this->reportModel->delete($existing['id']);
            }
        }

        return $this->jsonResponse(['message' => "Laporan tahun $year berhasil diperbarui."]);
    }

    // --- Quiz Questions ---

    /**
     * POST /api/quiz/questions
     */
    public function addQuizQuestion()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $questionText = $json['question_text'] ?? null;
        $sortOrder    = $json['sort_order'] ?? 0;
        $options      = $json['options'] ?? [];

        if (empty($questionText)) {
            return $this->errorResponse('question_text wajib diisi.', 400);
        }

        $this->quizQuestionModel->save([
            'question_text' => $questionText,
            'sort_order'    => $sortOrder
        ]);

        $questionId = $this->quizQuestionModel->getInsertID();

        // Simpan opsi jika ada
        foreach ($options as $optionText) {
            $this->quizOptionModel->save([
                'question_id' => $questionId,
                'option_text' => $optionText
            ]);
        }

        return $this->jsonResponse(['message' => 'Soal quiz berhasil ditambahkan.', 'id' => $questionId], 201);
    }

    /**
     * PUT /api/quiz/questions/(:num)
     */
    public function updateQuizQuestion($id)
    {
        $question = $this->quizQuestionModel->find($id);
        if (!$question) {
            return $this->errorResponse('Soal tidak ditemukan.', 404);
        }

        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $data = ['id' => $id];
        if (isset($json['question_text'])) $data['question_text'] = $json['question_text'];
        if (isset($json['sort_order']))    $data['sort_order']    = $json['sort_order'];

        $this->quizQuestionModel->save($data);

        // Update opsi jika diberikan
        if (isset($json['options'])) {
            // Hapus opsi lama
            $this->quizOptionModel->where('question_id', $id)->delete();
            // Simpan opsi baru
            foreach ($json['options'] as $optionText) {
                $this->quizOptionModel->save([
                    'question_id' => $id,
                    'option_text' => $optionText
                ]);
            }
        }

        return $this->jsonResponse(['message' => 'Soal quiz berhasil diperbarui.']);
    }

    /**
     * DELETE /api/quiz/questions/(:num)
     */
    public function deleteQuizQuestion($id)
    {
        $question = $this->quizQuestionModel->find($id);
        if (!$question) {
            return $this->errorResponse('Soal tidak ditemukan.', 404);
        }

        $this->quizQuestionModel->delete($id);
        return $this->jsonResponse(['message' => 'Soal quiz berhasil dihapus.']);
    }

    // --- Quiz Recommendations ---

    /**
     * POST /api/quiz/recommendations
     */
    public function addQuizRecommendation()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $divisionName = $json['division_name'] ?? null;
        $description  = $json['description'] ?? null;

        if (empty($divisionName) || empty($description)) {
            return $this->errorResponse('division_name dan description wajib diisi.', 400);
        }

        $this->quizRecommendationModel->save([
            'division_name' => $divisionName,
            'description'   => $description
        ]);

        return $this->jsonResponse(['message' => 'Rekomendasi berhasil ditambahkan.'], 201);
    }

    /**
     * PUT /api/quiz/recommendations/(:num)
     */
    public function updateQuizRecommendation($id)
    {
        $item = $this->quizRecommendationModel->find($id);
        if (!$item) {
            return $this->errorResponse('Rekomendasi tidak ditemukan.', 404);
        }

        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $data = ['id' => $id];
        if (isset($json['division_name'])) $data['division_name'] = $json['division_name'];
        if (isset($json['description']))   $data['description']   = $json['description'];

        $this->quizRecommendationModel->save($data);
        return $this->jsonResponse(['message' => 'Rekomendasi berhasil diperbarui.']);
    }

    /**
     * DELETE /api/quiz/recommendations/(:num)
     */
    public function deleteQuizRecommendation($id)
    {
        $item = $this->quizRecommendationModel->find($id);
        if (!$item) {
            return $this->errorResponse('Rekomendasi tidak ditemukan.', 404);
        }

        $this->quizRecommendationModel->delete($id);
        return $this->jsonResponse(['message' => 'Rekomendasi berhasil dihapus.']);
    }
}
