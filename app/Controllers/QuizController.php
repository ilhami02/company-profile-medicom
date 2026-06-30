<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuizQuestionModel;
use App\Models\QuizOptionModel;
use App\Models\QuizRecommendationModel;
use CodeIgniter\HTTP\ResponseInterface;

class QuizController extends BaseController
{
    protected $quizQuestionModel;
    protected $quizOptionModel;
    protected $quizRecommendationModel;

    public function __construct()
    {
        $this->quizQuestionModel       = new QuizQuestionModel();
        $this->quizOptionModel         = new QuizOptionModel();
        $this->quizRecommendationModel = new QuizRecommendationModel();
    }

    public $maxSoal = 5;
    public $currentSoal;

    public $point;

    public function quiz()
    {
        $divisi = [
            "Divisi Fotografi" => "Group_284.png",
            "Divisi Videografi" => "Group_285.png",
            "Divisi Humas" => "Group_290.png",
            "Divisi Desain Grafis" => "Group_287.png",
            "Divisi Video Editing" => "Group_291.png",
            "Divisi Jurnalistik" => "Group_286.png",
            "Divisi Publikasi" => "Group_288.png",
            "Divisi Pemrograman" => "Group_289.png"
        ];
        $displayed_divisi = array_slice($divisi, 0, 7, true);

        return view('users/quiz', [
            'title' => 'Quiz',
            'divisi' => $displayed_divisi
        ]);
    }

    public function startQuiz()
    {
        return view('users/quiz_start_view');
    }

    public function isiDataQuiz()
    {
        return view('users/quiz_isi_data', [
            'title' => 'Isi Data Kuis'
        ]);
    }

    public function saveDataQuiz()
    {
        $namaPeserta = $this->request->getPost('nama_peserta');
        session()->set('quiz_name', $namaPeserta);
        $data = $this->tampilkanPertanyaan(1);
        return view('users/quiz_pertanyaan', $data);
    }

    public function startQuizProcess()
    {

        if (!session()->has('quiz_nama')) {
            return redirect()->to('/quiz/isidata');
        }

        session()->set('quiz_jawaban', []);
        return redirect()->to('/quiz/pertanyaan/1');
    }

    /**
     * GET /quiz/pertanyaan/(:num)
     * Menampilkan pertanyaan secara langsung via GET
     */
    public function showPertanyaan($nomorSoal)
    {
        $data = $this->tampilkanPertanyaan($nomorSoal);

        if (!is_array($data)) {
            return redirect()->to('/quiz');
        }

        return view('users/quiz_pertanyaan', $data);
    }

    public function tampilkanPertanyaan($nomorSoal)
    {
        // Ambil semua pertanyaan dari database, urut berdasarkan sort_order
        $questions = $this->quizQuestionModel->orderBy('sort_order', 'ASC')->findAll();
        $totalSoal = count($questions);

        if ($totalSoal === 0 || $nomorSoal < 1 || $nomorSoal > $totalSoal) {
            return redirect()->to('/quiz');
        }

        // Ambil pertanyaan sesuai nomor (index 0-based)
        $currentQuestion = $questions[$nomorSoal - 1];

        // Ambil opsi untuk pertanyaan ini
        $options = $this->quizOptionModel
            ->where('question_id', $currentQuestion['id'])
            ->findAll();

        $opsi = [];
        foreach ($options as $opt) {
            $opsi[] = $opt['option_text'];
        }

        $jawabanTersimpan = session()->get('quiz_jawaban')[$nomorSoal] ?? null;

        $data = [
            'title'            => 'Soal ' . $nomorSoal,
            'nomorSoal'        => $nomorSoal,
            'totalSoal'        => $totalSoal,
            'soal'             => $currentQuestion['question_text'],
            'opsi'             => $opsi,
            'progressPercent'  => ($nomorSoal / $totalSoal) * 100,
            'jawabanTersimpan' => $jawabanTersimpan
        ];

        return $data;
    }

    public function prosesJawaban($nomorSoal)
    {
        $jawabanTerpilih = $this->request->getPost('jawaban');

        // Ambil total soal dari database
        $totalSoal = $this->quizQuestionModel->countAllResults();
        $this->maxSoal = $totalSoal;

        $this->currentSoal = $nomorSoal;
        $percentage = ($this->currentSoal / $this->maxSoal) * 100;

        if ($this->currentSoal > $this->maxSoal) {
            return redirect()->to('/quiz/hasil');
        }
        $this->currentSoal++;
        $this->point++;
        $data = $this->tampilkanPertanyaan($this->currentSoal);
        session()->set([
            'quiz_jawaban' => $this->point
        ]);


        if (!is_array($data)) {
            return redirect()->to('/quiz/hasil');
        }

        return view('users/quiz_pertanyaan', $data);

    }



    public function hasilQuiz()
    {
        // Ambil rekomendasi dari database
        $recommendations = $this->quizRecommendationModel->findAll();

        if (empty($recommendations)) {
            // Fallback jika database kosong
            $rekomendasiTerpilih = [
                'nama'       => 'DIVISI PEMROGRAMAN',
                'keterangan' => 'Bangun dan rawat website atau sistem.'
            ];
        } else {
            $seed = session()->get('quiz_nama') ?? time();
            srand(crc32($seed));

            $index = array_rand($recommendations);

            $rekomendasiTerpilih = [
                'nama'       => $recommendations[$index]['division_name'],
                'keterangan' => $recommendations[$index]['description']
            ];
        }

        $data = [
            'title'       => 'Hasil Kuis',
            'rekomendasi' => $rekomendasiTerpilih,
            'namaPeserta' => session()->get('quiz_nama')
        ];

        return view('users/quiz_hasil', $data);
    }
}
