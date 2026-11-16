<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DIGANOVA - Plan It. Join It. Enjoy It.</title>

<!-- Cek Tema dari localStorage SEBELUM render untuk mencegah kedipan (flicker) -->
<script>
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.add('light-mode');
    }
</script>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ================= THEME VARIABLES ================= */
:root {
  --theme-overlay: linear-gradient(to bottom, rgba(10, 25, 50, 0.6), rgba(15, 31, 61, 0.8));
}

html.light-mode {
  --theme-overlay: linear-gradient(to bottom, rgba(255, 255, 255, 0.1), rgba(240, 248, 255, 0.4));
}

/* ================= BASE STYLES ================= */
body { 
  font-family: 'Inter', sans-serif; 
  color: #ffffff; 
  overflow-x: hidden; 
  transition: color 0.5s ease;
  
  background-image: url('{{ asset('images/bg-social.png') }}'); 
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

/* Overlay dinamis */
body::before {
  content: "";
  position: absolute;
  inset: 0;
  background: var(--theme-overlay);
  z-index: -1;
  pointer-events: none;
  transition: background 0.5s ease;
}

/* ================= THEME TEXT COLORS ================= */
/* Default: Dark Mode (Putih) */
.text-theme-main { color: #ffffff; transition: color 0.5s ease; }
.text-theme-sub { color: #ffffff; transition: color 0.5s ease; }
.text-theme-social { color: #ffffff; transition: color 0.5s ease; }

/* Light Mode Overrides (Warna Tema Solid tanpa Glow) */
html.light-mode .text-theme-main { color: #1e3a8a !important; } /* Biru Gelap */
html.light-mode .text-theme-sub { color: #1d4ed8 !important; }  /* Biru Terang */
html.light-mode .text-theme-social { color: #0f172a !important; } /* Slate 900 */

/* ================= 3D BUTTON & ANIMATED GLOW ================= */
.animate-gradient {
  background-size: 200% 200%;
  animation: gradientMove 3s ease infinite;
}
@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.btn-3d {
  box-shadow: 0 6px 0 #1e40af, 0 10px 20px rgba(0,0,0,0.2);
  transition: all 0.15s ease;
  transform: translateY(0);
}
.btn-3d:hover {
  transform: translateY(-3px);
  box-shadow: 0 9px 0 #1e40af, 0 15px 25px rgba(0,0,0,0.3);
}
.btn-3d:active {
  transform: translateY(6px); 
  box-shadow: 0 0px 0 #1e40af, 0 4px 10px rgba(0,0,0,0.2);
}

/* ================= BACKGROUND EFFECTS ================= */
.star {
  position: absolute;
  background-color: #ffffff;
  border-radius: 50%;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.9), 0 0 20px rgba(255, 255, 255, 0.5);
  animation: twinkle 3s infinite alternate;
}
@keyframes twinkle {
  0% { opacity: 0.25; transform: scale(0.8); }
  100% { opacity: 1; transform: scale(1.2); }
}

/* ================= GLOW EFFECTS (Untuk Gambar Saja) ================= */
.logo-glow {
  filter: drop-shadow(0 0 10px rgba(96, 165, 250, 0.6)); 
}
html.light-mode .logo-glow {
  filter: drop-shadow(0 0 6px rgba(29, 78, 216, 0.3));
}

.glow-image {
  filter: drop-shadow(0 24px 32px rgba(30, 58, 95, 0.25)) drop-shadow(0 0 40px rgba(147, 197, 253, 0.5));
}

/* ================= MASCOT ANIMATION ================= */
@keyframes mascotFloat {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
.mascot-float {
  animation: mascotFloat 4s ease-in-out infinite;
}

/* ================= RESPONSIVE TYPOGRAPHY ================= */
.text-huge {
  font-size: clamp(3.5rem, 7vw, 6rem);
  line-height: 1.05;
  font-weight: 900;
  letter-spacing: -0.02em;
}

/* ================= INTERACTIVE LETTER ANIMATION ================= */
.letter {
  display: inline-block;
  cursor: pointer;
  transition: color 0.2s ease, text-shadow 0.2s ease;
}
.letter:hover {
  color: #00E5FF;
}
@keyframes popText {
  0% { transform: translateY(0) scale(1); }
  40% { transform: translateY(-25px) scale(1.3) rotate(5deg); color: #00E5FF; text-shadow: 0 0 30px #00E5FF, 0 0 50px #00E5FF; }
  100% { transform: translateY(0) scale(1) rotate(0deg); }
}
.letter-pop {
  animation: popText 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
  z-index: 50;
}

/* ================= ICONS TOGGLE ================= */
.sun-icon { display: none; }
.moon-icon { display: block; }
html.light-mode .sun-icon { display: block; color: #F59E0B; }
html.light-mode .moon-icon { display: none; }
</style>
</head>

<body class="min-h-screen w-full relative flex flex-col justify-between overflow-hidden">

  <!-- ================= BACKGROUND ELEMENTS ================= -->
  <div class="star w-1 h-1 top-[20%] left-[15%]" style="animation-delay: 0s;"></div>
  <div class="star w-2 h-2 top-[40%] left-[5%]" style="animation-delay: 1s;"></div>
  <div class="star w-1 h-1 top-[60%] right-[20%]" style="animation-delay: 0.5s;"></div>
  <div class="star w-1.5 h-1.5 top-[15%] right-[40%]" style="animation-delay: 1.5s;"></div>


  <!-- ================= HEADER ================= -->
  <header class="relative z-20 w-full max-w-[1400px] mx-auto px-6 md:px-12 py-8 flex justify-between items-center">
    
    <!-- LOGO DIGANOVA -->
    <div class="inline-flex items-center gap-2.5 cursor-pointer group">
      
      <!-- Ikon Logo -->
      <img src="{{ asset('images/logo.png') }}" 
           alt="DIGANOVA Icon" 
           class="h-8 md:h-9 w-auto object-contain logo-glow group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300" />
      
      <!-- Teks di samping logo (TETAP PUTIH) -->
      <div class="flex flex-col leading-none">
        <div class="flex items-baseline text-lg md:text-xl font-black tracking-widest drop-shadow-md">
          <span class="text-white">DIG</span> 
          <span class="text-white">AN</span>
          <span class="text-white">OVA</span>
        </div>
        <span class="text-[7px] md:text-[8px] text-white font-bold tracking-[0.2em] mt-1 drop-shadow-md transition-colors duration-300">PLAN IT. JOIN IT. ENJOY IT.</span>
      </div>
    </div>

    <!-- KANAN: Tombol Tema -->
    <div class="flex items-center gap-4 md:gap-6">
      <!-- ✅ TOMBOL TOGGLE GELAP/TERANG -->
      <button id="theme-toggle" class="p-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-300 hover:scale-110 shadow-lg backdrop-blur-md focus:outline-none" aria-label="Toggle Theme">
        <svg class="w-5 h-5 sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <svg class="w-5 h-5 moon-icon text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
      </button>
    </div>
  </header>


  <!-- ================= MAIN HERO SECTION ================= -->
  <main class="relative z-10 w-full max-w-[1400px] mx-auto flex-grow flex flex-col lg:flex-row items-center px-6 md:px-12 pb-24">
    
    <!-- KIRI: MASKOT -->
    <div class="w-full lg:w-1/2 relative flex justify-center items-center mt-12 lg:mt-0">
      <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[80%] h-[80%] bg-[#93c5fd] rounded-full blur-[120px] opacity-30 pointer-events-none"></div>
      
      <img src="{{ asset('images/navo-mascot.png') }}"
           alt="Maskot Tech Hill City"
           class="relative z-20 w-full max-w-[450px] lg:max-w-[520px] object-contain glow-image mascot-float">
    </div>

    <!-- KANAN: Teks & CTA -->
    <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-end text-center lg:text-right relative z-20 mt-16 lg:mt-0 lg:pr-8">
      
      <div class="mb-10 flex flex-col items-center lg:items-end w-full">
        <!-- DIGANOVA Interaktif (Glow dihapus, hanya drop-shadow tipis) -->
        <h1 class="text-huge text-theme-main uppercase drop-shadow-md flex justify-center lg:justify-end flex-wrap select-none w-full">
          <span class="letter">D</span>
          <span class="letter">I</span>
          <span class="letter">G</span>
          <span class="letter">A</span>
          <span class="letter">N</span>
          <span class="letter">O</span>
          <span class="letter">V</span>
          <span class="letter">A</span>
        </h1>
        
        <!-- Sub-judul -->
        <h2 class="text-xl md:text-3xl font-extrabold text-theme-sub tracking-wide mt-2 drop-shadow-sm">
          Plan It. Join It. Enjoy It.
        </h2>
        
        <!-- SOCIAL -->
        <p class="text-lg md:text-2xl font-black text-theme-social tracking-[0.3em] mt-4 uppercase drop-shadow-sm">
          SOCIAL
        </p>
      </div>

      <!-- Tombol & Badge -->
      <div class="flex items-center justify-center lg:justify-end gap-4 relative mt-4 w-full">
        <!-- Badge Centang -->
        <div class="absolute -left-8 md:-left-16 top-1/2 -translate-y-1/2 z-30 animate-pulse hidden lg:block">
           <svg width="80" height="80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M50 0 L60 10 L75 5 L80 20 L95 25 L90 40 L100 50 L90 60 L95 75 L80 80 L75 95 L60 90 L50 100 L40 90 L25 95 L20 80 L5 75 L10 60 L0 50 L10 40 L5 25 L20 20 L25 5 L40 10 Z" fill="#6ba8e8" stroke="#ffffff" stroke-width="4"/>
              <path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
           </svg>
        </div>

        <!-- ✅ TOMBOL LOGIN 3D + ANIMASI GLOW -->
        <div class="relative group z-20">
          <!-- Animasi Warna di Belakang Tombol -->
          <div class="absolute -inset-1 bg-gradient-to-r from-[#00E5FF] via-[#4a90e2] to-[#93c5fd] rounded-xl blur-md opacity-70 group-hover:opacity-100 animate-gradient transition-opacity duration-300"></div>
          
          <!-- Tombol 3D -->
          <a href="{{ route('login') }}" class="relative bg-gradient-to-r from-[#6ba8e8] to-[#4a90e2] text-white font-bold text-lg px-10 py-4 rounded-xl flex items-center gap-2 border border-white/50 btn-3d">
            Login Sekarang
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </a>
        </div>
      </div>
    </div>
  </main>

  <!-- ================= JAVASCRIPT ================= -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Tema Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                htmlElement.classList.toggle('light-mode');
                if (htmlElement.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                } else {
                    localStorage.setItem('theme', 'dark');
                }
            });
        }

        // 2. Animasi Huruf DIGANOVA
        const letters = document.querySelectorAll('.letter');
        letters.forEach(letter => {
            letter.addEventListener('click', function() {
                this.classList.remove('letter-pop');
                void this.offsetWidth; // Memicu reflow
                this.classList.add('letter-pop');
            });
        });
    });
  </script>

</body>
</html>