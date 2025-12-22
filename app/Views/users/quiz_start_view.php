<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f7f7f7;
    }
    .bg-main-gradient {
        background-image: linear-gradient(
            to bottom, 
            #000B4F 0%, 
            #003399 50%, 
            #0066CC 100% 
        );
        color: white;
    }
    .animation-card {
        background-color: rgba(0, 51, 153, 0.6); 
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px); 
        max-width: 600px;
        padding: 40px;
        border-radius: 12px;
        opacity: 0;
        transform: scale(0.8);
        animation: cardLoad 1.5s ease-out forwards;
    }
    @keyframes cardLoad {
        0% { opacity: 0; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.05); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
<div class="bg-main-gradient min-h-screen text-white flex flex-col items-center justify-center text-center px-4">
    <div class="animation-card w-full">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4 leading-relaxed">
            Selamat Datang di Quiz Kami!
        </h2>
        <p class="text-lg leading-snug">
            Bersiaplah untuk menguji pengetahuan Anda. Quiz akan dimulai dalam beberapa detik.
        </p>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const redirectDelay = 500; 

        setTimeout(() => {
            window.location.href = '<?= base_url('/quiz/isidata') ?>';
            
        }, redirectDelay);
    });
</script>

<?= $this->endSection() ?>