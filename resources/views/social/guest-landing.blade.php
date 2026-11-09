<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<script>document.documentElement.classList.add('js');</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DIGANOVA — Plan It. Join It. Enjoy It.</title>
<meta name="description" content="DIGANOVA — platform sosial, event, dan gaming untuk komunitas Indonesia. Plan It. Join It. Enjoy It.">
<meta property="og:title" content="DIGANOVA — Plan It. Join It. Enjoy It.">
<meta property="og:description" content="Bergabung dengan ribuan kreator, gamer, dan komunitas di seluruh Indonesia.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:image" content="{{ asset('images/og-diganova.jpg') }}">
<meta name="twitter:card" content="summary_large_image">

@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ============================================================
   1. TOKENS — dark navy theme
   ============================================================ */
:root {
  --bg-0:   #060D1F;   /* deepest */
  --bg-1:   #0A1628;
  --bg-2:   #0F1F3D;   /* card base */
  --bg-3:   #152D54;   /* elevated */
  --blue:   #3B82F6;
  --blue-l: #60A5FA;
  --blue-d: #1E3A8A;
  --sky:    #93C5FD;
  --cyan:   #22D3EE;
  --mint:   #5EEAD4;
  --sun:    #FACC15;
  --text:   #E2E8F0;
  --text-dim: #94A3B8;
  --line:   #1E3A8A;
  --ease:   cubic-bezier(.34, 1.56, .64, 1);
}

/* ============================================================
   2. BASE
   ============================================================ */
html { color-scheme: dark; }
body {
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
  background: var(--bg-0);
  color: var(--text);
}
.f-display {
  font-family: 'Fredoka', 'Plus Jakarta Sans', sans-serif;
  letter-spacing: -.01em;
}

/* ============================================================
   3. KEYFRAMES
   ============================================================ */
@keyframes bob       { 50% { transform: translateY(-14px) rotate(8deg); } }
@keyframes pop       { from { opacity: 0; transform: translateY(26px) scale(.94); } to { opacity: 1; transform: none; } }
@keyframes floaty    { 50% { transform: translateY(-12px) rotate(var(--r, 0deg)); } }
@keyframes blob      { 50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; } }
@keyframes marquee   { to { transform: translateX(-50%); } }
@keyframes wob       { 25% { transform: rotate(-6deg) scale(1.08); } 75% { transform: rotate(6deg) scale(1.08); } }
@keyframes drift     { 50% { transform: translate(30px, -20px) rotate(180deg); } }
@keyframes pulseGlow { 50% { opacity: .35; transform: scale(1.08); } }
@keyframes spin-slow { to { transform: rotate(360deg); } }
@keyframes wave      { 50% { transform: translateY(-8px) rotate(-3deg); } }

/* ============================================================
   4. ANIMATION UTILITIES
   ============================================================ */
.floaty      { animation: floaty      var(--dur, 8s)  ease-in-out infinite var(--delay, 0s); }
.blobby      { animation: blob        var(--dur, 14s) ease-in-out infinite var(--delay, 0s); }
.drift       { animation: drift       var(--dur, 18s) ease-in-out infinite var(--delay, 0s); }
.pulse-glow  { animation: pulseGlow   var(--dur, 6s)  ease-in-out infinite var(--delay, 0s); }
.spin-slow   { animation: spin-slow   var(--dur, 40s) linear     infinite; }
.wave        { animation: wave        var(--dur, 10s) ease-in-out infinite var(--delay, 0s); }

/* ============================================================
   5. TILT UTILITIES
   ============================================================ */
.tilt-l1 { --hr: -1deg; transform: rotate(-1deg); }
.tilt-l2 { --hr: -2deg; transform: rotate(-2deg); }
.tilt-l3 { --hr: -3deg; transform: rotate(-3deg); }
.tilt-l4 { --hr: -4deg; transform: rotate(-4deg); }
.tilt-r2 { --hr:  2deg; transform: rotate( 2deg); }
.tilt-r3 { --hr:  3deg; transform: rotate( 3deg); }
.tilt-r7 { --hr:  4deg; transform: rotate( 7deg); }

/* ============================================================
   6. PAGE TRANSITION — dark reveal
   ============================================================ */
