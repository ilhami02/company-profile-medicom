<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; }
    .bg-main-gradient {
        background-image: linear-gradient(to bottom, #000B4F, #003399, #0066CC);
        color: white;
    }
    .progress-bar { height: 6px; background: rgba(255,255,255,0.4); border-radius: 3px; }
    .progress-fill { height: 100%; background: white; border-radius: 3px; transition: .5s; }

    .soal-container {
        background: rgba(0, 51, 153, 0.4);
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        padding: 30px; border-radius: 12px; max-width: 800px;
    }

    .opsi-label {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.5);
        padding: 15px 20px; color: white;
        border-radius: 8px; cursor: pointer; display: block;
        transition: .2s;
    }
    .opsi-label:hover { background: rgba(255,255,255,0.2); }

    .opsi-label.selected {
        background: white; color: #003399;
        border-color: white; box-shadow: 0 0 10px white;
    }

    .opsi-input { opacity: 0; position: absolute; }
    
    .btn-lanjut {
        background: white; color: #003399;
        padding: 10px 40px; border-radius: 4px;
        font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        opacity: .5; cursor: not-allowed; margin-top: 30px;
        border: none;
    }

    .btn-lanjut.active { opacity: 1; cursor: pointer; }
    .alert-error { background: #dc2626; padding: 12px; border-radius: 8px; }
</style>

<div class="bg-main-gradient min-h-[75vh] text-white pt-20 pb-40 flex flex-col items-center px-4">

    <div class="container mx-auto max-w-7xl">
        <div class="max-w-4xl mx-auto text-center mb-8">
            <h1 class="text-3xl font-bold">Soal <?= esc($nomorSoal) ?></h1>
            <p class="text-lg mb-4">dari <?= esc($totalSoal) ?></p>

            <div class="progress-bar w-full">
                <div class="progress-fill" style="width: <?= esc($progressPercent) ?>%;"></div>
            </div>
        </div>

        <div class="soal-container mx-auto">

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="text-center mb-10">
                <p class="text-xl font-semibold"><?= esc($soal) ?></p>
            </div>

            <?php 
                // Logika Penentuan Tombol dan Aksi
                $isLastQuestion = ($nomorSoal >= $totalSoal);
                $buttonText = $isLastQuestion ? 'Lihat Hasil →' : 'Lanjut →';
                $formAction = $isLastQuestion ? base_url('quiz/result') : base_url('quiz/jawab/' . $nomorSoal);
            ?>

            <form action="<?= $formAction ?>" method="post" id="quizForm">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <?php
                    foreach ($opsi as $i => $teksOpsi):
                        $isSelected = ($jawabanTersimpan == $teksOpsi);
                    ?>

                    <div class="relative">
                        <input 
                            type="radio" 
                            name="jawaban" 
                            id="opsi_<?= $i ?>" 
                            value="<?= esc($teksOpsi) ?>"
                            class="opsi-input"
                            <?= $isSelected ? 'checked' : '' ?>
                        >

                        <label 
                            for="opsi_<?= $i ?>" 
                            class="opsi-label <?= $isSelected ? 'selected' : '' ?>"
                        >
                            <?= esc($teksOpsi) ?>
                        </label>
                    </div>

                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <button 
                        type="submit" 
                        id="btnLanjut" 
                        class="btn-lanjut <?= $jawabanTersimpan ? 'active' : '' ?>"
                        <?= $jawabanTersimpan ? '' : 'disabled' ?>
                    >
                        <?= $buttonText ?>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const labels = document.querySelectorAll('.opsi-label');
    const inputs = document.querySelectorAll('.opsi-input');
    const btn = document.getElementById('btnLanjut');
    const form = document.getElementById('quizForm');
    
    const isLastQuestion = <?= $isLastQuestion ? 'true' : 'false' ?>;
    const resultUrl = '<?= base_url('quiz/result') ?>';

    function update() {
        labels.forEach(l => l.classList.remove('selected'));

        const selected = document.querySelector('input[name="jawaban"]:checked');
        if (!selected) {
            btn.disabled = true;
            btn.classList.remove('active');
            return;
        }

        document.querySelector(`label[for="${selected.id}"]`).classList.add('selected');
        btn.disabled = false;
        btn.classList.add('active');

        if (isLastQuestion) {
            form.action = resultUrl;
        }
    }

    if (isLastQuestion) {
        btn.innerHTML = 'Lihat Hasil →';
    } else {
        btn.innerHTML = 'Lanjut →';
    }

    labels.forEach(l => l.addEventListener('click', update));
    inputs.forEach(i => i.addEventListener('change', update));
    update();
});
</script>

<?= $this->endSection() ?>
