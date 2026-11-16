<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIGANOVA — Plan It. Join It. Enjoy It.</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;600;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --c-white: #FFFFFF; --c-indigo: #4647AE; --c-blue: #4382DF;
            --c-deep: #112E81; --c-navy: #090E39; --c-black: #000000;
        }
        .font-display { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        body { font-family: 'Space Grotesk', system-ui, sans-serif; }

        /* Extrude */
        .ex-w { --ec: #FFFFFF; } .ex-b { --ec: #4382DF; } .ex-i { --ec: #4647AE; } .ex-d { --ec: #112E81; } .ex-k { --ec: #000000; }
        .extrude { box-shadow: 1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec), 5px 5px 0 var(--ec), 6px 6px 0 var(--ec), 7px 7px 0 var(--ec), 8px 8px 0 var(--ec), 14px 20px 30px rgba(0,0,0,.5); }
        .extrude-sm { box-shadow: 1px 1px 0 var(--ec), 2px 2px 0 var(--ec), 3px 3px 0 var(--ec), 4px 4px 0 var(--ec), 8px 12px 18px rgba(0,0,0,.45); }

        /* Button 3D */
        .btn-3d {
            --ec: #4382DF;
            transform: translate(var(--tx, 0px), var(--ty, 0px));
            box-shadow: 6px 6px 0 var(--ec), 6px 14px 24px rgba(0,0,0,.45);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-3d:hover { transform: translate(calc(var(--tx, 0px) + 3px), calc(var(--ty, 0px) + 3px)); box-shadow: 3px 3px 0 var(--ec), 3px 8px 14px rgba(0,0,0,.4); }
        .btn-3d:active { transform: translate(calc(var(--tx, 0px) + 6px), calc(var(--ty, 0px) + 6px)); box-shadow: 0 0 0 var(--ec); }

        /* Background */
        .bg-grid {
            background-image:
                linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at 50% 25%, #000 25%, transparent 75%);
        }
        #glow { background: radial-gradient(600px circle at var(--mx, 50%) var(--my, 25%), rgba(67,130,223,.16), transparent 60%); }

        /* Orb */
        .orb {
            --oc: #4382DF; --os: rgba(9,14,57,.34); --oh: #fff; --oe: #000;
            border-radius: 9999px; border: 3px solid #fff;
            background:
                radial-gradient(ellipse 13% 8% at 27% 23%, var(--oh) 0 92%, transparent 100%),
                radial-gradient(circle at 32% 30%, transparent 0 60%, var(--os) 61%),
                var(--oc);
            box-shadow: 4px 5px 0 var(--oe), 10px 16px 24px rgba(0,0,0,.35);
        }
        .orb-i { --oc: #4647AE; --oe: #000; }
        .orb-w { --oc: #fff; --os: rgba(67,130,223,.4); --oe: #4382DF; }
        .orb-lg { border-width: 5px; box-shadow: 8px 10px 0 var(--oe), 18px 28px 40px rgba(0,0,0,.35); }

        /* Cube */
        .scene { perspective: 900px; }
        .cube { position: relative; width: var(--s); height: var(--s); transform-style: preserve-3d; animation: cube-spin 16s linear infinite; }
        .cube:hover { animation-play-state: paused; }
        .cube-face { position: absolute; inset: 0; display: grid; place-items: center; border: 2px solid #fff; border-radius: 14px; }
        .cube .f1 { transform: rotateY(0deg) translateZ(calc(var(--s)/2)); }
        .cube .f2 { transform: rotateY(90deg) translateZ(calc(var(--s)/2)); }
        .cube .f3 { transform: rotateY(180deg) translateZ(calc(var(--s)/2)); }
        .cube .f4 { transform: rotateY(-90deg) translateZ(calc(var(--s)/2)); }
        .cube .f5 { transform: rotateX(90deg) translateZ(calc(var(--s)/2)); }
        .cube .f6 { transform: rotateX(-90deg) translateZ(calc(var(--s)/2)); }

        /* Ticket */
        .ticket {
            --r: 14px;
            -webkit-mask: radial-gradient(circle var(--r) at 0 62%, #0000 98%, #000) left / 51% 100% no-repeat, radial-gradient(circle var(--r) at 100% 62%, #0000 98%, #000) right / 51% 100% no-repeat;
            mask: radial-gradient(circle var(--r) at 0 62%, #0000 98%, #000) left / 51% 100% no-repeat, radial-gradient(circle var(--r) at 100% 62%, #0000 98%, #000) right / 51% 100% no-repeat;
        }

        /* Blob */
        .blob-b { border-radius: 40% 60% 35% 65% / 60% 35% 65% 40%; }

        /* Keyframes */
        @keyframes floaty { 0%,100% { transform: translateY(0) rotate(var(--rot,0deg)); } 50% { transform: translateY(-14px) rotate(calc(var(--rot,0deg) + 2deg)); } }
        @keyframes wiggle { 0%,100% { transform: rotate(-5deg); } 50% { transform: rotate(5deg); } }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        @keyframes cube-spin { from { transform: rotateX(-22deg) rotateY(0deg); } to { transform: rotateX(-22deg) rotateY(360deg); } }
        @keyframes shine { 0% { transform: translateX(-120%) skewX(-20deg); } 100% { transform: translateX(260%) skewX(-20deg); } }
        @keyframes word-in { from { opacity: 0; transform: translateY(46px) rotateX(-70deg); } to { opacity: 1; transform: none; } }
        @keyframes pop-in { 0% { opacity: 0; transform: scale(.6) rotate(-8deg); } 70% { transform: scale(1.06) rotate(2deg); } 100% { opacity: 1; transform: scale(1) rotate(-2deg); } }
        @keyframes breathe { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }
        @keyframes curtain-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes curtain-shine { from { background-position: 0% 0; } to { background-position: 200% 0; } }
        @keyframes curtain-bar { from { transform: scaleX(0); } to { transform: scaleX(1); } }
        @keyframes curtain-eq { 0%,100% { height: 8px; opacity: .5; } 50% { height: 22px; opacity: 1; } }

        .floaty { animation: floaty 6s ease-in-out infinite; }
        .floaty-2 { animation: floaty 7.5s ease-in-out infinite reverse; }
        .wiggle { animation: wiggle 2.4s ease-in-out infinite; }
        .breathe { animation: breathe 4s ease-in-out infinite; }
        .marquee-track { animation: marquee 28s linear infinite; }
        .marquee:hover .marquee-track { animation-play-state: paused; }
        .pop-in { animation: pop-in .8s cubic-bezier(.2,.9,.3,1.3) both; }
        .word { display: inline-block; opacity: 0; transform-origin: 50% 100%; animation: word-in .9s cubic-bezier(.2,.9,.3,1) forwards; animation-delay: calc(var(--i, 0) * 90ms + 150ms); }

        .shine::after { content: ''; position: absolute; inset: 0; width: 40%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent); transform: translateX(-120%) skewX(-20deg); }
        .shine:hover::after { animation: shine .9s ease; }

        /* Reveal */
        .js .reveal { opacity: 0; transform: translateY(36px) scale(.97); transition: opacity .7s ease, transform .7s cubic-bezier(.2,.8,.2,1); }
        .js .reveal.in { opacity: 1; transform: none; }

        /* Tilt */
        [data-tilt] { position: relative; transition: transform .15s ease-out; transform-style: preserve-3d; will-change: transform; }
        .z-1 { transform: translateZ(26px); }
        .z-2 { transform: translateZ(52px); }
        .glare { position: absolute; inset: 0; border-radius: inherit; pointer-events: none; opacity: 0; transition: opacity .3s; z-index: 5; background: radial-gradient(circle at var(--gx, 50%) var(--gy, 50%), rgba(255,255,255,.3), transparent 50%); }
        [data-tilt]:hover > .glare { opacity: 1; }

        .hov-pop { transition: scale .3s cubic-bezier(.3,1.8,.5,1), rotate .3s cubic-bezier(.3,1.8,.5,1); }
        .hov-pop:hover { scale: 1.12; rotate: -4deg; z-index: 5; }

        /* Flip */
        .flip { perspective: 700px; outline: none; }
        .flip-inner { position: relative; width: 100%; height: 100%; transform-style: preserve-3d; transition: transform .7s cubic-bezier(.2,.8,.2,1); }
        .flip:hover .flip-inner, .flip:focus .flip-inner { transform: rotateY(180deg); }
        .flip-face { position: absolute; inset: 0; -webkit-backface-visibility: hidden; backface-visibility: hidden; }
        .flip-back { transform: rotateY(180deg); }

        /* FAQ */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        .faq-item { transition: translate .3s cubic-bezier(.3,1.6,.5,1), border-color .2s ease, box-shadow .2s ease; }
        .faq-item:hover { translate: 8px 0; border-color: rgba(67,130,223,.9); }
        .faq-item[open] { border-color: #fff; box-shadow: 5px 5px 0 #4382DF; }
        .faq-item[open] .faq-plus { transform: rotate(45deg); background: #4382DF; color: #090E39; }

        /* ============ CURTAIN ============ */
        #curtain {
            --panel-dur: .55s; --panel-gap: 40ms;
            position: fixed; inset: 0; z-index: 400;
            display: none; overflow: hidden; pointer-events: none;
        }
        .js #curtain { display: block; }
        .js #curtain.is-done { display: none; }
        .curtain-panels { position: absolute; inset: 0; display: flex; }
        .curtain-panel {
            flex: 1; height: 100%; background: var(--pc); pointer-events: auto;
            transform: translateY(-120%);
            box-shadow: 0 6px 0 #4382DF, 0 16px 28px rgba(0,0,0,.45);
            transition: transform var(--panel-dur) cubic-bezier(.76,0,.24,1);
            transition-delay: calc(var(--n) * var(--panel-gap));
            will-change: transform;
        }
        #curtain.is-covering .curtain-panel { transform: translateY(0); }
        #curtain.is-leaving { --panel-dur: .38s; --panel-gap: 25ms; }
        #curtain.is-leaving .curtain-content { opacity: 0 !important; transform: scale(1.03) !important; transition: opacity .12s ease !important; transition-delay: 0s !important; }

        .curtain-content {
            position: absolute; inset: 0; z-index: 2; overflow: hidden;
            opacity: 0; transform: scale(1.04);
            transition: opacity .35s ease, transform .45s cubic-bezier(.3,1.6,.5,1);
            pointer-events: none;
        }
        #curtain.is-covering .curtain-content { opacity: 1; transform: none; transition-delay: .1s; }

        .curtain-branding {
            position: relative; z-index: 4;
            display: grid; place-content: center; justify-items: center; gap: 1rem;
            height: 100%; padding: 2rem;
        }
        #curtain.countdown-armed .curtain-branding { display: none; }

        .curtain-logo { animation: curtain-float 3s ease-in-out infinite; }
        .curtain-text h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800; font-size: 2.25rem; letter-spacing: -.02em;
            background: linear-gradient(120deg, #fff 0%, #4382DF 50%, #fff 100%);
            background-size: 200% 100%;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            animation: curtain-shine 3s linear infinite;
        }
        .curtain-text p {
            font-size: .75rem; font-weight: 700; letter-spacing: .25em;
            text-transform: uppercase; color: rgba(255,255,255,.55); text-align: center;
        }
        .curtain-bar-wrap {
            width: 11rem; height: 14px; padding: 2px;
            border: 2px solid #fff; border-radius: 999px;
            background: #090E39; overflow: hidden;
        }
        .curtain-bar {
            height: 100%; width: 100%; border-radius: 999px;
            background: linear-gradient(90deg, #4382DF, #fff, #4382DF);
            transform-origin: left;
            animation: curtain-bar .7s cubic-bezier(.65,0,.35,1) .1s both;
        }
        .curtain-eq { display: flex; gap: 4px; align-items: flex-end; height: 22px; }
        .curtain-eq span { display: block; width: 5px; border-radius: 2px; background: #4382DF; box-shadow: 0 0 8px rgba(67,130,223,.8); animation: curtain-eq 1s ease-in-out infinite; }
        .curtain-eq span:nth-child(2) { animation-delay: .15s; }
        .curtain-eq span:nth-child(3) { animation-delay: .3s; }
        .curtain-eq span:nth-child(4) { animation-delay: .45s; }
        .curtain-eq span:nth-child(5) { animation-delay: .6s; }

        /* Countdown */
        .countdown-display {
            position: absolute; inset: 0; z-index: 3;
            display: grid; place-items: center;
            font-family: 'Bricolage Grotesque', sans-serif; font-weight: 800;
            pointer-events: none;
        }
        .countdown-num, .countdown-go {
            grid-area: 1 / 1; opacity: 0;
            background: linear-gradient(180deg, #ffffff 0%, #4382DF 55%, #ffffff 100%);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            filter: drop-shadow(3px 3px 0 #4382DF) drop-shadow(6px 6px 0 #112E81) drop-shadow(0 12px 24px rgba(0,0,0,.65));
        }
        .countdown-num { font-size: clamp(7rem, 22vw, 12rem); line-height: 1; letter-spacing: -.04em; }
        .countdown-go { font-size: clamp(2.25rem, 8vw, 4rem); letter-spacing: .02em; padding-left: .05em; filter: drop-shadow(2px 2px 0 #4382DF) drop-shadow(4px 4px 0 #112E81) drop-shadow(0 8px 18px rgba(0,0,0,.6)); }

        #curtain.countdown-armed.is-covering:not(.is-leaving) .countdown-num[data-num="3"] { animation: countdown-num .35s cubic-bezier(.2,.9,.3,1.4) both; animation-delay: .10s; }
        #curtain.countdown-armed.is-covering:not(.is-leaving) .countdown-num[data-num="2"] { animation: countdown-num .35s cubic-bezier(.2,.9,.3,1.4) both; animation-delay: .40s; }
        #curtain.countdown-armed.is-covering:not(.is-leaving) .countdown-num[data-num="1"] { animation: countdown-num .35s cubic-bezier(.2,.9,.3,1.4) both; animation-delay: .70s; }
        #curtain.countdown-armed.is-covering:not(.is-leaving) .countdown-go { animation: countdown-go .30s cubic-bezier(.2,.9,.3,1.4) both; animation-delay: .95s; }

        @keyframes countdown-num {
            0%   { opacity: 0; transform: scale(.35) rotate(-10deg); filter: blur(10px) drop-shadow(3px 3px 0 #4382DF) drop-shadow(6px 6px 0 #112E81); }
            30%  { opacity: 1; transform: scale(1.12) rotate(2deg);   filter: blur(0)    drop-shadow(3px 3px 0 #4382DF) drop-shadow(6px 6px 0 #112E81); }
            65%  { opacity: 1; transform: scale(1)    rotate(0);      }
            100% { opacity: 0; transform: scale(1.35) rotate(0);      filter: blur(6px)  drop-shadow(3px 3px 0 #4382DF) drop-shadow(6px 6px 0 #112E81); }
        }
        @keyframes countdown-go {
            0%   { opacity: 0; transform: scale(.55); }
            45%  { opacity: 1; transform: scale(1.12); }
            100% { opacity: 1; transform: scale(1); }
        }

        .js:not(.ready) { overflow: hidden; }
        .js:not(.ready) .word, .js:not(.ready) .pop-in { animation-play-state: paused; }

        @media (prefers-reduced-motion: reduce) {
            #curtain { display: none !important; }
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
            .word { opacity: 1; transform: none; }
            .js .reveal { opacity: 1; transform: none; }
            .countdown-num, .countdown-go { display: none !important; }
        }
    </style>
</head>
<body id="top" class="min-h-screen bg-[#090E39] text-white overflow-x-hidden selection:bg-[#4382DF] selection:text-white">
<script>document.documentElement.classList.add('js');</script>

@php
    $icons = [
        'ticket'   => '<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'mic'      => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"/>',
        'gamepad'  => '<path d="M6 11h4M8 9v4M15 12h.01M18 10h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.98 3.59C2.6 9.42 2 14.46 2 16a3 3 0 0 0 3 3c1 0 1.5-.5 2-1l1.41-1.41A2 2 0 0 1 9.83 16h4.34a2 2 0 0 1 1.41.59L17 18c.5.5 1 1 2 1a3 3 0 0 0 3-3c0-1.54-.6-6.58-.69-7.26A4 4 0 0 0 17.32 5z"/>',
        'palette'  => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.93 0 1.5-.75 1.5-1.5 0-.39-.15-.73-.39-1-.23-.27-.38-.62-.38-1 0-.83.67-1.5 1.5-1.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9z"/>',
        'shirt'    => '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'party'    => '<path d="M5.8 11.3 2 22l10.7-3.79"/><path d="M4 3h.01M22 8h.01M15 2h.01M22 20h.01"/><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10"/><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11-.11.7-.72 1.22-1.43 1.22H17"/><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7"/><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2z"/>',
        'trophy'   => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>',
        'flame'    => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
        'headphones' => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
        'crown'    => '<path d="M11.56 3.27a.5.5 0 0 1 .88 0l2.95 5.6a1 1 0 0 0 1.52.29l4.28-3.67a.5.5 0 0 1 .8.52l-2.83 10.25a1 1 0 0 1-.96.73H5.81a1 1 0 0 1-.96-.73L2.02 6.02a.5.5 0 0 1 .8-.52l4.28 3.67a1 1 0 0 0 1.52-.29z"/><path d="M5 21h14"/>',
        'eye'      => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'smile'    => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
        'heart'    => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>',
        'zap'      => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
        'music'    => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'lock'     => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'log-in'   => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/>',
        'user-plus'=> '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>',
        'settings' => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>',
        'layout'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
    ];
    $icon = fn (string $name, string $class = 'w-6 h-6') =>
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';

    $logo = fn (string $class = 'w-10 h-10') =>
        '<img src="' . asset('images/logo.png') . '" alt="Logo DIGANOVA" class="' . $class . ' object-contain" draggable="false">';

    $cube = function (string $size) use ($icon) {
        $faces = [
            ['mic', 'f1', 'bg-[#4647AE] text-white'],
            ['gamepad', 'f2', 'bg-[#4382DF] text-[#090E39]'],
            ['palette', 'f3', 'bg-[#112E81] text-white'],
            ['shirt', 'f4', 'bg-[#4647AE] text-white'],
            ['ticket', 'f5', 'bg-white text-[#090E39]'],
            ['music', 'f6', 'bg-[#4382DF] text-[#090E39]'],
        ];
        $html = '<div class="cube" style="--s:' . $size . '">';
        foreach ($faces as [$ico, $f, $cls]) {
            $html .= '<div class="cube-face ' . $f . ' ' . $cls . '">' . $icon($ico, 'w-1/2 h-1/2') . '</div>';
        }
        return $html . '</div>';
    };
@endphp

    {{-- CURTAIN --}}
    <div id="curtain" class="is-covering" aria-hidden="true">
        <div class="curtain-panels">
            @foreach (['#090E39', '#112E81', '#4647AE', '#112E81', '#090E39'] as $n => $pc)
                <div class="curtain-panel" style="--pc: {{ $pc }}; --n: {{ $n }}"></div>
            @endforeach
        </div>
        <div class="curtain-content">
            <div class="countdown-display" aria-hidden="true">
                <span class="countdown-num" data-num="3">3</span>
                <span class="countdown-num" data-num="2">2</span>
                <span class="countdown-num" data-num="1">1</span>
                <span class="countdown-go">SHOW!</span>
            </div>
            <div class="curtain-branding">
                <div class="curtain-logo">{!! $logo('w-24 h-24 sm:w-32 sm:h-32') !!}</div>
                <div class="curtain-text">
                    <h2>DIGANOVA</h2>
                    <p>Plan It. Join It. Enjoy It.</p>
                </div>
                <div class="curtain-bar-wrap"><div class="curtain-bar"></div></div>
                <div class="curtain-eq"><span></span><span></span><span></span><span></span><span></span></div>
            </div>
        </div>
    </div>

    {{-- Progress bar --}}
    <div id="progress" class="fixed top-0 left-0 right-0 h-1 z-[70] origin-left scale-x-0 bg-gradient-to-r from-[#4647AE] via-[#4382DF] to-white"></div>

    {{-- Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="absolute -top-40 -left-40 w-[38rem] h-[38rem] bg-[#4647AE]/40 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 -right-40 w-[38rem] h-[38rem] bg-[#4382DF]/25 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-20 left-1/3 w-[30rem] h-[30rem] bg-[#112E81]/70 rounded-full blur-[120px]"></div>
        <div id="glow" class="absolute inset-0"></div>
    </div>

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 px-4 pt-3">
        <nav class="max-w-6xl mx-auto flex items-center justify-between gap-3 bg-[#090E39]/85 backdrop-blur-xl border-2 border-white rounded-full px-3 py-2 extrude-sm ex-i">
            <a href="#top" class="flex items-center gap-2.5 shrink-0">
                <span class="wiggle shrink-0">{!! $logo('w-10 h-10') !!}</span>
                <span class="flex flex-col leading-none">
                    <span class="font-display font-extrabold tracking-tight text-lg">DIGANOVA</span>
                    <span class="mt-0.5 text-[9px] font-semibold tracking-wide text-white/60 whitespace-nowrap hidden sm:block">Plan It. Join It. Enjoy It.</span>
                </span>
            </a>

            <div class="flex items-center gap-1.5 shrink-0">
                <a href="{{ route('event.events.index') }}" data-cursor="Events"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white/80 hover:text-white hover:bg-white/10 transition">
                    {!! $icon('ticket', 'w-4 h-4') !!} Events
                </a>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.events.index') }}" data-cursor="Admin"
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white/80 hover:text-white hover:bg-white/10 transition">
                            {!! $icon('settings', 'w-4 h-4') !!} Admin
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" data-cursor="Dashboard"
                       class="hov-pop inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2 rounded-full text-sm font-extrabold hover:bg-[#4382DF] hover:text-white transition">
                        {!! $icon('layout', 'w-4 h-4') !!} Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" data-cursor="Masuk"
                       class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold text-white/80 hover:text-white hover:bg-white/10 transition">
                        {!! $icon('log-in', 'w-4 h-4') !!} Login
                    </a>
                    <a href="{{ route('register') }}" data-cursor="Gabung"
                       class="hov-pop inline-flex items-center gap-2 bg-white text-[#090E39] px-4 py-2 rounded-full text-sm font-extrabold hover:bg-[#4382DF] hover:text-white transition">
                        {!! $icon('user-plus', 'w-4 h-4') !!} Daftar
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main-content" class="relative z-10">

        {{-- HERO --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 lg:pt-20 pb-20 grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-center lg:text-left">
                <div class="hov-pop inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white text-[#090E39] text-sm font-bold extrude-sm ex-b mb-8 pop-in">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4382DF] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#4647AE]"></span>
                    </span>
                    Platform Event Masa Depan
                </div>

                <h1 class="font-display text-5xl sm:text-6xl xl:text-7xl font-extrabold leading-[1.05] tracking-tight mb-6" style="perspective: 700px">
                    <span class="word" style="--i:0">Satu</span>
                    <span class="word" style="--i:1">akun,</span>
                    <br class="hidden sm:block">
                    <span class="word relative mx-1" style="--i:2">
                        <span class="absolute inset-0 bg-[#4647AE] -rotate-2 rounded-2xl extrude-sm ex-k" aria-hidden="true"></span>
                        <span class="relative px-3">tiga</span>
                    </span>
                    <span class="word" style="--i:3">pengalaman.</span>
                </h1>

                <p class="text-lg md:text-xl text-white/70 max-w-xl mx-auto lg:mx-0 mb-4">
                    Event, Social, dan Playground — semua ada di satu platform.
                </p>
                <p class="font-display italic text-white/60 text-base md:text-lg mb-10">
                    "Plan It. Join It. Enjoy It."
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-5">
                    <a href="{{ route('event.events.index') }}" data-magnetic data-cursor="Lihat"
                       class="btn-3d shine relative overflow-hidden w-full sm:w-auto bg-white text-[#090E39] px-8 py-4 rounded-2xl font-extrabold text-lg border-2 border-white flex items-center justify-center gap-2 group">
                        {!! $icon('party', 'w-5 h-5') !!}
                        Lihat Events
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.events.index') }}" data-magnetic data-cursor="Admin"
                               class="btn-3d ex-k w-full sm:w-auto bg-[#4647AE] text-white px-8 py-4 rounded-2xl font-bold text-lg border-2 border-white flex items-center justify-center gap-2">
                                {!! $icon('settings', 'w-5 h-5') !!}
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" data-magnetic data-cursor="Dashboard"
                           class="btn-3d ex-k w-full sm:w-auto bg-[#4647AE] text-white px-8 py-4 rounded-2xl font-bold text-lg border-2 border-white flex items-center justify-center gap-2">
                            {!! $icon('layout', 'w-5 h-5') !!}
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" data-magnetic data-cursor="Masuk"
                           class="btn-3d ex-k w-full sm:w-auto bg-[#4647AE] text-white px-8 py-4 rounded-2xl font-bold text-lg border-2 border-white flex items-center justify-center gap-2">
                            {!! $icon('log-in', 'w-5 h-5') !!}
                            Login
                        </a>
                    @endauth
                </div>

                @guest
                    <p class="mt-6 text-sm text-white/60">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-[#4382DF] hover:text-white underline underline-offset-4">Daftar gratis</a>
                    </p>
                @endguest

                <div class="mt-12 flex items-center justify-center lg:justify-start gap-4 text-sm text-white/70">
                    <div class="flex -space-x-3">
                        <span class="hov-pop w-10 h-10 rounded-full bg-white text-[#090E39] border-2 border-[#090E39] grid place-items-center">{!! $icon('mic', 'w-5 h-5') !!}</span>
                        <span class="hov-pop w-10 h-10 rounded-full bg-[#4382DF] text-[#090E39] border-2 border-[#090E39] grid place-items-center">{!! $icon('gamepad', 'w-5 h-5') !!}</span>
                        <span class="hov-pop w-10 h-10 rounded-full bg-[#4647AE] text-white border-2 border-[#090E39] grid place-items-center">{!! $icon('palette', 'w-5 h-5') !!}</span>
                        <span class="hov-pop w-10 h-10 rounded-full bg-[#112E81] text-white border-2 border-white/60 grid place-items-center">{!! $icon('shirt', 'w-5 h-5') !!}</span>
                    </div>
                    <span>Bergabung bareng para <b class="text-white">penikmat event</b> lainnya</span>
                </div>
            </div>

            <div data-parallax class="relative h-[31rem] sm:h-[36rem] max-w-md sm:max-w-lg mx-auto w-full">
                <div data-depth="12" class="absolute top-2 right-2 sm:right-6">
                    <div class="orb orb-lg breathe w-24 h-24 sm:w-32 sm:h-32"></div>
                </div>
                <div data-depth="60" class="absolute top-[44%] left-[8%]">
                    <div class="orb orb-i floaty w-10 h-10"></div>
                </div>
                <div data-depth="40" class="absolute bottom-[26%] right-[34%]">
                    <div class="orb orb-w floaty-2 w-7 h-7"></div>
                </div>
                <div data-depth="34" class="absolute top-[40%] right-6 sm:right-10 w-36 h-36">
                    <div class="scene relative w-full h-full grid place-items-center">
                        {!! $cube('92px') !!}
                    </div>
                </div>
                <div data-depth="46" class="absolute top-2 left-0 sm:left-2 w-72 sm:w-80">
                    <div class="floaty" style="--rot:-5deg">
                        <div class="relative">
                            <div class="absolute inset-0 translate-x-3 translate-y-3 rounded-3xl bg-[#4382DF]"></div>
                            <div class="absolute inset-0 translate-x-6 translate-y-6 rounded-3xl bg-[#4647AE]"></div>
                            <div data-tilt data-rot="0">
                                <div class="ticket bg-gradient-to-br from-[#4382DF] via-[#4647AE] to-white p-[3px] rounded-3xl">
                                    <div class="ticket relative overflow-hidden bg-[#0d1450] rounded-[22px] p-5">
                                        <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-widest text-white/60">
                                            <span class="inline-flex items-center gap-1.5">{!! $icon('ticket', 'w-4 h-4') !!} E-Ticket</span>
                                            <span class="text-[#4382DF]">● Valid</span>
                                        </div>
                                        <h3 class="font-display font-extrabold text-2xl mt-3 leading-tight">Neon Night<br>Music Fest</h3>
                                        <div class="mt-4 flex gap-2 text-xs font-semibold">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10">{!! $icon('music', 'w-3.5 h-3.5') !!} Musik</span>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10">{!! $icon('pin', 'w-3.5 h-3.5') !!} Palembang</span>
                                        </div>
                                        <div class="my-5 border-t-2 border-dashed border-white/20"></div>
                                        <div class="flex items-end justify-between">
                                            <div>
                                                <p class="text-[10px] uppercase tracking-widest text-white/50">Seat</p>
                                                <p class="font-display font-extrabold text-xl">VIP-07</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-depth="28" class="absolute bottom-6 left-0 sm:left-6">
                    <div class="floaty-2" style="--rot:6deg">
                        <div data-tilt data-rot="6" class="bg-white text-[#090E39] border-2 border-white rounded-3xl px-5 py-4 extrude ex-b">
                            <div class="z-1 w-fit">{!! $icon('trophy', 'w-9 h-9 text-[#4647AE]') !!}</div>
                            <p class="z-1 font-display font-extrabold leading-tight mt-1">Early Bird<br>Unlocked!</p>
                        </div>
                    </div>
                </div>
                <div data-depth="58" class="absolute bottom-0 right-0 sm:right-6">
                    <div class="floaty" style="--rot:-3deg">
                        <div data-tilt data-rot="-3" class="flex items-center gap-3 bg-white text-[#090E39] rounded-2xl pl-3 pr-5 py-3 border-2 border-white extrude-sm ex-i">
                            <span class="z-1 grid place-items-center w-9 h-9 rounded-xl bg-[#4382DF] text-white">{!! $icon('gamepad', 'w-5 h-5') !!}</span>
                            <div class="z-1 leading-tight">
                                <p class="text-xs text-[#112E81]/70 font-medium">Turnamen baru</p>
                                <p class="text-sm font-extrabold">Slot tinggal 12!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MARQUEE --}}
        <section class="marquee relative border-y-2 border-white bg-[#4382DF] text-[#090E39] overflow-hidden -rotate-1 my-6" aria-hidden="true">
            <div class="marquee-track flex w-max whitespace-nowrap py-4 font-display font-extrabold text-2xl sm:text-3xl uppercase tracking-tight">
                @for ($i = 0; $i < 2; $i++)
                    <div class="flex items-center gap-8 pr-8">
                        <span>Plan It</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                        <span>Join It</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                        <span>Enjoy It</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                        <span>Event</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                        <span>Social</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                        <span>Playground</span><i class="w-3 h-3 bg-[#090E39] rotate-45"></i>
                    </div>
                @endfor
            </div>
        </section>

        {{-- THREE PILLARS --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="reveal text-center max-w-2xl mx-auto mb-14">
                <span class="hov-pop inline-block px-4 py-1.5 rounded-full bg-[#4647AE] text-sm font-bold rotate-2 mb-4 border-2 border-white/80">
                    Tiga Pilar
                </span>
                <h2 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight">Satu akun, tiga pengalaman</h2>
                <p class="text-white/70 mt-4 text-lg">Semua yang kamu butuhkan untuk hidupkan momen serumu.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Event --}}
                <div class="reveal">
                    <div data-tilt data-rot="-2" class="group relative h-full min-h-[20rem] bg-white text-[#090E39] border-2 border-white rounded-[2.5rem] p-8 extrude ex-b flex flex-col">
                        <div class="absolute right-8 bottom-8 z-2 text-[#4647AE] group-hover:rotate-12 transition-transform">
                            {!! $icon('calendar', 'w-20 h-20') !!}
                        </div>
                        <span class="z-1 relative inline-flex w-fit items-center gap-1.5 bg-[#090E39] text-white text-xs font-bold px-3 py-1 rounded-full">01</span>
                        <h3 class="z-1 relative font-display font-extrabold text-3xl mt-4 leading-none">Event</h3>
                        <p class="z-1 relative mt-3 max-w-xs font-medium text-[#112E81] flex-1">
                            Festival musik, cosplay, pameran seni, turnamen game — semua ada di sini.
                        </p>
                        <a href="{{ route('event.events.index') }}" class="z-1 relative mt-6 inline-flex items-center gap-2 font-extrabold text-[#4647AE] group-hover:gap-3 transition-all">
                            Jelajahi Events <span>→</span>
                        </a>
                    </div>
                </div>

                {{-- Social --}}
                <div class="reveal" data-delay="100">
                    <div data-tilt data-rot="2" class="group relative h-full min-h-[20rem] bg-[#4382DF] text-[#090E39] border-2 border-white rounded-[2.5rem] p-8 extrude ex-w flex flex-col">
                        <div class="absolute right-8 bottom-8 z-2 group-hover:rotate-12 transition-transform">
                            {!! $icon('users', 'w-20 h-20') !!}
                        </div>
                        <span class="z-1 relative inline-flex w-fit items-center gap-1.5 bg-[#090E39] text-white text-xs font-bold px-3 py-1 rounded-full">02</span>
                        <h3 class="z-1 relative font-display font-extrabold text-3xl mt-4 leading-none">Social</h3>
                        <p class="z-1 relative mt-3 max-w-xs font-medium flex-1">
                            Gabung komunitas, kenalan sama orang baru, dan bagi momen seru bareng.
                        </p>
                        <span class="z-1 relative mt-6 inline-flex items-center gap-2 font-extrabold">
                            Coming Soon <span class="w-2 h-2 rounded-full bg-[#090E39] animate-pulse"></span>
                        </span>
                    </div>
                </div>

                {{-- Playground --}}
                <div class="reveal" data-delay="200">
                    <div data-tilt data-rot="-2" class="group relative h-full min-h-[20rem] bg-[#4647AE] text-white border-2 border-white rounded-[2.5rem] p-8 extrude ex-k flex flex-col">
                        <div class="absolute right-8 bottom-8 z-2 group-hover:rotate-12 transition-transform">
                            {!! $icon('gamepad', 'w-20 h-20') !!}
                        </div>
                        <span class="z-1 relative inline-flex w-fit items-center gap-1.5 bg-white text-[#090E39] text-xs font-bold px-3 py-1 rounded-full">03</span>
                        <h3 class="z-1 relative font-display font-extrabold text-3xl mt-4 leading-none">Playground</h3>
                        <p class="z-1 relative mt-3 max-w-xs text-white/80 font-medium flex-1">
                            Mini games, tantangan, dan achievement buat isi waktu luangmu.
                        </p>
                        <span class="z-1 relative mt-6 inline-flex items-center gap-2 font-extrabold">
                            Coming Soon <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        {{-- BADGE --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="reveal">
                <div class="relative overflow-hidden bg-gradient-to-br from-[#4647AE] to-[#112E81] border-2 border-white rounded-[3rem] extrude ex-b p-8 sm:p-14">
                    <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full border-[30px] border-white/10"></div>
                    <div class="relative grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-white text-[#090E39] text-sm font-bold rotate-2 mb-4">Pencapaian</span>
                            <h2 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">Tiap event, ada badge baru.</h2>
                            <p class="text-white/80 mt-4 text-lg max-w-md">Datang, ikut lomba, ajak teman. Semua aksimu dihitung dan jadi koleksi.</p>
                            <a href="{{ route('register') }}" data-magnetic
                               class="btn-3d ex-k inline-flex items-center gap-2 mt-8 bg-white text-[#090E39] px-7 py-3.5 rounded-2xl font-extrabold">
                                Mulai Koleksi Badge →
                            </a>
                        </div>
                        <div class="grid grid-cols-3 gap-4 sm:gap-6">
                            @php
                                $badges = [
                                    ['flame', 'Hot Starter', 'bg-white text-[#090E39]', 'Daftar & ikut event pertamamu'],
                                    ['headphones', 'Music Lover', 'bg-[#4382DF] text-[#090E39]', 'Hadir di 3 event musik'],
                                    ['gamepad', 'Gamer Pro', 'bg-[#090E39] text-white', 'Menangkan turnamen game'],
                                    ['shirt', 'Cosplayer', 'bg-[#4382DF] text-[#090E39]', 'Ikut parade cosplay'],
                                    ['palette', 'Art Hunter', 'bg-white text-[#090E39]', 'Kunjungi pameran seni'],
                                    ['crown', 'Legend', 'bg-[#090E39] text-white', 'Kumpulin semua badge'],
                                ];
                            @endphp
                            @foreach ($badges as $i => [$ico, $label, $front, $desc])
                                <div class="reveal" data-delay="{{ $i * 80 }}">
                                    <div class="flip aspect-square" tabindex="0">
                                        <div class="flip-inner">
                                            <div class="flip-face {{ $front }} border-2 border-white rounded-3xl p-2 flex flex-col items-center justify-center text-center extrude-sm ex-k">
                                                {!! $icon($ico, 'w-9 h-9 sm:w-12 sm:h-12') !!}
                                                <span class="font-display font-extrabold text-[11px] sm:text-sm leading-tight mt-2">{{ $label }}</span>
                                            </div>
                                            <div class="flip-face flip-back bg-[#090E39] text-white border-2 border-[#4382DF] rounded-3xl p-2 sm:p-3 flex flex-col items-center justify-center text-center">
                                                <span class="text-[#4382DF]">{!! $icon('lock', 'w-4 h-4 sm:w-5 sm:h-5') !!}</span>
                                                <span class="font-semibold text-[10px] sm:text-xs leading-snug mt-1.5">{{ $desc }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="reveal text-center mb-12">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#4382DF] text-[#090E39] text-sm font-bold rotate-2 mb-4">FAQ</span>
                <h2 class="font-display text-4xl sm:text-5xl font-extrabold tracking-tight">Biasanya ditanyain</h2>
            </div>
            @php
                $faqs = [
                    ['Apa itu DIGANOVA?', 'Platform untuk menemukan dan mengikuti berbagai event seperti musik, cosplay, seni, dan turnamen game, lengkap dengan e-ticket dan sistem pencapaian.'],
                    ['Harus daftar dulu buat lihat event?', 'Iya. Detail event dan tiket hanya bisa diakses member, jadi kamu perlu login atau membuat akun dulu.'],
                    ['Tiketnya dalam bentuk apa?', 'E-ticket digital yang tersimpan di akunmu, jadi nggak perlu cetak dan nggak takut hilang.'],
                    ['Badge itu buat apa?', 'Badge adalah penanda pencapaianmu di setiap event. Kumpulin sebanyak mungkin dan pamerin di profilmu.'],
                ];
            @endphp
            <div class="space-y-5">
                @foreach ($faqs as $i => [$q, $a])
                    <div class="reveal" data-delay="{{ $i * 80 }}">
                        <details class="faq-item bg-[#112E81]/60 backdrop-blur border-2 border-white/20 rounded-2xl {{ $i % 2 ? 'rotate-[.4deg]' : '-rotate-[.4deg]' }}">
                            <summary class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-display font-bold text-lg">
                                <span>{{ $q }}</span>
                                <span class="faq-plus shrink-0 w-9 h-9 grid place-items-center rounded-full bg-white/10 text-2xl leading-none transition-all">+</span>
                            </summary>
                            <p class="px-6 pb-6 text-white/70">{{ $a }}</p>
                        </details>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="reveal">
                <div data-parallax class="relative overflow-hidden bg-gradient-to-br from-[#4647AE] to-[#112E81] border-2 border-white rounded-[3rem] extrude ex-b p-10 sm:p-16 text-center">
                    <div data-depth="20" class="absolute -top-10 -left-10"><div class="orb orb-lg breathe w-40 h-40"></div></div>
                    <div data-depth="30" class="absolute -bottom-12 -right-8"><div class="orb orb-lg orb-w floaty-2 w-32 h-32"></div></div>
                    <h2 class="relative font-display font-extrabold text-4xl sm:text-6xl tracking-tight leading-none">
                        Siap bikin<br>momen serumu?
                    </h2>
                    <p class="relative mt-5 text-lg font-medium text-white/80 max-w-md mx-auto">
                        Daftar gratis, temukan event pertamamu, dan mulai kumpulin badge hari ini.
                    </p>
                    <div class="relative mt-10 flex flex-col sm:flex-row gap-5 justify-center">
                        <a href="{{ route('register') }}" data-magnetic
                           class="btn-3d shine relative overflow-hidden ex-k bg-white text-[#090E39] px-9 py-4 rounded-2xl font-extrabold text-lg border-2 border-white">
                            Daftar Sekarang →
                        </a>
                        <a href="{{ route('event.events.index') }}" data-magnetic
                           class="btn-3d ex-k bg-[#090E39] text-white px-9 py-4 rounded-2xl font-extrabold text-lg border-2 border-white">
                            Lihat Events
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="relative z-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-white/50">
            <div class="flex items-center gap-2">
                <span class="shrink-0">{!! $logo('w-8 h-8') !!}</span>
                <span class="font-display font-extrabold text-white text-lg">DIGANOVA</span>
                <span>· Plan It. Join It. Enjoy It.</span>
            </div>
            <p>© {{ date('Y') }} DIGANOVA · Event Domain — diganova.test</p>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script>
    /* ========== CURTAIN ========== */
    (function () {
        const root    = document.documentElement;
        const curtain = document.getElementById('curtain');
        const reduce  = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!curtain || reduce) {
            root.classList.add('ready');
            curtain?.classList.add('is-done');
            return;
        }
        let opened = false;
        const t0 = performance.now();
        const MIN_LOAD = 1200, REVEAL_READY = 200, HIDE_AFTER = 810, SAFETY_OPEN = 3500;
        curtain.classList.add('countdown-armed');

        const open = () => {
            if (opened) return;
            opened = true;
            curtain.classList.remove('is-covering');
            setTimeout(() => root.classList.add('ready'), REVEAL_READY);
            setTimeout(() => curtain.classList.add('is-done'), HIDE_AFTER);
        };
        const start = () => setTimeout(open, Math.max(0, MIN_LOAD - (performance.now() - t0)));
        if (document.readyState === 'complete') start();
        else window.addEventListener('load', start, { once: true });
        setTimeout(open, SAFETY_OPEN);
        const skip = () => { if (!opened) open(); };
        window.addEventListener('wheel', skip, { once: true, passive: true });
        window.addEventListener('touchstart', skip, { once: true, passive: true });
        window.addEventListener('keydown', skip, { once: true });
        curtain.addEventListener('click', (e) => { e.preventDefault(); skip(); });
    })();

    /* ========== MAIN ========== */
    (function () {
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        const root = document.documentElement;

        // Reveal
        const reveals = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            reveals.forEach(e => e.classList.add('in'));
        } else {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(en => {
                    if (!en.isIntersecting) return;
                    const d = en.target.dataset.delay;
                    if (d) en.target.style.transitionDelay = d + 'ms';
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                });
            }, { threshold: 0.12 });
            reveals.forEach(e => io.observe(e));
        }

        // Progress
        const bar = document.getElementById('progress');
        const onScrollProgress = () => {
            const h = root.scrollHeight - window.innerHeight;
            bar.style.transform = 'scaleX(' + (h > 0 ? window.scrollY / h : 0) + ')';
        };
        window.addEventListener('scroll', onScrollProgress, { passive: true });
        onScrollProgress();

        // Glow
        const glow = document.getElementById('glow');
        window.addEventListener('mousemove', (e) => {
            glow.style.setProperty('--mx', e.clientX + 'px');
            glow.style.setProperty('--my', e.clientY + 'px');
        }, { passive: true });

        // Tilt
        if (!reduce && canHover) {
            document.querySelectorAll('[data-tilt]').forEach(el => {
                const rot = parseFloat(el.dataset.rot || 0);
                const base = 'rotate(' + rot + 'deg)';
                el.style.transform = base;
                const glare = document.createElement('span');
                glare.className = 'glare';
                el.appendChild(glare);
                el.addEventListener('mousemove', (e) => {
                    const r = el.getBoundingClientRect();
                    const x = (e.clientX - r.left) / r.width - 0.5;
                    const y = (e.clientY - r.top) / r.height - 0.5;
                    el.style.transform = 'perspective(800px) ' + base + ' rotateX(' + (-y * 10).toFixed(2) + 'deg) rotateY(' + (x * 10).toFixed(2) + 'deg) translateY(-4px)';
                    glare.style.setProperty('--gx', ((x + 0.5) * 100).toFixed(1) + '%');
                    glare.style.setProperty('--gy', ((y + 0.5) * 100).toFixed(1) + '%');
                });
                el.addEventListener('mouseleave', () => { el.style.transform = base; });
            });

            // Parallax
            document.querySelectorAll('[data-parallax]').forEach(scene => {
                const items = [...scene.querySelectorAll('[data-depth]')];
                const area = scene.closest('section') || scene;
                let tx = 0, ty = 0, cx = 0, cy = 0;
                area.addEventListener('mousemove', (e) => {
                    const r = area.getBoundingClientRect();
                    tx = (e.clientX - r.left) / r.width - 0.5;
                    ty = (e.clientY - r.top) / r.height - 0.5;
                });
                area.addEventListener('mouseleave', () => { tx = 0; ty = 0; });
                (function loop() {
                    cx += (tx - cx) * 0.08;
                    cy += (ty - cy) * 0.08;
                    items.forEach(el => {
                        const d = +el.dataset.depth;
                        el.style.transform = 'translate3d(' + (-cx * d).toFixed(2) + 'px,' + (-cy * d).toFixed(2) + 'px,0)';
                    });
                    requestAnimationFrame(loop);
                })();
            });

            // Magnetic
            document.querySelectorAll('[data-magnetic]').forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const r = btn.getBoundingClientRect();
                    btn.style.setProperty('--tx', (((e.clientX - r.left) / r.width - 0.5) * 12).toFixed(1) + 'px');
                    btn.style.setProperty('--ty', (((e.clientY - r.top) / r.height - 0.5) * 10).toFixed(1) + 'px');
                });
                btn.addEventListener('mouseleave', () => {
                    btn.style.setProperty('--tx', '0px');
                    btn.style.setProperty('--ty', '0px');
                });
            });
        }
    })();
    </script>
</body>
</html>