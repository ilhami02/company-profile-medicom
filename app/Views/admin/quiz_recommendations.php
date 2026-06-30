<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<h1 class="text-2xl font-bold mb-6 text-gray-800">Kelola Rekomendasi Divisi</h1>

<div class="bg-white p-6 rounded shadow mb-8 border-t-4 border-blue-600">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Rekomendasi Baru</h2>
    <form action="/admin/addQuizRecommendation" method="post" class="flex flex-col gap-3 bg-gray-50 p-4 rounded">
        <?= csrf_field() ?>
        <input type="text" name="division_name" placeholder="Nama Divisi (Contoh: DIVISI PUBLIKASI)" class="border p-2 rounded w-full" required>
        <textarea name="description" rows="3" placeholder="Deskripsi atau alasan kenapa direkomendasikan" class="border p-2 rounded w-full" required></textarea>
        
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-max mt-2">Tambah Rekomendasi</button>
    </form>
</div>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Rekomendasi</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach($recommendations as $r): ?>
        <div class="bg-white border rounded p-4 relative group hover:shadow-md transition">
            <h3 class="font-bold text-lg text-blue-800 mb-2"><?= $r['division_name'] ?></h3>
            <p class="text-gray-600 text-sm"><?= $r['description'] ?></p>
            
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                <a href="/admin/deleteQuizRecommendation/<?= $r['id'] ?>" onclick="return confirm('Hapus rekomendasi ini?')" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Hapus</a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($recommendations)): ?>
            <p class="text-sm text-gray-400 col-span-full italic">Belum ada rekomendasi divisi.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
