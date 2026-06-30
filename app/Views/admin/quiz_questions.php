<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-6 text-gray-800">Kelola Pertanyaan Quiz</h1>

<div class="bg-white p-6 rounded shadow mb-8 border-t-4 border-blue-600">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Pertanyaan Baru</h2>
    <form action="/admin/addQuizQuestion" method="post" class="flex flex-col gap-3 bg-gray-50 p-4 rounded">
        <?= csrf_field() ?>
        <input type="text" name="question_text" placeholder="Teks Pertanyaan" class="border p-2 rounded w-full" required>
        <input type="number" name="sort_order" placeholder="Urutan (Contoh: 1)" class="border p-2 rounded w-full md:w-1/4" required>
        
        <h3 class="font-bold text-gray-700 mt-2">Opsi Jawaban (pisahkan dengan baris baru):</h3>
        <textarea name="options" rows="4" placeholder="Opsi A&#10;Opsi B&#10;Opsi C&#10;Opsi D" class="border p-2 rounded w-full" required></textarea>
        
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-max mt-2">Tambah Pertanyaan</button>
    </form>
</div>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Pertanyaan</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="border-b p-3 bg-gray-50 font-bold">No</th>
                    <th class="border-b p-3 bg-gray-50 font-bold">Urutan</th>
                    <th class="border-b p-3 bg-gray-50 font-bold">Pertanyaan</th>
                    <th class="border-b p-3 bg-gray-50 font-bold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($questions as $q): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border-b p-3"><?= $no++ ?></td>
                    <td class="border-b p-3"><?= $q['sort_order'] ?></td>
                    <td class="border-b p-3"><?= $q['question_text'] ?></td>
                    <td class="border-b p-3">
                        <a href="/admin/deleteQuizQuestion/<?= $q['id'] ?>" onclick="return confirm('Hapus pertanyaan ini?')" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($questions)): ?>
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500 italic border-b">Belum ada pertanyaan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