.pt {
  position: fixed; inset: 0; z-index: 9999;
  pointer-events: none; display: grid; place-items: center;
  background: radial-gradient(circle at 30% 20%, #3B82F6, #1E3A8A 55%, #060D1F);
  clip-path: circle(0% at var(--x, 50%) var(--y, 50%));
  transition: clip-path .65s cubic-bezier(.7, 0, .2, 1);
}
.pt.in      { clip-path: circle(150% at var(--x, 50%) var(--y, 50%)); pointer-events: all; }
.js .pt     { clip-path: circle(150% at 50% 50%); }
.js .pt.out { clip-path: circle(0% at 50% 50%); }
.pt-logo {
  width: 84px; height: 84px; border-radius: 28px;
  background: #0F1F3D; display: grid; place-items: center;
  border: 3px solid #60A5FA;
  box-shadow: 0 0 40px rgba(96,165,250,.6);
  animation: bob .9s ease-in-out infinite;
  transition: opacity .2s;
}
.pt.out .pt-logo { opacity: 0; }

/* ============================================================
   7. ENTRANCE
   ============================================================ */
.js .enter { opacity: 0; }
.js body.ready .enter {
  animation: pop .7s var(--ease) forwards;
  animation-delay: calc(var(--i, 0) * 90ms + .35s);
}

/* ============================================================
   8. NAV
   ============================================================ */
.nav-chip {
  position: relative; z-index: 1;
  padding: .5rem 1.1rem; border-radius: 999px;
  font-weight: 700; font-size: .875rem; color: #94A3B8;
  transition: color .25s, transform .35s var(--ease);
}
.nav-chip:hover { color: #fff; transform: translateY(-3px) rotate(-2deg); }
.nav-bubble {
  position: absolute; top: 50%; height: calc(100% - 8px);
  border-radius: 999px;
  background: linear-gradient(135deg, #3B82F6, #60A5FA);
  transform: translateY(-50%); opacity: 0;
  transition: left .45s var(--ease), width .45s var(--ease), opacity .2s;
  box-shadow: 0 8px 18px -6px rgba(96, 165, 250, .7);
}
.logo-tile           { transition: transform .5s var(--ease); }
a:hover > .logo-tile { transform: rotate(-12deg) scale(1.1); }

/* ============================================================
   9. HERO TEXT
   ============================================================ */
.w { display: inline-block; transition: transform .45s var(--ease); }
.w:hover { transform: translateY(-8px) rotate(-3deg) scale(1.06); }
.sticker {
  display: inline-block; background: #F8FAFC;
  border: 3px solid #0A1628; border-radius: 22px;
  padding: 0 .35em; transform: rotate(-3deg);
  box-shadow: 6px 6px 0 #0A1628, 0 0 40px rgba(96, 165, 250, .35);
  transition: transform .4s var(--ease), box-shadow .4s;
  color: #2563EB;
}
.sticker:hover {
  transform: rotate(2deg) translate(-3px, -3px);
  box-shadow: 10px 10px 0 #0A1628, 0 0 60px rgba(96, 165, 250, .55);
}
.hilite {
  background: linear-gradient(transparent 58%, var(--sun) 58% 92%, transparent 92%);
  background-size: 100% 100%;
  color: #fff;
}

/* ============================================================
   10. SHAPES
   ============================================================ */
.sh-blob   { border-radius: 62% 38% 46% 54% / 54% 46% 62% 38%; }
.sh-bubble { border-radius: 48% 52% 42% 58% / 60% 40% 60% 40%; }
.sh-leaf   { border-radius: 4rem .75rem 4rem .75rem; }
.sh-flag   { border-radius: 2rem 2rem 2rem 0; }
.sh-oct    { clip-path: polygon(14% 0, 86% 0, 100% 14%, 100% 86%, 86% 100%, 14% 100%, 0 86%, 0 14%); }
.sh-star   { clip-path: polygon(50% 0, 61% 15%, 79% 10%, 82% 29%, 100% 35%, 90% 52%, 100% 68%, 82% 74%, 79% 92%, 61% 87%, 50% 100%, 39% 87%, 21% 92%, 18% 74%, 0 68%, 10% 52%, 0 35%, 18% 29%, 21% 10%, 39% 15%); }
.sh-tri    { clip-path: polygon(50% 0, 100% 100%, 0 100%); }
.sh-diamond{ clip-path: polygon(50% 0, 100% 50%, 50% 100%, 0 50%); }
.sh-ticket {
  border-radius: 1.75rem;
  -webkit-mask:
    radial-gradient(circle 14px at 0 50%,    #0000 98%, #000) left  / 51% 100% no-repeat,
    radial-gradient(circle 14px at 100% 50%, #0000 98%, #000) right / 51% 100% no-repeat;
  mask:
    radial-gradient(circle 14px at 0 50%,    #0000 98%, #000) left  / 51% 100% no-repeat,
    radial-gradient(circle 14px at 100% 50%, #0000 98%, #000) right / 51% 100% no-repeat;
}

/* ============================================================
   11. CARDS & INTERACTIVE
   ============================================================ */
.card {
  transition: transform .5s var(--ease), box-shadow .4s, border-color .3s;
  cursor: pointer; will-change: transform;
}
.card:hover,
.card:focus-visible {
  transform: translateY(-10px) rotate(var(--hr, -2deg)) scale(1.03);
  box-shadow:
    0 0 0 1px rgba(96, 165, 250, .35),
    0 24px 40px -14px rgba(0, 0, 0, .6),
    0 0 40px rgba(59, 130, 246, .35);
  outline: none; z-index: 20;
}
.card:focus-visible { outline: 3px solid var(--blue-l); outline-offset: 5px; }
.card:active { transform: scale(.97); }
.card .ico       { transition: transform .6s var(--ease); }
.card:hover .ico { transform: rotate(360deg) scale(1.15); }
.card .go        { transition: transform .35s var(--ease); }
.card:hover .go  { transform: translateX(8px); }

.hov-pop       { transition: transform .4s var(--ease); }
.hov-pop:hover { transform: translateY(-5px) scale(1.08) rotate(-2deg); }
.btn           { transition: transform .35s var(--ease), box-shadow .3s, background .3s; }
.btn:hover     { transform: translateY(-4px) scale(1.04); }
.btn:active    { transform: scale(.96); }
.stat          { transition: transform .45s var(--ease); }
.stat:hover    { transform: translateY(-8px) rotate(3deg); }
.stat:hover b  { animation: wob .6s; }

/* ============================================================
   11b. ✨ TITLE POP — efek "TIMBUL" + NEON GLOW saat klik ✨
   ============================================================ */
.title-pop {
  cursor: pointer; user-select: none; position: relative;
  transition: transform .3s var(--ease), filter .3s;
  will-change: transform, text-shadow;
  outline: none;
}
.title-pop:focus-visible {
  outline: 3px dashed var(--blue-l);
  outline-offset: 10px;
  border-radius: 8px;
}
.title-pop:hover {
  filter: drop-shadow(0 0 12px rgba(96, 165, 250, .5));
}
.title-pop.popping {
  animation: titleBounce .72s cubic-bezier(.34, 1.56, .64, 1);
  text-shadow:
    /* lapisan emboss biru gelap = efek TERANGKAT */
    2px 2px 0 #1D4ED8,
    4px 4px 0 #1E3A8A,
    6px 6px 0 #152D54,
    8px 8px 0 #0F1F3D,
    /* halo neon = efek MENYALA */
    0 0 18px rgba(96, 165, 250, .85),
    0 0 36px rgba(59, 130, 246, .65),
    0 0 60px rgba(37, 99, 235, .45);
}
.title-pop--light.popping {
  text-shadow:
    2px 2px 0 rgba(30, 58, 138, .7),
    4px 4px 0 rgba(30, 58, 138, .45),
    6px 6px 0 rgba(0, 0, 0, .3),
    0 0 18px rgba(250, 204, 21, .95),
    0 0 40px rgba(250, 204, 21, .7),
    0 0 70px rgba(250, 204, 21, .4);
}
@keyframes titleBounce {
  0%   { transform: scale(1) rotate(0); }
  22%  { transform: scale(1.15) rotate(-3deg); }
  48%  { transform: scale(1.06) rotate( 2deg); }
  72%  { transform: scale(1.10) rotate(-1deg); }
  100% { transform: scale(1) rotate(0); }
}
.sparkle {
  position: fixed;
  width: 12px; height: 12px;
  border-radius: 50%;
  pointer-events: none;
  z-index: 8500;
  transform: translate(-50%, -50%);
  animation: sparkleOut .9s cubic-bezier(.2, .7, .3, 1) forwards;
}
@keyframes sparkleOut {
  0%   { transform: translate(-50%,-50%) translate(0,0) rotate(0) scale(1); opacity: 1; }
  70%  { opacity: .9; }
  100% { transform: translate(-50%,-50%) translate(var(--tx), var(--ty)) rotate(var(--r)) scale(0); opacity: 0; }
}

/* ============================================================
   12. DECORATION
   ============================================================ */
.dots {
  background-image: radial-gradient(rgba(96, 165, 250, .35) 1.2px, transparent 1.2px);
  background-size: 22px 22px;
}
.grid-lines {
  background-image:
    linear-gradient(rgba(59, 130, 246, .10) 1px, transparent 1px),
    linear-gradient(90deg, rgba(59, 130, 246, .10) 1px, transparent 1px);
  background-size: 64px 64px;
  -webkit-mask-image: radial-gradient(ellipse at center, #000 25%, transparent 70%);
  mask-image:         radial-gradient(ellipse at center, #000 25%, transparent 70%);
}
.marquee-mask {
  -webkit-mask-image: linear-gradient(90deg, #0000, #000 6%, #000 94%, #0000);
  mask-image:         linear-gradient(90deg, #0000, #000 6%, #000 94%, #0000);
}
.noise {
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
  mix-blend-mode: overlay;
}

/* ============================================================
   13. MODAL — dark
   ============================================================ */
.modal {
  position: fixed; inset: 0; z-index: 9000;
  display: grid; place-items: center; padding: 1rem;
  opacity: 0; pointer-events: none; transition: opacity .3s;
}
.modal.active { opacity: 1; pointer-events: all; }
.modal-bg {
  position: absolute; inset: 0;
  background: rgba(3, 8, 20, .78);
  backdrop-filter: blur(12px);
}
.modal-box {
  position: relative; width: 100%; max-width: 30rem;
  transform: scale(.7) rotate(-4deg); opacity: 0;
  transition: transform .55s var(--ease), opacity .25s;
}
.modal.active .modal-box { transform: none; opacity: 1; }
.modal li { opacity: 0; transform: translateX(-16px); }
.modal.active li {
  animation: pop .5s var(--ease) forwards;
  animation-delay: calc(var(--n) * 80ms + .25s);
}

/* ============================================================
   14. UTILITIES
   ============================================================ */
::-webkit-scrollbar       { width: 10px; }
::-webkit-scrollbar-track { background: var(--bg-1); }
::-webkit-scrollbar-thumb { background: linear-gradient(var(--blue), var(--blue-d)); border-radius: 6px; }
.sr-only {
  position: absolute; width: 1px; height: 1px;
  overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap;
}
.sr-only:focus {
  position: fixed; top: 1rem; left: 1rem;
  width: auto; height: auto; clip: auto; z-index: 10000;
  background: #0F1F3D; color: #fff; padding: .5rem 1rem;
  border-radius: .75rem; font-weight: 700;
  border: 2px solid var(--blue);
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: .01ms !important;
    scroll-behavior: auto !important;
  }
  .pt { display: none; }
  .js .enter { opacity: 1; }
}
</style>
<noscript><style>.pt{display:none!important}.enter,.modal li{opacity:1!important}</style></noscript>
</head>

<body class="overflow-x-hidden selection:bg-[#60A5FA]/40 antialiased">
<a href="#main" class="sr-only">Lewati ke konten utama</a>

<!-- SVG SPRITE -->
<svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
  <defs>
    <symbol id="i-bolt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></symbol>
    <symbol id="i-ticket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></symbol>
    <symbol id="i-circle-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></symbol>
    <symbol id="i-pencil" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></symbol>
    <symbol id="i-badge" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></symbol>
    <symbol id="i-gamepad" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10a4 4 0 014 4v2a4 4 0 01-4 4c-1.5 0-2-1-3-2h-4c-1 1-1.5 2-3 2a4 4 0 01-4-4v-2a4 4 0 014-4zm1 3v4m-2-2h4m6.5-1h.01M18 12h.01"/></symbol>
    <symbol id="i-monitor" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></symbol>
    <symbol id="i-twitter" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></symbol>
    <symbol id="i-instagram" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></symbol>
    <symbol id="i-linkedin" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></symbol>
  </defs>
</svg>

@php
/* Blob besar — sekarang gelap dengan glow biru/cyan */
$bgBlobs = [
  ['cls' => '-top-40 -left-32 w-[40rem] h-[40rem] from-[#1E3A8A] via-[#3B82F6] to-[#60A5FA] opacity-[0.18] sh-blob',   'dur' => '16s', 'delay' => '0s'],
  ['cls' => 'top-1/4 -right-40 w-[34rem] h-[34rem] from-[#1E3A8A] via-[#2563EB] to-[#3B82F6] opacity-[0.16] sh-blob',  'dur' => '18s', 'delay' => '3s'],
  ['cls' => '-bottom-48 left-1/4 w-[32rem] h-[32rem] from-[#3B82F6] via-[#2563EB] to-[#1E3A8A] opacity-[0.14] sh-bubble','dur' => '20s', 'delay' => '6s'],
  ['cls' => 'top-1/2 left-1/3 w-[24rem] h-[24rem] from-[#22D3EE] via-[#3B82F6] to-[#1E3A8A] opacity-[0.12] sh-blob',     'dur' => '22s', 'delay' => '9s'],
];

/* Bentuk playful kecil — warna cerah pop di dark */
$bgShapes = [
  ['cls' => 'top-[12%]  left-[5%]   w-16 h-16 sh-star bg-[#FACC15] opacity-40',                                    'dur' => '9s',  'delay' => '0s',   'anim' => 'floaty'],
  ['cls' => 'top-[38%]  right-[7%]  w-12 h-12 rounded-full bg-gradient-to-br from-[#60A5FA] to-[#3B82F6] opacity-40','dur' => '11s', 'delay' => '2s',   'anim' => 'floaty'],
  ['cls' => 'bottom-[18%] left-[10%] w-10 h-10 sh-oct bg-[#3B82F6] opacity-40',                                    'dur' => '10s', 'delay' => '4s',   'anim' => 'floaty'],
  ['cls' => 'top-[58%]  right-[14%] w-14 h-14 sh-bubble bg-gradient-to-br from-[#FACC15] to-[#FDE68A] opacity-40', 'dur' => '13s', 'delay' => '1s',   'anim' => 'floaty'],
  ['cls' => 'top-[8%]   right-[26%] w-8 h-8  rounded-full bg-[#FACC15] opacity-55',                                 'dur' => '7s',  'delay' => '3s',   'anim' => 'floaty'],
  ['cls' => 'bottom-[8%] right-[5%]  w-20 h-20 sh-star bg-[#60A5FA] opacity-25',                                   'dur' => '14s', 'delay' => '5s',   'anim' => 'floaty'],
  ['cls' => 'top-[28%]  left-[2%]   w-9 h-9  rounded-full bg-[#60A5FA] opacity-45',                                'dur' => '12s', 'delay' => '2.5s', 'anim' => 'floaty'],
  ['cls' => 'top-[76%]  left-[35%]  w-6 h-6  sh-oct bg-[#FACC15] opacity-45',                                       'dur' => '10s', 'delay' => '6s',   'anim' => 'floaty'],
  ['cls' => 'top-[20%]  left-[22%]  w-10 h-10 sh-diamond bg-gradient-to-br from-[#22D3EE] to-[#60A5FA] opacity-30', 'dur' => '13s', 'delay' => '4.5s', 'anim' => 'wave'],
  ['cls' => 'top-[48%]  left-[46%]  w-7 h-7  sh-tri bg-[#93C5FD] opacity-35',                                       'dur' => '15s', 'delay' => '7s',   'anim' => 'wave'],
  ['cls' => 'bottom-[35%] right-[22%] w-12 h-12 rounded-full border-[3px] border-[#60A5FA] opacity-50',              'dur' => '11s', 'delay' => '1.5s', 'anim' => 'floaty'],
  ['cls' => 'top-[85%]  right-[38%] w-9 h-9  sh-diamond bg-[#FACC15] opacity-35',                                   'dur' => '16s', 'delay' => '3.5s', 'anim' => 'drift'],
];

$marquee = [
  ['icon' => 'i-bolt',    'label' => 'Komunitas Kreator Indonesia'],
  ['icon' => 'i-gamepad', 'label' => 'Gaming Squad Nusantara'],
  ['icon' => 'i-monitor', 'label' => 'Tech Enthusiast ID'],
  ['icon' => 'i-ticket',  'label' => 'Festival Musik 2025'],
];

/* Tiga dunia — kartu gelap bertema */
$dunia = [
  ['key' => 'sosial', 'title' => 'Sosial Media',
   'desc' => 'Bagikan momen, bangun followers lewat feed yang hidup.',
   'icon' => 'i-chat', 'iconSize' => 'w-10 h-10',
   'shape' => 'sh-bubble', 'iconShape' => 'sh-blob',
   'bg' => 'bg-[#0F1F3D]', 'border' => 'border-[#60A5FA]',
   'shadow' => 'shadow-[0_0_40px_-10px_rgba(96,165,250,0.5)]',
   'iconBg' => 'from-[#1D4ED8] to-[#60A5FA]',
   'titleColor' => 'text-white', 'descColor' => 'text-[#94A3B8]', 'linkColor' => 'text-[#60A5FA]',
   'offset' => 'md:mt-0', 'tilt' => 'tilt-l2', 'pad' => 'p-9 pt-12'],
  ['key' => 'event', 'title' => 'Event & Festival',
   'desc' => 'Cari event, beli tiket digital, ketemu teman baru.',
   'icon' => 'i-calendar', 'iconSize' => 'w-9 h-9',
   'shape' => 'sh-ticket', 'iconShape' => 'sh-star',
   'bg' => 'bg-[#1A1208]', 'border' => 'border-[#FBBF24]',
   'shadow' => 'shadow-[0_0_40px_-10px_rgba(251,191,36,0.5)]',
   'iconBg' => 'from-[#F59E0B] to-[#FBBF24]',
   'titleColor' => 'text-[#FBBF24]', 'descColor' => 'text-[#FCD34D]/80', 'linkColor' => 'text-[#FBBF24]',
   'offset' => 'md:mt-14', 'tilt' => 'tilt-r2', 'pad' => 'px-10 py-12'],
  ['key' => 'game', 'title' => 'Game Arena',
   'desc' => 'Turnamen dan leaderboard untuk tunjukkan skill-mu.',
   'icon' => 'i-circle-play', 'iconSize' => 'w-9 h-9',
   'shape' => 'sh-leaf', 'iconShape' => 'sh-oct',
   'bg' => 'bg-[#0A1A14]', 'border' => 'border-[#34D399]',
   'shadow' => 'shadow-[0_0_40px_-10px_rgba(52,211,153,0.5)]',
   'iconBg' => 'from-[#10B981] to-[#34D399]',
   'titleColor' => 'text-[#5EEAD4]', 'descColor' => 'text-[#94A3B8]', 'linkColor' => 'text-[#34D399]',
   'offset' => 'md:mt-28', 'tilt' => 'tilt-l2', 'pad' => 'p-9 pt-12'],
];

$fitur = [
  ['key'=>'sosial','title'=>'Postingan Kreatif','desc'=>'Teks, gambar, hashtag, sampai 500 karakter.','icon'=>'i-pencil',
   'bg'=>'bg-[#2563EB] text-white','shape'=>'sh-flag','border'=>'border-2 border-[#60A5FA]/40','tilt'=>'tilt-l2','icoBg'=>'bg-white/20','iconColor'=>''],
  ['key'=>'sosial','title'=>'Interaksi Instan','desc'=>'Like, komentar, repost tanpa jeda.','icon'=>'i-heart',
   'bg'=>'bg-[#152D54]','shape'=>'sh-bubble','border'=>'border-[3px] border-[#3B82F6]/50','tilt'=>'tilt-r2','icoBg'=>'bg-[#3B82F6]/20','iconColor'=>'text-[#60A5FA]'],
  ['key'=>'dm','title'=>'Direct Message','desc'=>'Chat pribadi, terenkripsi, penuh sticker.','icon'=>'i-mail',
   'bg'=>'bg-[#0F1F3D]','shape'=>'sh-leaf','border'=>'border-[3px] border-[#3B82F6] shadow-[5px_5px_0_#1E3A8A]','tilt'=>'tilt-l1','icoBg'=>'bg-[#3B82F6]/20','iconColor'=>'text-[#60A5FA]'],
  ['key'=>'sosial','title'=>'Komunitas & Grup','desc'=>'Cari circle yang sefrekuensi.','icon'=>'i-users',
   'bg'=>'bg-[#1E3A8A]/50','shape'=>'sh-flag','border'=>'border-2 border-[#3B82F6]/40','tilt'=>'tilt-r3','icoBg'=>'bg-[#3B82F6]/25','iconColor'=>'text-[#93C5FD]'],
  ['key'=>'game','title'=>'Badge & Achievement','desc'=>'Kumpulkan badge, bangun reputasi.','icon'=>'i-badge',
   'bg'=>'bg-[#FACC15] text-[#1E3A8A]','shape'=>'sh-bubble','border'=>'border-2 border-[#FDE68A]/60','tilt'=>'tilt-l3','icoBg'=>'bg-[#1E3A8A]/15','iconColor'=>''],
];

$stats = [
  ['value'=>'12.5K+','label'=>'Pengguna aktif','shape'=>'sh-blob',  'bg'=>'bg-[#0F1F3D]',   'border'=>'border-[3px] border-[#60A5FA]','text'=>'text-white','valueColor'=>'text-[#60A5FA]','labelColor'=>'text-[#94A3B8]','pad'=>'p-4'],
  ['value'=>'320+',  'label'=>'Event',         'shape'=>'sh-star',  'bg'=>'bg-[#FACC15]','border'=>'',                           'text'=>'text-[#1E3A8A]','valueColor'=>'',             'labelColor'=>'',            'pad'=>'p-6'],
  ['value'=>'150+',  'label'=>'Grup komunitas','shape'=>'sh-bubble','bg'=>'bg-[#2563EB]','border'=>'',                           'text'=>'text-white',  'valueColor'=>'',             'labelColor'=>'',            'pad'=>'p-4'],
  ['value'=>'98%',   'label'=>'Kepuasan user', 'shape'=>'sh-oct',   'bg'=>'bg-[#0A1A14]', 'border'=>'border-[3px] border-[#34D399]','text'=>'text-[#5EEAD4]','valueColor'=>'',            'labelColor'=>'',            'pad'=>'p-6'],
];

$socials = [
  ['id' => 'i-twitter',   'label' => 'Twitter'],
  ['id' => 'i-instagram', 'label' => 'Instagram'],
  ['id' => 'i-linkedin',  'label' => 'LinkedIn'],
];
@endphp

<div class="pt out" id="pt" aria-hidden="true">
  <div class="pt-logo">
    <svg class="w-10 h-10 text-[#60A5FA]"><use href="#i-bolt"/></svg>
  </div>
</div>

<!-- ============================================================
     BACKGROUND — dark navy dengan glow
     ============================================================ -->
<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">

  {{-- Base: dark navy gradient --}}
  <div class="absolute inset-0 bg-[linear-gradient(180deg,#060D1F_0%,#0A1628_25%,#0F1F3D_55%,#0A1628_85%,#060D1F_100%)]"></div>

  {{-- Radial glows --}}
  <div class="absolute top-[-15%] left-1/2 -translate-x-1/2 w-[90rem] h-[55rem] rounded-full bg-[radial-gradient(circle,rgba(59,130,246,0.22)_0%,transparent_65%)] opacity-90"></div>
  <div class="absolute top-[25%] -right-[25%] w-[50rem] h-[50rem] rounded-full bg-[radial-gradient(circle,rgba(30,58,138,0.35)_0%,transparent_70%)] opacity-70"></div>
  <div class="absolute bottom-[-20%] -left-[20%] w-[55rem] h-[55rem] rounded-full bg-[radial-gradient(circle,rgba(34,211,238,0.12)_0%,transparent_70%)] opacity-60"></div>
  <div class="absolute top-[55%] left-[20%] w-[35rem] h-[35rem] rounded-full bg-[radial-gradient(circle,rgba(96,165,250,0.14)_0%,transparent_70%)] opacity-50"></div>

  {{-- Grid + dots --}}
  <div class="absolute inset-0 opacity-60 grid-lines"></div>
  <div class="absolute inset-0 dots opacity-40"></div>
  <div class="absolute inset-0 noise opacity-[0.12]"></div>

  {{-- Blobs --}}
  @foreach ($bgBlobs as $b)
    <div class="absolute blur-[120px] bg-gradient-to-br {{ $b['cls'] }} blobby"
         style="--dur:{{ $b['dur'] }};--delay:{{ $b['delay'] }}"></div>
  @endforeach

  {{-- Playful shapes --}}
  @foreach ($bgShapes as $s)
    <div class="absolute {{ $s['cls'] }} {{ $s['anim'] }}"
         style="--dur:{{ $s['dur'] }};--delay:{{ $s['delay'] }}"></div>
  @endforeach

  {{-- Vignette --}}
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_55%,rgba(3,8,20,0.6)_100%)]"></div>
</div>

<!-- ============================================================
     HEADER
     ============================================================ -->
<header class="fixed top-3 inset-x-0 z-50 px-3 pointer-events-none">
  <nav class="pointer-events-auto max-w-6xl mx-auto flex items-center justify-between gap-3" aria-label="Navigasi utama">

    <a href="{{ url('/') }}"
       class="flex items-center gap-3 bg-[#0F1F3D] border-[3px] border-[#3B82F6] rounded-[1.75rem] rounded-bl-md pl-2 pr-5 py-2 shadow-[4px_4px_0_#060D1F] hov-pop shrink-0">
      <span class="logo-tile relative w-11 h-11 rounded-2xl bg-gradient-to-br from-[#2563EB] to-[#60A5FA] grid place-items-center">
        <svg class="w-6 h-6 text-white"><use href="#i-bolt"/></svg>
        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-[#FACC15] border-2 border-[#0F1F3D]"></span>
      </span>
      <span class="leading-none">
        <span class="f-display text-xl font-bold text-white block">DIGANOVA</span>
        <span class="text-[10px] font-bold text-[#60A5FA] block mt-1">Plan It. Join It. Enjoy It.</span>
      </span>
    </a>

    <div class="hidden md:block relative bg-[#0F1F3D]/90 backdrop-blur border-[3px] border-[#1E3A8A] rounded-full p-1 shadow-lg shadow-[#3B82F6]/20" id="navTrack">
      <span class="nav-bubble" id="navBubble"></span>
      <a href="#dunia"     class="nav-chip">Tiga Dunia</a>
      <a href="#fitur"     class="nav-chip">Fitur</a>
      <a href="#statistik" class="nav-chip">Statistik</a>
    </div>

    <div class="flex items-center gap-2 shrink-0">
      <a href="{{ route('login') }}"
         class="btn hidden sm:inline-flex bg-[#0F1F3D] border-[3px] border-[#1E3A8A] text-white font-bold text-sm px-5 py-2.5 rounded-full hover:border-[#60A5FA]">Masuk</a>
      <a href="{{ route('register') }}"
         class="btn inline-flex items-center gap-1.5 bg-[#FACC15] border-[3px] border-[#1E3A8A] text-[#1E3A8A] font-extrabold text-sm px-5 py-2.5 rounded-full shadow-[3px_3px_0_#060D1F]">
        Gabung
        <svg class="w-4 h-4"><use href="#i-arrow-r"/></svg>
      </a>
    </div>
  </nav>
</header>

<!-- Mobile dock -->
<nav class="md:hidden fixed bottom-3 inset-x-3 z-50 bg-[#0F1F3D]/95 backdrop-blur border-[3px] border-[#3B82F6] rounded-full shadow-[4px_4px_0_#060D1F] flex justify-around py-2 text-xs font-bold text-white" aria-label="Navigasi cepat">
  <a href="#dunia"       class="hov-pop px-4 py-1.5 rounded-full hover:bg-[#1E3A8A]/50">Dunia</a>
  <a href="#fitur"       class="hov-pop px-4 py-1.5 rounded-full hover:bg-[#1E3A8A]/50">Fitur</a>
  <a href="#statistik"   class="hov-pop px-4 py-1.5 rounded-full hover:bg-[#1E3A8A]/50">Statistik</a>
  <a href="{{ route('login') }}" class="hov-pop px-4 py-1.5 rounded-full hover:bg-[#1E3A8A]/50">Masuk</a>
</nav>

<main id="main" class="relative z-10">

<!-- HERO -->
<section class="max-w-6xl mx-auto px-4 sm:px-6 pt-36 pb-16 lg:pt-44 grid lg:grid-cols-[1.1fr_.9fr] gap-12 items-center">
  <div class="text-center lg:text-left">
    <p class="enter inline-flex items-center gap-2 bg-[#0F1F3D] border-2 border-[#1E3A8A] rounded-full px-4 py-1.5 text-sm font-bold text-[#93C5FD] hov-pop shadow-sm" style="--i:0">
      <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#60A5FA] opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#60A5FA]"></span>
      </span>
      Gratis &amp; sudah dipakai 12.500+ orang
    </p>

    <h1 class="title-pop f-display font-bold text-[3.4rem] sm:text-7xl lg:text-[5.6rem] leading-[1.02] mt-6 text-white">
      <span class="enter block" style="--i:1"><span class="w">Plan</span> <span class="w">It.</span></span>
      <span class="enter block my-2" style="--i:2"><span class="sticker"><span class="w">Join</span> <span class="w">It.</span></span></span>
      <span class="enter block" style="--i:3"><span class="hilite"><span class="w">Enjoy</span> <span class="w">It.</span></span></span>
    </h1>

    <p class="enter text-lg text-[#94A3B8] leading-relaxed max-w-xl mx-auto lg:mx-0 mt-7" style="--i:4">
      Satu akun untuk ngobrol di feed, cari tiket event, dan adu skill di turnamen. Temukan komunitas yang sefrekuensi denganmu.
    </p>

    <div class="enter flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mt-8" style="--i:5">
      <a href="{{ route('register') }}"
         class="btn group inline-flex items-center justify-center gap-2 bg-[#2563EB] text-white border-[3px] border-[#1D4ED8] font-extrabold px-8 py-4 rounded-2xl shadow-[5px_5px_0_#060D1F] hover:bg-[#3B82F6]">
        Daftar gratis
        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"><use href="#i-arrow-r"/></svg>
      </a>
      <button type="button" data-modal="sosial"
              class="btn inline-flex items-center justify-center gap-2 bg-[#0F1F3D] text-white border-[3px] border-[#1E3A8A] hover:border-[#60A5FA] font-bold px-8 py-4 rounded-2xl">
        Intip fiturnya
      </button>
    </div>
  </div>

  <div class="enter relative h-[460px] sm:h-[520px] max-w-md mx-auto w-full" style="--i:4">
    <div class="absolute top-4 right-2 w-24 h-24 sh-star bg-[#FACC15] floaty" style="--dur:7s"></div>
    <div class="absolute bottom-6 left-0 w-16 h-16 rounded-full bg-gradient-to-br from-[#60A5FA] to-[#1E3A8A] floaty" style="--dur:9s;--delay:2s"></div>

    <div role="button" tabindex="0" data-modal="game" aria-label="Buka detail Game Arena"
         class="card sh-oct tilt-r7 absolute right-0 top-8 w-60 bg-gradient-to-br from-[#10B981] to-[#34D399] text-white p-7 pt-9 text-center">
      <p class="f-display text-xl font-bold">Game Arena</p>
      <p class="text-sm text-white/90 mt-1">Turnamen mingguan</p>
      <p class="f-display text-4xl font-bold mt-3">#1</p>
      <p class="text-xs font-bold">Leaderboard</p>
    </div>

    <div role="button" tabindex="0" data-modal="sosial" aria-label="Buka detail Sosial Media"
         class="card absolute left-0 top-24 w-[18.5rem] bg-[#0F1F3D] border-[3px] border-[#3B82F6] sh-flag p-5 shadow-[6px_6px_0_#060D1F] z-10 tilt-l4">
      <div class="flex items-center gap-3 mb-3">
        <img src="https://i.pravatar.cc/100?img=33" class="w-10 h-10 rounded-full ring-2 ring-[#3B82F6]/50" alt="Foto profil Raka Pratama">
        <div>
          <p class="font-extrabold text-sm flex items-center gap-1 text-white">
            Raka Pratama
            <span class="inline-flex text-[#60A5FA]" aria-label="Terverifikasi">
              <svg class="w-4 h-4"><use href="#i-badge"/></svg>
            </span>
          </p>
          <p class="text-[11px] text-[#94A3B8]">&#64;raka_07 · 2j</p>
        </div>
      </div>
      <p class="text-sm leading-relaxed mb-3 text-[#CBD5E1]">
        Baru nyoba <b class="text-[#60A5FA]">&#64;DIGANOVA</b>. UI-nya keren, komunitasnya asik!
        <span class="inline-flex align-text-bottom text-[#FACC15]">
          <svg class="w-4 h-4"><use href="#i-bolt"/></svg>
        </span>
      </p>
      <div class="h-24 rounded-2xl bg-gradient-to-tr from-[#1E3A8A] to-[#60A5FA] grid place-items-center mb-3" aria-hidden="true">
        <svg class="w-8 h-8 text-white"><use href="#i-play"/></svg>
      </div>
      <div class="flex gap-4 text-xs font-bold text-[#94A3B8]">
        <span class="inline-flex items-center gap-1"><svg class="w-4 h-4"><use href="#i-chat"/></svg>124</span>
        <span class="inline-flex items-center gap-1"><svg class="w-4 h-4"><use href="#i-refresh"/></svg>89</span>
        <span class="inline-flex items-center gap-1 text-[#FB7185]"><svg class="w-4 h-4"><use href="#i-heart"/></svg>1.2K</span>
      </div>
    </div>

    <div role="button" tabindex="0" data-modal="event" aria-label="Buka detail Event dan Festival"
         class="card sh-ticket absolute bottom-0 right-4 w-64 bg-[#FACC15] text-[#1E3A8A] px-8 py-5 z-20 tilt-r3">
      <p class="text-xs font-extrabold">TIKET DIGITAL</p>
      <p class="f-display text-2xl font-bold leading-tight">Festival Musik 2025</p>
      <div class="border-t-2 border-dashed border-[#1E3A8A]/40 mt-3 pt-2 flex justify-between items-center text-xs font-bold">
        <span>QR siap scan</span>
        <span class="go inline-flex items-center"><svg class="w-4 h-4"><use href="#i-arrow-r"/></svg></span>
      </div>
    </div>
  </div>
</section>

<!-- MARQUEE -->
<section class="my-8 -rotate-1 w-[120vw] -ml-[10vw] bg-[#2563EB] border-y-[3px] border-[#1D4ED8] py-4 overflow-hidden shadow-[0_0_40px_rgba(59,130,246,0.4)]" aria-hidden="true">
  <div class="flex w-max marquee-mask" style="animation:marquee 30s linear infinite">
    @for ($i = 0; $i < 6; $i++)
      <div class="flex items-center gap-8 pr-8 text-white font-extrabold text-sm whitespace-nowrap f-display">
        @foreach ($marquee as $m)
          <span class="inline-flex items-center gap-2">
            <svg class="w-5 h-5 text-[#FACC15]"><use href="#{{ $m['icon'] }}"/></svg>
            {{ $m['label'] }}
          </span>
        @endforeach
      </div>
    @endfor
  </div>
</section>

<!-- TIGA DUNIA -->
<section id="dunia" class="max-w-6xl mx-auto px-4 sm:px-6 py-20 scroll-mt-24">
  <div class="text-center max-w-2xl mx-auto mb-16">
    <h2 class="title-pop f-display font-bold text-4xl lg:text-6xl text-white">
      Satu akun, <span class="sticker">tiga dunia</span>
    </h2>
    <p class="text-[#94A3B8] text-lg mt-6">Pilih dunia yang paling cocok denganmu, atau jelajahi semuanya.</p>
  </div>

  <div class="grid md:grid-cols-3 gap-8 items-start">
    @foreach ($dunia as $d)
      <div role="button" tabindex="0" data-modal="{{ $d['key'] }}" aria-label="Buka detail {{ $d['title'] }}"
           class="card {{ $d['shape'] }} {{ $d['tilt'] }} {{ $d['bg'] }} border-[3px] {{ $d['border'] }} {{ $d['pad'] }} text-center {{ $d['shadow'] }} {{ $d['offset'] }}">
        <span class="ico mx-auto w-20 h-20 {{ $d['iconShape'] }} bg-gradient-to-br {{ $d['iconBg'] }} grid place-items-center mb-5">
          <svg class="{{ $d['iconSize'] }} text-white"><use href="#{{ $d['icon'] }}"/></svg>
        </span>
        <h3 class="f-display text-3xl font-bold {{ $d['titleColor'] }}">{{ $d['title'] }}</h3>
        <p class="text-sm {{ $d['descColor'] }} mt-2">{{ $d['desc'] }}</p>
        <p class="mt-5 text-sm font-extrabold {{ $d['linkColor'] }} inline-flex items-center gap-1">
          Lihat detail
          <span class="go inline-flex items-center"><svg class="w-4 h-4"><use href="#i-arrow-r"/></svg></span>
        </p>
      </div>
    @endforeach
  </div>
</section>

<!-- FITUR -->
<section id="fitur" class="bg-[#0F1F3D]/60 backdrop-blur-sm border-y-[3px] border-[#1E3A8A] py-20 scroll-mt-16">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="title-pop f-display font-bold text-4xl lg:text-5xl text-white text-center mb-14">
      Bikin tiap interaksi terasa seru
    </h2>
    <div class="flex flex-wrap justify-center gap-6">
      @foreach ($fitur as $f)
        <button type="button" data-modal="{{ $f['key'] }}"
                class="card {{ $f['shape'] }} {{ $f['tilt'] }} w-64 {{ $f['bg'] }} {{ $f['border'] }} p-7 text-left">
          <span class="ico inline-grid place-items-center w-12 h-12 rounded-2xl {{ $f['icoBg'] }} {{ $f['iconColor'] }}">
            <svg class="w-6 h-6"><use href="#{{ $f['icon'] }}"/></svg>
          </span>
          <h3 class="f-display text-xl font-bold mt-3">{{ $f['title'] }}</h3>
          <p class="text-sm opacity-80 mt-1">{{ $f['desc'] }}</p>
        </button>
      @endforeach
    </div>
  </div>
</section>

<!-- STATISTIK -->
<section id="statistik" class="max-w-6xl mx-auto px-4 sm:px-6 py-24 scroll-mt-16">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @foreach ($stats as $s)
      <div class="stat {{ $s['shape'] }} {{ $s['bg'] }} {{ $s['border'] }} aspect-square grid place-content-center text-center {{ $s['pad'] }} {{ $s['text'] }}">
        <b class="f-display text-4xl lg:text-5xl {{ $s['valueColor'] }}">{{ $s['value'] }}</b>
        <span class="text-sm font-bold {{ $s['labelColor'] }}">{{ $s['label'] }}</span>
      </div>
    @endforeach
  </div>
</section>

<!-- CTA -->
<section class="max-w-5xl mx-auto px-4 sm:px-6 pb-24">
  <div class="relative overflow-hidden sh-leaf bg-gradient-to-br from-[#1D4ED8] via-[#2563EB] to-[#3B82F6] border-[4px] border-[#1D4ED8] shadow-[10px_10px_0_#060D1F,0_0_60px_rgba(59,130,246,0.4)] text-center px-8 py-16">
    <div class="absolute -top-10 -left-10 w-40 h-40 rounded-full bg-white/10" aria-hidden="true"></div>
    <div class="absolute -bottom-12 right-8 w-28 h-28 sh-star bg-[#FACC15] floaty" style="--dur:7s" aria-hidden="true"></div>
    <h2 class="title-pop title-pop--light relative f-display font-bold text-4xl lg:text-6xl text-white leading-tight">
      Siap gabung bareng<br>ribuan kreator?
    </h2>
    <p class="relative text-white/90 text-lg mt-5 max-w-xl mx-auto">Buat akun dalam 30 detik. Gratis, tanpa biaya tersembunyi.</p>
    <div class="relative flex flex-col sm:flex-row gap-4 justify-center mt-9">
      <a href="{{ route('register') }}"
         class="btn bg-white text-[#1D4ED8] font-extrabold text-lg px-10 py-4 rounded-2xl border-[3px] border-[#1E3A8A] shadow-[5px_5px_0_#060D1F]">Daftar sekarang</a>
      <a href="{{ route('login') }}"
         class="btn bg-[#0A1628]/40 text-white font-bold text-lg px-8 py-4 rounded-2xl border-[3px] border-white/40 hover:bg-[#0A1628]/60">Masuk ke akun</a>
    </div>
  </div>
</section>
</main>

<!-- FOOTER -->
<footer class="relative z-10 bg-[#060D1F] border-t-[3px] border-[#1E3A8A] pt-14 pb-24 md:pb-8">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
      <div class="col-span-2 md:col-span-1">
        <p class="f-display text-2xl font-bold text-white">DIGANOVA</p>
        <p class="text-[11px] font-bold text-[#60A5FA] mb-3">Plan It. Join It. Enjoy It.</p>
        <p class="text-sm text-[#94A3B8]">Ekosistem sosial, event, dan gaming untuk generasi baru Indonesia.</p>
      </div>
      <div>
        <h4 class="title-pop font-extrabold text-white mb-3 inline-block">Platform</h4>
        <ul class="space-y-2 text-sm text-[#94A3B8]">
          <li><a href="#dunia" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Sosial Media</a></li>
          <li><a href="#dunia" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Event &amp; Festival</a></li>
          <li><a href="#dunia" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Game Arena</a></li>
        </ul>
      </div>
      <div>
        <h4 class="title-pop font-extrabold text-white mb-3 inline-block">Perusahaan</h4>
        <ul class="space-y-2 text-sm text-[#94A3B8]">
          <li><a href="#" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Tentang Kami</a></li>
          <li><a href="#" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Kebijakan Privasi</a></li>
          <li><a href="#" class="hover:text-[#60A5FA] inline-block hover:translate-x-1 transition-transform">Syarat &amp; Ketentuan</a></li>
        </ul>
      </div>
      <div class="col-span-2 md:col-span-1">
        <h4 class="title-pop font-extrabold text-white mb-3 inline-block">Ikuti kami</h4>
        <div class="flex gap-2">
          @foreach ($socials as $s)
            <a href="#" aria-label="{{ $s['label'] }} DIGANOVA"
               class="hov-pop w-10 h-10 rounded-xl bg-[#0F1F3D] border-2 border-[#1E3A8A] grid place-items-center text-[#60A5FA] hover:bg-[#3B82F6] hover:text-white hover:border-[#60A5FA]">
              <svg class="w-5 h-5"><use href="#{{ $s['id'] }}"/></svg>
            </a>
          @endforeach
        </div>
      </div>
    </div>
    <p class="border-t-2 border-[#1E3A8A] pt-6 text-sm text-[#64748B] text-center md:text-left">
      &copy; 2025 DIGANOVA. All rights reserved.
    </p>
  </div>
</footer>

<!-- MODAL -->
<div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="mTitle" hidden>
  <div class="modal-bg" data-close></div>
  <div class="modal-box" id="mBox">
    <div class="bg-[#0F1F3D] border-[4px] border-[#3B82F6] shadow-[8px_8px_0_#060D1F,0_0_50px_rgba(59,130,246,0.4)] overflow-hidden" id="mShape">
      <div class="p-8 pb-10 text-white relative" id="mHead">
        <button type="button" data-close aria-label="Tutup"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/25 hover:bg-white/40 hover:rotate-90 transition grid place-items-center">
          <svg class="w-5 h-5"><use href="#i-close"/></svg>
        </button>
        <div class="w-16 h-16 rounded-2xl bg-white/25 grid place-items-center mb-3" id="mIcon" aria-hidden="true"></div>
        <h3 class="f-display text-3xl font-bold" id="mTitle"></h3>
        <p class="text-white/90 text-sm mt-1" id="mDesc"></p>
      </div>
      <div class="p-8">
        <ul class="space-y-3 text-sm text-[#CBD5E1]" id="mList"></ul>
        <a href="{{ route('register') }}" id="mCta"
           class="btn mt-7 w-full inline-flex justify-center font-extrabold px-6 py-4 rounded-2xl border-[3px] border-[#1E3A8A] shadow-[4px_4px_0_#060D1F]"></a>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  'use strict';
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const pt     = document.getElementById('pt');

  function reveal() {
    pt.classList.add('out');
    pt.classList.remove('in');
    document.body.classList.add('ready');
  }
  window.addEventListener('load', () => requestAnimationFrame(() => setTimeout(reveal, 250)));
  setTimeout(reveal, 2500);
  window.addEventListener('pageshow', (e) => { if (e.persisted) reveal(); });

  document.querySelectorAll('a[href]').forEach((a) => {
    const href = a.getAttribute('href');
    if (!href || href[0] === '#' || /^(mailto|tel|javascript):/.test(href) || a.target === '_blank') return;
    a.addEventListener('click', (e) => {
      if (e.metaKey || e.ctrlKey || e.shiftKey || reduce) return;
      let url;
      try { url = new URL(href, location.href); } catch (_) { return; }
      if (url.origin !== location.origin || (url.pathname === location.pathname && url.hash)) return;
      e.preventDefault();
      const rect = a.getBoundingClientRect();
      pt.style.setProperty('--x', (rect.left + rect.width  / 2) + 'px');
      pt.style.setProperty('--y', (rect.top  + rect.height / 2) + 'px');
      pt.classList.remove('out');
      void pt.offsetWidth;
      pt.classList.add('in');
      setTimeout(() => { location.href = url.href; }, 650);
    });
  });

  const track  = document.getElementById('navTrack');
  const bubble = document.getElementById('navBubble');
  if (track && bubble) {
    track.querySelectorAll('.nav-chip').forEach((chip) => {
      const move = () => {
        bubble.style.left    = chip.offsetLeft + 'px';
        bubble.style.width   = chip.offsetWidth + 'px';
        bubble.style.opacity = 1;
      };
      chip.addEventListener('mouseenter', move);
      chip.addEventListener('focus',      move);
    });
    track.addEventListener('mouseleave', () => { bubble.style.opacity = 0; });
  }

  const DATA = {
    sosial: {
      icon: 'chat', title: 'Sosial Media', desc: 'Feed, DM, dan interaksi real-time.',
      headerBg: 'linear-gradient(135deg,#1D4ED8,#60A5FA)', shape: 'sh-leaf',
      ctaClass: 'bg-[#2563EB] text-white', cta: 'Coba sekarang, gratis',
      items: [
        '<b>Feed &amp; postingan</b> — teks dan gambar hingga 500 karakter.',
        '<b>Hashtag &amp; trending</b> — ikuti obrolan yang lagi ramai.',
        '<b>Komentar &amp; repost</b> — bangun engagement organik.',
        '<b>Komunitas &amp; grup</b> — gabung circle yang sefrekuensi.',
      ],
    },
    dm: {
      icon: 'mail', title: 'Direct Message', desc: 'Ngobrol pribadi dengan siapa pun.',
      headerBg: 'linear-gradient(135deg,#1E3A8A,#3B82F6)', shape: 'sh-flag',
      ctaClass: 'bg-[#2563EB] text-white', cta: 'Mulai ngobrol',
      items: [
        '<b>Terenkripsi</b> — obrolanmu hanya untuk kamu dan temanmu.',
        '<b>Reaksi &amp; sticker</b> — bikin chat makin seru.',
        '<b>Cepat</b> — pesan terkirim seketika.',
      ],
    },
    event: {
      icon: 'ticket', title: 'Event & Festival', desc: 'Temukan dan hadiri event seru.',
      headerBg: 'linear-gradient(135deg,#F59E0B,#FBBF24)', shape: 'sh-leaf',
      ctaClass: 'bg-[#FACC15] text-[#1E3A8A]', cta: 'Daftar untuk membuka',
      items: [
        'Jelajahi event populer di kotamu.',
        'Beli tiket digital dengan QR code.',
        'Kenalan dengan peserta lain.',
        'Dapat pengingat otomatis lewat notifikasi.',
      ],
    },
    game: {
      icon: 'gamepad', title: 'Game Arena', desc: 'Turnamen, leaderboard, achievement.',
      headerBg: 'linear-gradient(135deg,#10B981,#34D399)', shape: 'sh-flag',
      ctaClass: 'bg-[#10B981] text-white', cta: 'Daftar untuk membuka',
      items: [
        'Ikut turnamen mingguan dengan hadiah.',
        'Pantau papan peringkat dan ranking global.',
        'Kumpulkan achievement eksklusif.',
        'Gabung squad dan komunitas gaming.',
      ],
    },
  };

  const $         = (id) => document.getElementById(id);
  const modal     = $('modal');
  const FOCUSABLE = 'button,[href],[tabindex]:not([tabindex="-1"])';
  let lastFocused = null;

  function openModal(key) {
    const data = DATA[key];
    if (!data) return;
    lastFocused = document.activeElement;
    $('mHead').style.background = data.headerBg;
    $('mShape').className = 'bg-[#0F1F3D] border-[4px] border-[#3B82F6] shadow-[8px_8px_0_#060D1F,0_0_50px_rgba(59,130,246,0.4)] overflow-hidden ' + data.shape;
    $('mIcon').innerHTML  = `<svg class="w-9 h-9"><use href="#i-${data.icon}"/></svg>`;
    $('mTitle').textContent = data.title;
    $('mDesc').textContent  = data.desc;
    $('mList').innerHTML = data.items.map((text, i) => `
      <li style="--n:${i}" class="flex gap-3">
        <span class="shrink-0 w-6 h-6 rounded-full bg-[#1E3A8A] text-[#93C5FD] grid place-items-center" aria-hidden="true">
          <svg class="w-3.5 h-3.5"><use href="#i-check"/></svg>
        </span>
        <span>${text}</span>
      </li>
    `).join('');
    const cta = $('mCta');
    cta.textContent = data.cta;
    cta.className   = 'btn mt-7 w-full inline-flex justify-center font-extrabold px-6 py-4 rounded-2xl border-[3px] border-[#1E3A8A] shadow-[4px_4px_0_#060D1F] ' + data.ctaClass;
    modal.hidden = false;
    void modal.offsetWidth;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    const firstBtn = modal.querySelector('[data-close].modal-bg ~ * button') || modal.querySelector('button');
    if (firstBtn) firstBtn.focus({ preventScroll: true });
  }

  function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    if (lastFocused) lastFocused.focus({ preventScroll: true });
    setTimeout(() => {
      if (!modal.classList.contains('active')) modal.hidden = true;
    }, 350);
  }

  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-modal]');
    if (opener) { openModal(opener.dataset.modal); return; }
    if (e.target.closest('[data-close]')) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    const card = e.target.closest && e.target.closest('[data-modal][role="button"]');
    if (card && (e.key === 'Enter' || e.key === ' ')) {
      e.preventDefault();
      card.click();
      return;
    }
    if (!modal.classList.contains('active')) return;
    if (e.key === 'Escape') { e.preventDefault(); closeModal(); return; }
    if (e.key === 'Tab') {
      const focusables = [...modal.querySelectorAll(FOCUSABLE)].filter((el) => el.offsetParent !== null);
      if (!focusables.length) return;
      const first = focusables[0];
      const last  = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });

  /* ============================================================
     ✨ TITLE POP — efek "TIMBUL + NEON GLOW" saat judul diklik ✨
     ============================================================ */
  const POP_COLORS = ['#FACC15', '#60A5FA', '#22D3EE', '#93C5FD', '#FDE68A', '#5EEAD4'];

  function burstSparkles(x, y, n) {
    if (reduce) return;
    for (let i = 0; i < n; i++) {
      const s = document.createElement('span');
      s.className = 'sparkle';
      s.style.left = x + 'px';
      s.style.top  = y + 'px';
      const color = POP_COLORS[i % POP_COLORS.length];
      s.style.background = color;
      const angle = (Math.PI * 2 * i) / n + Math.random() * 0.6;
      const dist  = 70 + Math.random() * 110;
      s.style.setProperty('--tx', Math.cos(angle) * dist + 'px');
      s.style.setProperty('--ty', Math.sin(angle) * dist + 'px');
      s.style.setProperty('--r',  (Math.random() * 720 - 360) + 'deg');
      const size = 8 + Math.random() * 10;
      s.style.width  = size + 'px';
      s.style.height = size + 'px';
      s.style.boxShadow = '0 0 16px ' + color + ', 0 0 30px ' + color;
      document.body.appendChild(s);
      setTimeout(() => s.remove(), 950);
    }
  }

  document.querySelectorAll('.title-pop').forEach((el) => {
    el.setAttribute('tabindex', '0');
    const pop = () => {
      if (el._popping) {
        el.classList.remove('popping');
        void el.offsetWidth;
      }
      el._popping = true;
      el.classList.add('popping');
      const r = el.getBoundingClientRect();
      burstSparkles(r.left + r.width / 2, r.top + r.height / 2, 14);
      clearTimeout(el._popT);
      el._popT = setTimeout(() => {
        el.classList.remove('popping');
        el._popping = false;
      }, 740);
    };
    el.addEventListener('click', pop);
    el.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        pop();
      }
    });
  });
})();
</script>
</body>
</html>