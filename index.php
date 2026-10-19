<?php
// Share-preview (Open Graph) data. Room code is sanitized; nothing else from the URL is echoed.
$kl_room = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)($_GET['room'] ?? '')));
if (!preg_match('/^[A-Z0-9]{4,8}$/', $kl_room)) { $kl_room = ''; }
$kl_listen = (($_GET['listen'] ?? '') === '1');
$kl_path = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$kl_dir  = substr($kl_path, 0, (int)strrpos($kl_path, '/') + 1);
$kl_base = 'https://kalugar.com';
$kl_url  = $kl_base . $kl_path . ($kl_room ? '?room=' . $kl_room . ($kl_listen ? '&listen=1' : '') : '');
$kl_img  = $kl_base . $kl_dir . 'kantahan-share.png';
$kl_title = $kl_listen ? '🎧 Live ngayon sa Kantahan Live!' : '🎤 Kantahan na! Sumali sa Kantahan Live';
$kl_desc  = $kl_listen
  ? 'I-tap ang link para marinig agad ang kumakanta nang live. Walang kailangang i-install.'
  : 'Online karaoke kasama ang mga kaibigan: kumanta, makinig, at mag-cheer nang live.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Kantahan Live! - Web Studio</title>
  <meta property="og:site_name" content="Kantahan Live!">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($kl_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($kl_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url" content="<?= htmlspecialchars($kl_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($kl_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($kl_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($kl_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($kl_img, ENT_QUOTES, 'UTF-8') ?>">
  <style>
    :root {
      --bg: #030508;
      --card-bg: #131c2e;
      --border: #22314e;
      --accent: #10b981;
      --accent-hover: #059669;
      --singer-red: #ef4444;
      --text: #f1f5f9;
      --muted: #94a3b8;
      --gold: #f59e0b;
      --blue: #38bdf8;
    }

    * { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }

    html, body {
      width: 100%;
      overflow-x: hidden;
      background: var(--bg);
      color: var(--text);
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    #wakeBanner {
      display: none;
      background: #1e1b4b;
      border-bottom: 1px solid #6366f1;
      color: #c7d2fe;
      font-size: 13px;
      padding: 10px 16px;
      text-align: center;
      font-weight: 500;
    }

    /* TOP HEADER: Clean single-row on Desktop */
    header {
      background: #0d1525;
      border-bottom: 2px solid var(--border);
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      width: 100%;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 800;
      font-size: 20px;
      letter-spacing: 0.3px;
      color: var(--accent);
      white-space: nowrap;
      flex-shrink: 0;
    }
    .brand .brand-icon { font-size: 1.15em; }

    .pills { 
      display: flex; 
      align-items: center; 
      gap: 8px; 
      flex-wrap: nowrap;
      justify-content: flex-end;
    }

    .badge {
      font-size: 11px;
      padding: 4px 10px;
      border-radius: 999px;
      background: var(--card-bg);
      border: 1px solid var(--border);
      color: var(--muted);
      font-weight: 600;
      white-space: nowrap;
    }
    .badge.live { color: #34d399; border-color: rgba(52, 211, 153, 0.3); }
    .badge.waking { color: var(--gold); border-color: rgba(245, 158, 11, 0.4); }

    .btn-badge {
      background: var(--card-bg);
      border: 1px solid var(--border);
      color: var(--muted);
      padding: 5px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }
    .btn-badge.admin-on { background: #78350f; color: #fde68a; border-color: var(--gold); }

    .container {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      flex: 1;
    }

    .main-grid {
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 16px;
      align-items: start;
      width: 100%;
    }

    @media (max-width: 860px) {
      .container { padding: 12px; }
      .main-grid { grid-template-columns: minmax(0, 1fr); }
    }

    .main-grid > * { min-width: 0; }

    .card {
      min-width: 0;
      background: var(--card-bg);
      border: 2px solid var(--border);
      border-radius: 12px;
      padding: 14px 16px;
      display: flex;
      flex-direction: column;
      width: 100%;
    }

    /* Kulay ng bawat section: para madaling makita ang grupo sa isang sulyap */
    .card.tone-stage { --tone: 251,113,133; } /* rose  - Live Stage & Queue */
    .card.tone-mixer { --tone: 56,189,248; }  /* sky   - DSP Karaoke Mixer */
    .card.tone-chat  { --tone: 167,139,250; } /* violet - Live Chat & Cheers */
    .card[class*="tone-"] {
      border-color: rgba(var(--tone), 0.28);
      border-top: 4px solid rgba(var(--tone), 0.9);
      background: linear-gradient(180deg, rgba(var(--tone), 0.09), rgba(var(--tone), 0) 130px), var(--card-bg);
    }
    .card[class*="tone-"] .card-title { color: rgb(var(--tone)); }

    .card-title {
      font-size: 12px;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.5px;
      color: var(--muted);
      margin-bottom: 12px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
    }

    /* DESKTOP STAGE BAR: Centered Timer */
    .stage-title-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      gap: 12px;
    }

    .stage-title-info {
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
      min-width: 160px;
    }

    .stage-timer-center {
      font-variant-numeric: tabular-nums;
      color: var(--singer-red);
      font-weight: 800;
      font-size: 15px;
      letter-spacing: 1.5px;
      text-align: right;
      flex: 1;
    }

    .stage-action-area {
      width: 100%;
      display: flex;
      gap: 8px;
      margin-bottom: 12px;
    }
    .stage-action-area .btn-action {
      flex: 1;
      justify-content: center;
      padding: 9px 12px;
      font-size: 12px;
    }
    .btn-action.talk-btn {
      background: #0a1120;
      color: var(--blue);
      border: 1px solid var(--blue);
      touch-action: none;
      user-select: none;
      -webkit-user-select: none;
      -webkit-touch-callout: none;
    }
    .btn-action.talk-btn.talking {
      background: var(--singer-red);
      border-color: var(--singer-red);
      color: #fff;
    }

    .mixer-header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      cursor: pointer;
    }
	
    /* Embedded Karaoke Stage Screen */
    .yt-karaoke-screen {
      display: none;
      width: 100%;
      height: 215px;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 12px;
      border: 1px solid var(--border);
      background: #000;
      position: relative;
      z-index: 10;
      pointer-events: auto !important;
    }
    .yt-karaoke-screen iframe {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
      pointer-events: auto !important;
    }
    /* Desktop lang: mas mataas na video player (mobile: 215px pa rin) */
    @media (min-width: 861px) {
      .yt-karaoke-screen { height: 360px; }
    }

    /* Minimalist Song Search Panel */
    .song-search-box {
      min-width: 0;
      max-width: 100%;
      background: #0a1120;
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 12px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .song-search-input-wrap {
      display: flex;
      gap: 6px;
    }
    .song-search-results {
      display: flex;
      flex-direction: column;
      gap: 6px;
      max-height: 170px;
      overflow-y: auto;
    }
    .song-result-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 8px;
      background: #131c2e;
      border-radius: 6px;
      border: 1px solid rgba(255,255,255,0.04);
      cursor: pointer;
      transition: 0.15s;
    }
    .song-result-item:hover {
      background: #1e293b;
      border-color: var(--accent);
    }
    .song-result-item img {
      width: 44px;
      height: 33px;
      border-radius: 4px;
      object-fit: cover;
      flex-shrink: 0;
    }
    .song-result-item {
      min-width: 0;
      max-width: 100%;
    }
    .song-result-item span {
      font-size: 11.5px;
      color: var(--text);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex: 1;
      min-width: 0;
    }
    .song-result-item span.play-tag {
      flex: 0 0 auto;
      overflow: visible;
    }

    .visualizer-box {
      background: #090e18;
      border-radius: 8px;
      height: 75px;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.05);
      margin-bottom: 12px;
      width: 100%;
    }
    canvas { width: 100%; height: 100%; display: block; }

    .user-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      overflow-y: auto;
      max-height: 480px;
      width: 100%;
    }

    .user-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 14px;
      border-radius: 8px;
      background: #0a1120;
      border: 1px solid rgba(255,255,255,0.05);
      font-size: 13px;
      transition: 0.2s;
      gap: 10px;
      width: 100%;
    }

    .user-row.singer-active {
      border: 1.5px solid var(--singer-red);
      background: rgba(239, 68, 68, 0.08);
      box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
    }

    .user-row.next-inline {
      border: 1px solid rgba(16, 185, 129, 0.4);
      background: rgba(16, 185, 129, 0.04);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 0;
      flex: 1;
    }

    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 13px;
      background: #1e293b;
      color: var(--blue);
      flex-shrink: 0;
    }
    .user-row.singer-active .avatar {
      background: var(--singer-red);
      color: #fff;
    }
    .avatar.has-img { background: #0a1120; padding: 0; }
    .avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar.me { cursor: pointer; }
    .user-row.singer-active .avatar.has-img { box-shadow: 0 0 0 2px var(--singer-red); }

    /* Profile popup */
    .profile-card { position: relative; align-items: center; gap: 12px; }
    .profile-close {
      position: absolute; top: 10px; right: 12px;
      background: none; border: none; color: var(--muted);
      font-size: 16px; cursor: pointer; padding: 4px;
    }
    .profile-avatar {
      width: 88px; height: 88px; border-radius: 18px;
      background: #1e293b; color: var(--blue);
      display: flex; align-items: center; justify-content: center;
      font-size: 34px; font-weight: 800;
      overflow: hidden; cursor: pointer; position: relative;
      border: 2px solid var(--border);
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .profile-avatar .cam {
      position: absolute; right: 4px; bottom: 4px; font-size: 12px;
      background: rgba(9,13,22,0.85); border-radius: 6px; padding: 1px 4px;
    }
    .profile-links { font-size: 11px; color: var(--muted); }
    .profile-links a { color: var(--blue); cursor: pointer; }
    .profile-msg { font-size: 11px; min-height: 14px; color: var(--muted); line-height: 1.4; }
    .profile-msg.err { color: var(--singer-red); }
    .profile-msg.ok { color: var(--accent); }

    .queue-pos {
      background: #312e81;
      color: #c7d2fe;
      font-weight: 700;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 4px;
    }

    .user-actions {
      display: flex;
      align-items: center;
      gap: 6px;
      flex-shrink: 0;
    }

    .btn-action {
      border: none;
      font-size: 11px;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: 0.15s;
    }
    .btn-action.start-sing {
      background: var(--accent);
      color: #fff;
    }
    .btn-action.start-sing:hover { background: var(--accent-hover); }
    .btn-action.stop-sing {
      background: var(--singer-red);
      color: #fff;
    }
    .btn-action.admin-tool {
      background: #1e293b;
      border: 1px solid var(--border);
      color: var(--muted);
    }
    .btn-action.admin-tool:hover { color: #fff; background: #334155; }

    #mixerPanel {
      display: none;
      flex-direction: column;
      gap: 12px;
      width: 100%;
    }

    .controls-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      font-size: 12px;
      width: 100%;
    }
    .control-item { 
      display: flex; 
      flex-direction: column; 
      gap: 6px; 
      width: 100%;
    }
    .control-label { display: flex; justify-content: space-between; color: var(--muted); }
    input[type=range] {
      accent-color: var(--accent);
      height: 6px;
      background: #090e18;
      border-radius: 4px;
      width: 100%;
      display: block;
    }

    .audio-aux {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 6px;
      padding-top: 10px;
      border-top: 1px solid rgba(255,255,255,0.05);
      width: 100%;
    }

    .audio-aux .btn-action {
      white-space: nowrap;
      padding: 4px 10px;
      font-size: 14px; /* kapantay ng icons sa Live Chat & Cheers */
      flex: 0 0 auto;
    }

    .chat-box { 
      display: flex; 
      flex-direction: column; 
      height: 100%; 
      width: 100%;
    }
    .chat-messages {
      flex: 1;
      overflow-y: auto;
      max-height: 280px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      font-size: 13px;
    }
    .msg-user { line-height: 1.4; }
    .msg-author { font-weight: 700; color: var(--blue); }
    .chat-input-bar { 
      display: flex; 
      gap: 8px; 
      margin-top: 12px; 
      width: 100%;
    }
    input[type=text] {
      flex: 1;
      background: #090e18;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 12px 12px;
      color: white;
      outline: none;
      font-size: 13px;
      min-width: 0;
    }
    .reactions { 
      display: flex; 
      gap: 6px; 
      margin-bottom: 8px; 
      flex-wrap: wrap;
    }
    .btn-react {
      background: #0a1120;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 4px 8px;
      cursor: pointer;
      font-size: 14px;
    }

    .modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 16px;
    }
    .modal-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      padding: 22px 24px 24px 24px;
      border-radius: 12px;
      max-width: 360px;
      width: 100%;
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    /* LED Audio Peak Lobby Equalizer Animation */
    .lobby-eq {
      display: flex;
      justify-content: center;
      align-items: flex-end;
      gap: 6px;
      height: 38px;
      margin-top: 6px;
      margin-bottom: 16px;
    }
    .lobby-eq span {
      display: block;
      width: 7px;
      background: var(--accent);
      border-radius: 4px;
      animation: eqBounce 1.2s ease-in-out infinite alternate;
    }
    .lobby-eq span:nth-child(1) { height: 40%; animation-delay: 0.1s; }
    .lobby-eq span:nth-child(2) { height: 80%; animation-delay: 0.4s; background: var(--blue); }
    .lobby-eq span:nth-child(3) { height: 60%; animation-delay: 0.2s; }
    .lobby-eq span:nth-child(4) { height: 100%; animation-delay: 0.5s; background: var(--gold); }
    .lobby-eq span:nth-child(5) { height: 50%; animation-delay: 0.3s; background: var(--singer-red); }

    @keyframes eqBounce {
      0% { transform: scaleY(0.3); opacity: 0.5; }
      100% { transform: scaleY(1); opacity: 1; }
    }

    /* ===== MULTI-ROOM ===== */
    input[type=password] {
      flex: 1; background: #090e18; border: 1px solid var(--border); border-radius: 6px;
      padding: 8px 12px; color: white; outline: none; font-size: 13px; min-width: 0;
    }
    .lobby-btn { justify-content: center; padding: 12px; font-size: 14px; width: 100%; }
    .lobby-view { display: none; flex-direction: column; gap: 10px; }
    .lobby-msg { font-size: 12px; color: #fca5a5; min-height: 14px; }
    .lobby-link { font-size: 12px; color: var(--muted); cursor: pointer; text-decoration: underline; }
    .rooms-card { max-width: 420px; text-align: left; }
    .rooms-list { display: flex; flex-direction: column; gap: 8px; max-height: 50vh; overflow-y: auto; }
    .room-row {
      display: flex; align-items: center; gap: 10px; background: #090e18;
      border: 1px solid var(--border); border-radius: 8px; padding: 10px 12px;
    }
    .room-row .rn { flex: 1; min-width: 0; font-size: 13px; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .room-row .rn small { display: block; color: var(--muted); font-weight: 400; font-size: 11px; letter-spacing: 1px; }
    .room-row .rc { font-size: 12px; color: var(--muted); white-space: nowrap; }
    .rooms-empty { color: var(--muted); font-size: 12px; text-align: center; padding: 14px 0; }
    .room-code { font-size: 28px; font-weight: 800; letter-spacing: 4px; color: var(--accent); }

    /* PURE MOBILE ONLY OVERRIDES */
    @media (max-width: 600px) {
      header {
        padding: 10px 12px;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
      }
      .brand { font-size: 20px; }
      .pills {
        justify-content: space-between;
        width: 100%;
      }
      .badge.hifi-badge { display: none; }

      .stage-title-bar {
        flex-wrap: wrap;
        gap: 8px;
      }
      .stage-title-info {
        min-width: unset;
      }
      .stage-timer-center {
        text-align: right;
        flex: 1;
      }
      .stage-action-area {
        width: 100%;
        min-width: unset;
      }
      .btn-action.hand-btn-mobile, .btn-action.talk-btn {
        flex: 1;
        justify-content: center;
        padding: 8px 12px;
        font-size: 12px;
      }

      .user-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0px;
      }
      .user-actions {
        width: 100%;
      }
      .user-actions .btn-action {
        width: 100%;
        justify-content: center;
        padding: 8px 12px;
		margin-top: 8px;
        font-size: 12px;
      }
    }

    /* ===== Listen popup (share link: ?room=CODE&listen=1) ===== */
    #listenModal { z-index: 8000; }
    #listenModal .modal-card { gap: 12px; }
    .listen-room { font-size: 12px; color: var(--muted); }
    .listen-now { font-size: 15px; font-weight: 700; min-height: 22px; }
    .listen-now.live { color: var(--singer-red); }
    .listen-big {
      background: var(--accent); color: #fff; border: 0; border-radius: 999px;
      font-size: 17px; font-weight: 700; padding: 16px 20px; cursor: pointer; width: 100%;
    }
    .listen-big:disabled { opacity: .5; cursor: default; }
    .listen-big.on { background: transparent; border: 1px solid var(--accent); color: var(--accent); cursor: default; }
    .listen-row { display: flex; gap: 8px; justify-content: center; }
    #listenEq { display: none; margin-bottom: 0; }
    #listenEq.on { display: flex; }
    /* ===== Lyrics box ===== */
    .card.tone-lyrics { --tone: 167,139,250; }
    .lyr-title { font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--muted); }
    .lyr-text {
      height: 220px; overflow-y: auto; white-space: pre-wrap; text-align: center;
      font-size: 16px; line-height: 1.7; padding: 10px 8px; border-radius: 8px;
      background: #0a1120; border: 1px solid rgba(255,255,255,0.06);
    }
    .lyr-hit {
      display: block; width: 100%; text-align: left; margin-top: 6px; padding: 8px 10px;
      font-size: 12px; color: var(--text, #e2e8f0); background: #0a1120;
      border: 1px solid var(--border); border-radius: 8px; cursor: pointer;
    }
    #listenModal .modal-card { max-height: 92vh; overflow-y: auto; }
    #listenLyrics { height: 180px; font-size: 15px; }
  </style>
</head>
<body>

  <div id="wakeBanner">
    ⏳ <strong>Waking up cloud relay...</strong> Please allow 15-25 seconds for the free instance to spin up.
  </div>

  <header>
    <div class="brand"><span class="brand-icon">🎤</span> Kantahan Live! <button class="btn-badge" id="roomBtn" style="display:none; margin-left:6px; font-size:11px; vertical-align:middle;" onclick="openRoomInfo()">🏠</button></div>
    <div class="pills">
      <span class="badge live" id="connBadge">● Connected (Live)</span>
      <span class="badge hifi-badge">48kHz Hi-Fi Relay</span>
      <button class="btn-badge" id="changeNameBtn">👤 Profile</button>
      <button class="btn-badge" id="adminToggleBtn">👑 Admin: OFF</button>
    </div>
  </header>

  <div class="container">
    <div class="main-grid">

      <!-- LEFT: Live Stage & Queue -->
      <div class="card tone-stage">
        <div class="card-title">
          <div class="stage-title-bar">
            <div class="stage-title-info">
              <span>Live Stage & Queue</span>
              <span style="color:var(--muted); font-weight:normal;">(<span id="userCount">1</span>)</span>
              <span id="listenBadge" title="Nakikinig mula sa shared link" style="display:none; margin-left:8px; font-size:12px; font-weight:700; padding:2px 8px; border-radius:999px; border:1px solid var(--border); color:var(--muted);">🎧 <span id="listenCount">0</span></span>
            </div>

            <div class="stage-timer-center">
              <span id="activeTimer">00:00</span>
            </div>
          </div>
        </div>

        <div class="visualizer-box">
          <canvas id="meterCanvas"></canvas>
        </div>

        <div class="stage-action-area">
          <button class="btn-action stop-sing" id="stageEndBtn" style="display:none;" onclick="endStageFromBar()">🔓 End Stage</button>
          <button class="btn-action talk-btn" id="talkBtn" style="display:none;" title="Pindutin at i-hold para magsalita">🗣️ Talk</button>
          <button class="btn-action hand-btn-mobile" id="handBtn" style="background:#1e293b; color:var(--accent); border:1px solid var(--accent);">✋ Raise Hand</button>
        </div>
		
        <!-- Embedded Video Player Screen -->
        <div class="yt-karaoke-screen" id="ytScreen">
          <iframe id="ytPlayer" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen playsinline></iframe>
        </div>

        <!-- Minimalist Song Finder Drawer -->
        <div class="song-search-box">
          <div class="song-search-input-wrap">
            <input type="text" id="ytSearchInput" placeholder="🔎 Search Song Title..." style="font-size:12px; padding:6px 10px;" onkeydown="if(event.key==='Enter') searchKaraoke()">
            <button class="btn-action" style="background:var(--accent); color:white; padding:6px 10px;" onclick="searchKaraoke()">Search</button>
            <button class="btn-action" id="playPauseVideoBtn" style="display:none; background:#2563eb; color:white; padding:6px 10px;" onclick="toggleKaraokePlayback()">▶ Play</button>
            <button class="btn-action admin-tool" id="closeVideoBtn" style="display:none; padding:6px 10px;" onclick="stopKaraokeVideo()">✕</button>
          </div>
          <div class="song-search-results" id="ytResults"></div>
        </div>

        <div class="user-list" id="userList"></div>
      </div>

      <!-- RIGHT: DSP Mixer & Live Chat -->
      <div style="display:flex; flex-direction:column; gap:16px;">
	  
        <!-- Chat -->
        <div class="card tone-chat" style="flex:1;">
          <div class="card-title">
            <span>Live Chat & Cheers</span>
            <span style="font-size: 11px; color: var(--muted);">Connected</span>
          </div>

          <div class="chat-box">
            <div class="reactions">
              <button class="btn-react" onclick="sendReaction('👏')">👏</button>
              <button class="btn-react" onclick="sendReaction('🎤')">🎤</button>
              <button class="btn-react" onclick="sendReaction('🔥')">🔥</button>
              <button class="btn-react" onclick="sendReaction('❤️')">❤️</button>
              <button class="btn-react" onclick="sendReaction('🍻')">🍻</button>
            </div>

            <div class="chat-messages" id="chatMsgs" style="height: 120px; max-height: 120px; overflow-y: auto;">
              <div class="msg-user"><span class="msg-author">System:</span> Welcome to Kantahan Live!</div>
            </div>

            <div class="chat-input-bar">
              <input type="text" id="chatInput" autocomplete="one-time-code" placeholder="Type a message..." onkeydown="if(event.key==='Enter') sendChat()">
              <button class="btn-action" style="background:var(--accent); color:white;" onclick="sendChat()">Send</button>
            </div>
          </div>
        </div>
        
<!-- Lyrics -->
<div class="card tone-lyrics" id="lyricsCard" style="display:none; gap:10px;">
  <div class="card-title" style="margin-bottom:0;">
    <div class="mixer-header-bar">
      <span>📝 Lyrics</span>
      <button class="btn-action admin-tool" id="lyricsClearBtn" style="display:none; font-size:10px; padding:4px 8px;" onclick="clearLyrics()">✕ Clear</button>
    </div>
  </div>
  <div id="lyricsTools" style="display:none;">
    <div class="song-search-input-wrap">
      <input type="text" id="lyricsQ" placeholder="🔎 Song title + artist..." style="font-size:12px; padding:6px 10px;" onkeydown="if(event.key==='Enter') searchLyrics()">
      <button class="btn-action" style="background:var(--accent); color:white; padding:6px 10px;" onclick="searchLyrics()">Search</button>
    </div>
    <div id="lyricsResults"></div>
  </div>
  <div class="lyr-title" id="lyricsTitle"></div>
  <div class="lyr-text" id="lyricsBox"></div>
</div>

<!-- DSP Mixer -->
<div class="card tone-mixer">
  <div class="card-title" style="margin-bottom:0; cursor:pointer;" onclick="var p = document.getElementById('mixerContent'); var b = document.getElementById('mixerBtn'); if(p.style.display==='none'){ p.style.display='block'; b.textContent='Hide ▲'; } else { p.style.display='none'; b.textContent='Show ▼'; }">
    <div class="mixer-header-bar">
      <span>🎚️ DSP Karaoke Mixer</span>
      <button class="btn-action admin-tool" id="mixerBtn" style="font-size:10px; padding:4px 8px;">Show ▼</button>
    </div>
  </div>

  <!-- Isang buong container para sa sliders at buttons (Naka-hide na default) -->
  <div id="mixerContent" style="display: none; padding-top: 14px;">
    <div class="controls-grid">
      <div class="control-item">
        <div class="control-label"><span>Mic Gain</span><span id="vocalVal">90%</span></div>
        <input type="range" id="vocalGain" min="0" max="2" step="0.05" value="0.9">
      </div>
      <div class="control-item">
        <div class="control-label"><span>Music Gain</span><span id="musicVal">75%</span></div>
        <input type="range" id="musicGain" min="0" max="2" step="0.05" value="0.75">
      </div>
      <div class="control-item">
        <div class="control-label"><span>Echo Effect (Wet)</span><span id="echoVal">30%</span></div>
        <input type="range" id="echoGain" min="0" max="1" step="0.01" value="0.30">
      </div>
      <div class="control-item">
        <div class="control-label"><span>Echo Repeats</span><span id="repeatVal">30%</span></div>
        <input type="range" id="echoRepeat" min="0" max="0.85" step="0.02" value="0.30">
      </div>
    </div>

    <div class="audio-aux" style="margin-top: 12px;">
      <button class="btn-action admin-tool" id="muteStageBtn" onclick="event.stopPropagation(); toggleStageMute()" title="Mute stage (local lang)">🔊</button>
      <button class="btn-action admin-tool" id="soundcheckBtn" onclick="event.stopPropagation(); toggleSoundcheck()" title="Soundcheck: test ng mic, walang ipinapadala">🎙️ Test</button>
      <button class="btn-action admin-tool" id="monitorToggleBtn" onclick="event.stopPropagation(); toggleLocalMonitoring()" title="Monitor: ECHO ONLY (echo lang ang maririnig mo)">🎧 Echo</button>
      <button class="btn-action admin-tool" onclick="event.stopPropagation(); resetStudioDefaults()" title="Reset sa default">↺</button>
    </div>

    <!-- Tip to avoid sound looping sa ilalim ng mga buttons -->
    <div style="font-size: 11px; color: var(--muted); margin-top: 10px; text-align: center;">
      Use headphones to avoid audio feedback.
    </div>
  </div>
</div>		

      </div>

    </div>
  </div>

  <!-- Nickname Modal -->
  <div class="modal-overlay" id="nameModal">
    <div class="modal-card">
      <h3 style="font-size: 16px;">🎤 What's your nickname?</h3>
      <p style="font-size: 12px; color: var(--muted);">Please enter a display name to join the Kantahan room.</p>
      <input type="text" id="nameInput" maxlength="16" placeholder="e.g. Edgie, Sergio, Alex..." style="text-align: center;">
      <div id="nameErr" class="profile-msg err"></div>
      <button class="btn-action" style="background:var(--accent); color:white; padding:10px;" onclick="saveName()">Join Studio</button>
    </div>
  </div>

  <!-- Profile Modal -->
  <div class="modal-overlay" id="profileModal">
    <div class="modal-card profile-card">
      <button class="profile-close" onclick="closeProfile()" title="Close">✕</button>
      <div class="profile-avatar" id="profileAvatar" onclick="document.getElementById('avatarFile').click()" title="Palitan ang photo"></div>
      <input type="file" id="avatarFile" accept="image/*" style="display:none;">
      <div class="profile-links"><a onclick="document.getElementById('avatarFile').click()">Change photo</a> · <a onclick="removeAvatar()">Remove</a></div>
      <input type="text" id="profileName" maxlength="16" placeholder="Nickname" style="text-align:center; width:100%;">
      <div id="profileMsg" class="profile-msg"></div>
      <button class="btn-action" id="profileSaveBtn" style="background:var(--accent); color:white; padding:10px; width:100%; justify-content:center;" onclick="saveProfileName()">Save</button>
    </div>
  </div>

  <!-- Lobby -->
  <div class="modal-overlay" id="lobbyModal" style="z-index:9000;">
    <div class="modal-card">
      <h3 style="font-size:18px; margin-bottom: 4px;">🎤 Kantahan Live!</h3>
      
      <div class="lobby-eq">
        <span></span><span></span><span></span><span></span><span></span>
      </div>

      <div class="lobby-view" id="lobbyHome">
        <button class="btn-action lobby-btn" style="background:var(--accent); color:white;" onclick="lobbyView('Create')">➕ Create Room</button>
        <button class="btn-action lobby-btn" onclick="lobbyView('Join')">🔑 Join with Code</button>
        <button class="btn-action lobby-btn" onclick="openRooms()">📋 Browse Rooms</button>
      </div>

      <div class="lobby-view" id="lobbyCreate">
        <input type="text" id="createName" maxlength="24" placeholder="Room name (optional)" style="text-align:center;">
        <input type="password" id="createPw" maxlength="32" placeholder="Password (blank = public)" style="text-align:center;">
        <button class="btn-action lobby-btn" style="background:var(--accent); color:white;" onclick="createRoom()">Create</button>
        <a class="lobby-link" onclick="lobbyView('Home')">← Back</a>
      </div>

      <div class="lobby-view" id="lobbyJoin">
        <input type="text" id="joinCode" maxlength="8" placeholder="ROOM CODE" style="text-align:center; text-transform:uppercase; letter-spacing:3px;">
        <input type="password" id="joinPw" maxlength="32" placeholder="Password" style="text-align:center; display:none;">
        <button class="btn-action lobby-btn" style="background:var(--accent); color:white;" onclick="joinRoom()">Join</button>
        <a class="lobby-link" onclick="lobbyView('Home')">← Back</a>
      </div>

      <div class="lobby-msg" id="lobbyMsg"></div>
    </div>
  </div>

  <!-- Rooms list -->
  <div class="modal-overlay" id="roomsModal" style="z-index:9100;">
    <div class="modal-card rooms-card">
      <div style="display:flex; align-items:center; justify-content:space-between;">
        <h3 style="font-size:16px;">📋 Rooms</h3>
        <span>
          <a class="lobby-link" onclick="loadRooms()">↻ Refresh</a> &nbsp;
          <a class="lobby-link" onclick="closeRooms()">✕</a>
        </span>
      </div>
      <div class="rooms-list" id="roomsList"></div>
    </div>
  </div>

  <!-- Room info -->
  <div class="modal-overlay" id="roomInfoModal" style="z-index:9100;">
    <div class="modal-card">
      <h3 id="riName" style="font-size:16px;"></h3>
      <div class="room-code" id="riCode"></div>
      <div id="riHost" style="font-size:12px; color:var(--gold); display:none;">👑 Ikaw ang host ng room na ito</div>
      <button class="btn-action lobby-btn" style="background:var(--accent); color:white;" onclick="copyInvite()" id="riCopyBtn">🔗 Copy invite link</button>
      <button class="btn-action lobby-btn" onclick="copyListen()" id="riListenBtn">🎧 Copy listen link</button>
      <button class="btn-action lobby-btn" onclick="shareListen()" id="riShareBtn" style="display:none;">📤 Share listen link</button>
      <button class="btn-action lobby-btn" onclick="leaveRoom()">🏃 Leave room</button>
      <a class="lobby-link" onclick="closeRoomInfo()">Close</a>
    </div>
  </div>

  <!-- Listen popup (para sa shared link) -->
  <div class="modal-overlay" id="listenModal">
    <div class="modal-card">
      <h3 style="font-size:18px;">🎧 Kantahan Live!</h3>
      <div class="listen-room" id="listenRoom">Connecting...</div>
      <div class="visualizer-box" style="margin-bottom:0;"><canvas id="listenMeter"></canvas></div>
      <div class="listen-now" id="listenNow">Connecting...</div>
      <div id="listenLyricsWrap" style="display:none;"><div class="lyr-title" id="listenLyricsTitle"></div><div class="lyr-text" id="listenLyrics"></div></div>
      <button class="listen-big" id="listenBtn" onclick="startListening()" disabled>▶ Tap to listen live</button>
      <div class="listen-row">
        <button class="btn-action admin-tool" id="listenMuteBtn" onclick="toggleStageMute(); refreshListen()" style="display:none;">🔊 Mute</button>
        <button class="btn-action" onclick="closeListen()">🎤 Open full studio</button>
      </div>
    </div>
  </div>

  <script>
    if (location.hostname !== "kalugar.com" && location.hostname !== "localhost" && location.hostname !== "127.0.0.1") {
      document.body.innerHTML = "<h2 style='color:white;text-align:center;margin-top:20%;'>Unauthorized Access. Available only at kalugar.com</h2>";
      throw new Error("Security check failed");
    }

    // I-set ito sa URL ng BAGONG rooms relay (hindi ang original na kantahan-relay)
    const WS_URL = "wss://kantahan-relay-2.onrender.com";

    // Listen mode: ?room=CODE&listen=1  ->  popup player, naka-auto join, tap lang para marinig
    const LISTEN_MODE = new URLSearchParams(location.search).get('listen') === '1';
    let listenOpen = LISTEN_MODE; // nakabukas pa ba ang listen popup
    let listenArmed = false; // hindi magpe-play ang audio hangga't hindi nata-tap (autoplay rule ng browser)

    // User Identity
    let myId = localStorage.getItem("kantahan_userid");
    if (!myId) {
      myId = "usr_" + Math.random().toString(36).substr(2, 6);
      localStorage.setItem("kantahan_userid", myId);
    }

    let myName = localStorage.getItem("kantahan_username") || "";
    const nameModal = document.getElementById('nameModal');
    const nameInput = document.getElementById('nameInput');

    // ---------- Sanitizers (lahat ng galing sa network ay dumadaan dito) ----------
    function esc(str) {
      return String(str).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }
    const ID_RE = /^[A-Za-z0-9_-]{1,40}$/;
    function cleanName(n) {
      return String(n || '').replace(/[\u0000-\u001f<>]/g, '').replace(/\s+/g, ' ').trim().slice(0, 16);
    }
    function cleanAvatar(a) {
      return (typeof a === 'string' && a.length <= 20000 && /^data:image\/jpeg;base64,[A-Za-z0-9+\/=]+$/.test(a)) ? a : null;
    }
    function sanitizeUser(u) {
      if (!u || typeof u !== 'object' || typeof u.id !== 'string' || !ID_RE.test(u.id)) return null;
      const name = cleanName(u.name) || ("Guest_" + u.id.slice(-3));
      return { id: u.id, name: name, letter: name.charAt(0).toUpperCase(), avatar: cleanAvatar(u.avatar), ln: u.ln ? 1 : 0 };
    }

    if (!myName) {
      if (!LISTEN_MODE) nameModal.style.display = "flex";
      myName = "Guest_" + myId.substr(-3);
    }
    let myAvatar = cleanAvatar(localStorage.getItem("kantahan_avatar"));

    function myUser() {
      return { id: myId, name: myName, letter: myName.charAt(0).toUpperCase(), avatar: myAvatar,
               ln: (LISTEN_MODE && listenOpen && listenArmed) ? 1 : 0 };
    }

    let users = { [myId]: myUser() };

    // ---------- Nickname rules ----------
    const NAME_COOLDOWN_MS = 24 * 60 * 60 * 1000; // isang palit kada 24 oras
    const RESERVED_NAMES = ['admin', 'administrator', 'host', 'moderator', 'mod', 'system', 'staff', 'kantahan', 'kantahan live'];

    function isNameSet() { return !myName.startsWith("Guest_"); }

    function nameProblem(raw) {
      const name = cleanName(raw);
      if (name.length < 2) return "Kailangan ng 2 hanggang 16 na character.";
      if (!/^[\p{L}\p{N} _.\-]+$/u.test(name)) return "Letra, numero, space, _ . - lang ang pwede.";
      const low = name.toLowerCase();
      if (RESERVED_NAMES.includes(low) || low.startsWith("guest_")) return "Hindi pwede ang pangalang ito.";
      for (const u of Object.values(users)) {
        if (u.id !== myId && u.name.toLowerCase() === low) return "May gumagamit na ng pangalang iyan ngayon.";
      }
      return null;
    }

    // Bakit hindi pa pwedeng magpalit ng nickname (null = pwede)
    function nameLockReason() {
      if (isAdmin) return null;
      if (queue.indexOf(myId) !== -1 || activeSingerId === myId) {
        return "Hindi pwedeng magpalit ng nickname habang nasa queue o nasa stage.";
      }
      if (isNameSet()) {
        const last = parseInt(localStorage.getItem("kantahan_name_changed") || "0", 10);
        const left = last + NAME_COOLDOWN_MS - Date.now();
        if (left > 0) {
          const hrs = Math.ceil(left / 3600000);
          return "Pwede ulit magpalit ng nickname sa loob ng ~" + hrs + " oras.";
        }
      }
      return null;
    }

    // Ipaalam sa lahat ang bagong pangalan/photo (hindi na kailangan ng bagong "join")
    function broadcastProfile() {
      users[myId] = myUser();
      sendStateMsg({ type: 'USER_UPDATE', user: myUser() });
      renderUI();
    }

    function saveName() {
      const err = document.getElementById('nameErr');
      let name = cleanName(nameInput.value);
      if (name) {
        const prob = nameProblem(name);
        if (prob) { err.textContent = prob; return; }
      } else {
        name = "Guest_" + myId.substr(-3);
      }
      err.textContent = "";
      myName = name;
      localStorage.setItem("kantahan_username", myName);
      nameModal.style.display = "none";
      broadcastProfile();
    }

    // ---------- Profile popup ----------
    const profileModal = document.getElementById('profileModal');

    function setProfileMsg(text, kind) {
      const m = document.getElementById('profileMsg');
      m.textContent = text || "";
      m.className = "profile-msg" + (kind ? " " + kind : "");
    }

    function renderProfileAvatar() {
      const box = document.getElementById('profileAvatar');
      box.innerHTML = "";
      if (myAvatar) {
        const img = document.createElement('img');
        img.src = myAvatar;
        img.alt = "";
        box.appendChild(img);
      } else {
        box.textContent = myName.charAt(0).toUpperCase();
      }
      const cam = document.createElement('span');
      cam.className = "cam";
      cam.textContent = "📷";
      box.appendChild(cam);
    }

    window.openProfile = () => {
      const input = document.getElementById('profileName');
      input.value = isNameSet() ? myName : "";
      const lock = nameLockReason();
      input.disabled = !!lock;
      document.getElementById('profileSaveBtn').style.display = lock ? "none" : "inline-flex";
      setProfileMsg(lock || (isNameSet() ? "Isang palit kada 24 oras." : "Pumili ng nickname para maging ikaw sa studio."), lock ? "" : "");
      renderProfileAvatar();
      profileModal.style.display = "flex";
    };

    window.closeProfile = () => { profileModal.style.display = "none"; };
    profileModal.addEventListener('click', (e) => { if (e.target === profileModal) closeProfile(); });

    window.saveProfileName = () => {
      const name = cleanName(document.getElementById('profileName').value);
      if (name === myName) { setProfileMsg("Walang nabago.", ""); return; }
      const lock = nameLockReason();
      if (lock) { setProfileMsg(lock, "err"); return; }
      const prob = nameProblem(name);
      if (prob) { setProfileMsg(prob, "err"); return; }
      const wasSet = isNameSet();
      myName = name;
      localStorage.setItem("kantahan_username", myName);
      if (wasSet) localStorage.setItem("kantahan_name_changed", String(Date.now()));
      broadcastProfile();
      renderProfileAvatar();
      closeProfile();
    };

    // Photo: auto-crop sa 1:1 at paliitin sa 96x96 JPEG (~3-5KB) para mabilis mag-load
    async function makeAvatarDataUrl(file) {
      if (!file || !/^image\//.test(file.type)) throw new Error("Larawan lang po ang pwede.");
      if (file.size > 15 * 1024 * 1024) throw new Error("Masyadong malaki ang file (max 15MB).");
      let bmp;
      try {
        bmp = await createImageBitmap(file, { imageOrientation: 'from-image' });
      } catch (e) {
        bmp = await new Promise((resolve, reject) => {
          const img = new Image();
          img.onload = () => resolve(img);
          img.onerror = () => reject(new Error("Hindi mabasa ang larawan."));
          img.src = URL.createObjectURL(file);
        });
      }
      const w = bmp.width || bmp.naturalWidth, h = bmp.height || bmp.naturalHeight;
      const side = Math.min(w, h);
      const canvas = document.createElement('canvas');
      canvas.width = canvas.height = 96;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = "#0a1120";
      ctx.fillRect(0, 0, 96, 96);
      ctx.imageSmoothingQuality = "high";
      ctx.drawImage(bmp, (w - side) / 2, (h - side) / 2, side, side, 0, 0, 96, 96);
      let q = 0.82, url = canvas.toDataURL('image/jpeg', q);
      while (url.length > 16000 && q > 0.4) { q -= 0.1; url = canvas.toDataURL('image/jpeg', q); }
      return url;
    }

    document.getElementById('avatarFile').addEventListener('change', async (e) => {
      const file = e.target.files && e.target.files[0];
      e.target.value = "";
      if (!file) return;
      try {
        const url = await makeAvatarDataUrl(file);
        if (!cleanAvatar(url)) throw new Error("Hindi magamit ang larawan. Subukan ang iba.");
        myAvatar = url;
        localStorage.setItem("kantahan_avatar", myAvatar);
        broadcastProfile();
        renderProfileAvatar();
        setProfileMsg("Na-update ang photo.", "ok");
      } catch (err) {
        setProfileMsg(err.message || "May problema sa larawan.", "err");
      }
    });

    window.removeAvatar = () => {
      myAvatar = null;
      localStorage.removeItem("kantahan_avatar");
      broadcastProfile();
      renderProfileAvatar();
      setProfileMsg("Tinanggal ang photo.", "ok");
    };

    document.getElementById('changeNameBtn').onclick = () => openProfile();

    // Admin PIN: hindi na nakasulat ang mismong PIN, hash na lang (PBKDF2-SHA256).
    // Para magpalit ng PIN: sa console ng site, i-run ang  adminHash("bagong-pin").then(console.log)  at i-paste ang resulta sa ADMIN_HASH.
    const ADMIN_SALT = "kantahan-live-v1";
    const ADMIN_HASH = "146ab0f240f7719f658020615a0793eece9bb73bd957c690779be3f101bdf02a";
    let adminFails = 0, adminLockUntil = 0;

    async function pinHash(pin) {
      const enc = new TextEncoder();
      const key = await crypto.subtle.importKey('raw', enc.encode(String(pin)), 'PBKDF2', false, ['deriveBits']);
      const bits = await crypto.subtle.deriveBits(
        { name: 'PBKDF2', salt: enc.encode(ADMIN_SALT), iterations: 100000, hash: 'SHA-256' }, key, 256);
      return Array.from(new Uint8Array(bits)).map(b => b.toString(16).padStart(2, '0')).join('');
    }
    window.adminHash = pinHash;
    let isAdmin = false;
    let queue = [];
    let activeSingerId = null;
    let activeSingerName = null;
    let stageSeq = 0; // Bilang ng stage events: para hindi ma-re-lock ng lumang snapshot ang stage
    let stageSynced = false;          // nakatanggap na ba ng unang snapshot ngayong connection
    let lastStageBeat = Date.now();   // huling "buhay pa ang singer" signal
    let talkHeld = false, isTalking = false, talkTail = 0, talkProc = null;
    let senderNext = {}, talkingUntil = {}, talkRefreshT = null;
    let timerInt = null, seconds = 0;

    const userListEl = document.getElementById('userList');
    const userCountEl = document.getElementById('userCount');
    const handBtn = document.getElementById('handBtn');
    const adminToggleBtn = document.getElementById('adminToggleBtn');
    const wakeBanner = document.getElementById('wakeBanner');
    const connBadge = document.getElementById('connBadge');
    const activeTimerEl = document.getElementById('activeTimer');

    // Collapsible Mixer Logic
    const mixerPanel = document.getElementById('mixerPanel');
    const mixerToggleBtn = document.getElementById('mixerToggleBtn');
    let isMixerVisible = false;

    window.toggleMixer = () => {
      isMixerVisible = !isMixerVisible;
      mixerPanel.style.display = isMixerVisible ? "flex" : "none";
      mixerToggleBtn.textContent = isMixerVisible ? "Hide ▲" : "Show ▼";
    };

    // Desktop lang: naka-SHOW na agad ang DSP Mixer pagbukas ng page (mobile: naka-hide pa rin)
    if (window.matchMedia('(min-width: 861px)').matches) {
      const mc = document.getElementById('mixerContent'), mb = document.getElementById('mixerBtn');
      if (mc && mb) { mc.style.display = 'block'; mb.textContent = 'Hide ▲'; }
    }

    // Sliders Persistence
    const savedVocal = localStorage.getItem("kantahan_vocal") || "0.9";
    const savedMusic = localStorage.getItem("kantahan_music") || "0.75";
    const savedEcho = localStorage.getItem("kantahan_echo") || "0.30";
    const savedRepeat = localStorage.getItem("kantahan_repeat") || "0.30";

    document.getElementById('vocalGain').value = savedVocal;
    document.getElementById('musicGain').value = savedMusic;
    document.getElementById('echoGain').value = savedEcho;
    document.getElementById('echoRepeat').value = savedRepeat;

    window.resetStudioDefaults = () => {
      document.getElementById('vocalGain').value = "0.9";
      document.getElementById('musicGain').value = "0.75";
      document.getElementById('echoGain').value = "0.30";
      document.getElementById('echoRepeat').value = "0.30";
      updateSliders();
      // Monitor: ECHO ONLY (naka-on)
      monitorMode = 1;
      isMonitoringEnabled = true;
      applyMonitorGain();
      refreshMonitorLabel();
    };

    // YouTube Karaoke Search & Embedded Player
    async function searchKaraoke() {
      const q = document.getElementById('ytSearchInput').value.trim();
      const resContainer = document.getElementById('ytResults');
      if (!q) return;

      resContainer.innerHTML = "<div style='font-size:11px; color:var(--muted); padding:4px;'>Searching tracks...</div>";

      try {
        const res = await fetch(`yt_search.php?q=${encodeURIComponent(q)}`);
        const data = await res.json();

        resContainer.innerHTML = "";
        if (!data.results || data.results.length === 0) {
          resContainer.innerHTML = "<div style='font-size:11px; color:var(--muted); padding:4px;'>No songs found. Try another title.</div>";
          return;
        }

        data.results.forEach(song => {
          const item = document.createElement('div');
          item.className = 'song-result-item';
          item.onclick = () => playKaraokeVideo(song.id, song.title);
          item.innerHTML = `
            <img src="${song.thumb}" alt="thumb">
            <span>${esc(song.title)}</span>
            <span class="play-tag" style="font-size:10px; color:var(--accent); font-weight:bold;">▶ Play</span>
          `;
          resContainer.appendChild(item);
        });
      } catch (err) {
        resContainer.innerHTML = "<div style='font-size:11px; color:var(--singer-red); padding:4px;'>Search failed. Please retry.</div>";
      }
    }

    let isYtPlaying = false;

    function playKaraokeVideo(videoId, title) {
      autoLyricsFor(title);
      const screen = document.getElementById('ytScreen');
      const player = document.getElementById('ytPlayer');
      const closeBtn = document.getElementById('closeVideoBtn');
      const playBtn = document.getElementById('playPauseVideoBtn');
      const searchRes = document.getElementById('ytResults');

      // Gagamit ng enablejsapi=1 para makontrol mula sa labas
      player.src = `https://www.youtube.com/embed/${videoId}?autoplay=0&controls=1&playsinline=1&enablejsapi=1&origin=${encodeURIComponent(location.origin)}`;
      
      screen.style.display = 'block';
      closeBtn.style.display = 'inline-flex';
      playBtn.style.display = 'inline-flex';
      playBtn.textContent = '▶ Play';
      isYtPlaying = false;
      searchRes.innerHTML = "";
    }

    function toggleKaraokePlayback() {
      const player = document.getElementById('ytPlayer');
      const playBtn = document.getElementById('playPauseVideoBtn');
      if (!player || !player.contentWindow) return;

      if (!isYtPlaying) {
        player.contentWindow.postMessage(JSON.stringify({ event: 'command', func: 'playVideo', args: [] }), '*');
        playBtn.textContent = '⏸ Pause';
        playBtn.style.background = '#e11d48'; // Red/Pink kapag pause
        isYtPlaying = true;
      } else {
        player.contentWindow.postMessage(JSON.stringify({ event: 'command', func: 'pauseVideo', args: [] }), '*');
        playBtn.textContent = '▶ Play';
        playBtn.style.background = '#2563eb'; // Blue kapag play
        isYtPlaying = false;
      }
    }

    function stopKaraokeVideo() {
      const screen = document.getElementById('ytScreen');
      const player = document.getElementById('ytPlayer');
      const closeBtn = document.getElementById('closeVideoBtn');
      const playBtn = document.getElementById('playPauseVideoBtn');

      player.src = "";
      screen.style.display = 'none';
      closeBtn.style.display = 'none';
      playBtn.style.display = 'none';
      isYtPlaying = false;
    }

    // Tinatayang total delay ng sariling boses sa headphones (input + output latency ng device)
    function getMonitorLatencyMs() {
      if (!audioCtx) return null;
      let total = (audioCtx.baseLatency || 0) + (audioCtx.outputLatency || 0);
      try {
        const track = micStream && micStream.getAudioTracks()[0];
        const st = track && track.getSettings ? track.getSettings() : null;
        if (st && typeof st.latency === 'number') total += st.latency;
      } catch (e) {}
      return total > 0 ? Math.round(total * 1000) : null;
    }

    function refreshMonitorLabel() {
      const btn = document.getElementById('monitorToggleBtn');
      if (!btn) return;
      if (monitorMode === 0) {
        btn.textContent = "🎧 Off";
        btn.title = "Monitor: OFF";
        btn.style.color = "var(--muted)";
        btn.style.borderColor = "var(--border)";
      } else if (monitorMode === 1) {
        btn.textContent = "🎧 Echo";
        btn.title = "Monitor: ECHO ONLY (echo lang ang maririnig mo)";
        btn.style.color = "var(--accent)";
        btn.style.borderColor = "var(--accent)";
      } else {
        const ms = getMonitorLatencyMs();
        btn.textContent = "🎧 Full";
        btn.title = "Monitor: FULL (mic + echo)" + (ms ? ` - delay ~${ms}ms` : "");
        btn.style.color = "var(--gold)";
        btn.style.borderColor = "var(--gold)";
      }
    }

    // Naririnig lang ang monitor kapag Soundcheck o Live (hindi habang Talk o nakatambay)
    function applyMonitorGain() {
      if (!audioCtx || !monitorGainNode || !monitorDryNode) return;
      const t = audioCtx.currentTime;
      const live = isSendingAudio || isSoundcheck;
      monitorGainNode.gain.setTargetAtTime((isMonitoringEnabled && live) ? 1.0 : 0, t, 0.01);
      monitorDryNode.gain.setTargetAtTime(monitorMode === 2 ? 1.0 : 0, t, 0.01);
    }

    // OFF -> ECHO ONLY -> FULL -> OFF
    window.toggleLocalMonitoring = () => {
      initAudio();
      monitorMode = (monitorMode + 1) % 3;
      isMonitoringEnabled = monitorMode > 0;
      applyMonitorGain();
      refreshMonitorLabel();
    };

    // UI Sorting & Rendering
    // ===== LYRICS (search via LRCLIB, sync ng text at scroll sa lahat) =====
    const LYR_MAX = 8000;
    let lyrSearchTok = 0;
    const lyrNorm = (x) => String(x || '').toLowerCase().normalize('NFKD').replace(/[^a-z0-9]+/g, '');

    // Linisin ang pamagat ng karaoke video: alisin ang (Karaoke Version), [HD], | Sing King, atbp.
    function cleanVideoTitle(t) {
      return String(t || '')
        .replace(/\[[^\]]*\]|\([^)]*\)|\{[^}]*\}/g, ' ')
        .replace(/\|.*$/, ' ')
        .replace(/\b(with lyrics|lyric video|lyrics|karaoke|videoke|instrumental|minus one|backing track|sing[- ]?along|higher key|lower key|official|hd|hq|4k|version|made famous by|originally performed by|in the style of)\b/gi, ' ')
        .replace(/\s+/g, ' ').replace(/^[\s\-–—:]+|[\s\-–—:]+$/g, '').trim();
    }
    const LYR_EMPTY = () => ({ title: '', text: '', singerId: null, r: 0 });
    let lyrics = LYR_EMPTY(), lyrShown = null, lyrApplyAt = 0, lyrSendT = null;
    const lyrCanEdit = () => activeSingerId === myId || isAdmin;
    const cleanLyr = (t) => String(t || '').replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '').slice(0, LYR_MAX);
    const lyrRatio = (v) => Math.min(1, Math.max(0, +v || 0));

    function lyrApplyScroll() {
      ['lyricsBox', 'listenLyrics'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        lyrApplyAt = Date.now();
        el.scrollTop = Math.max(0, el.scrollHeight - el.clientHeight) * lyrics.r;
      });
    }

    function renderLyrics() {
      if (lyrics.text && !lyrics.singerId && activeSingerId) lyrics.singerId = activeSingerId; // naging lyrics ng kumakanta
      if (lyrics.text && lyrics.singerId && lyrics.singerId !== activeSingerId) lyrics = LYR_EMPTY(); // lumipat na ang stage
      const has = !!lyrics.text, edit = lyrCanEdit();
      document.getElementById('lyricsCard').style.display = (has || edit) ? 'flex' : 'none';
      document.getElementById('lyricsTools').style.display = edit ? 'block' : 'none';
      document.getElementById('lyricsClearBtn').style.display = (edit && has) ? 'inline-flex' : 'none';
      const key = lyrics.title + '\u0001' + lyrics.text;
      if (key !== lyrShown) {
        lyrShown = key;
        document.getElementById('lyricsTitle').textContent = lyrics.title;
        document.getElementById('lyricsBox').textContent = lyrics.text || 'Mag-search ng kanta sa taas para lumabas ang lyrics.';
        document.getElementById('listenLyricsTitle').textContent = lyrics.title;
        document.getElementById('listenLyrics').textContent = lyrics.text;
        document.getElementById('listenLyricsWrap').style.display = has ? 'block' : 'none';
        setTimeout(lyrApplyScroll, 0);
      }
    }

    function setLyrics(title, text) {
      lyrics = { title: cleanLyr(title).slice(0, 80), text: cleanLyr(text), singerId: activeSingerId, r: 0 };
      sendStateMsg({ type: 'LYRICS', from: myId, title: lyrics.title, text: lyrics.text });
      renderLyrics();
      document.getElementById('lyricsBox').scrollTop = 0;
    }
    window.clearLyrics = () => {
      lyrics = LYR_EMPTY();
      sendStateMsg({ type: 'LYRICS_CLEAR', from: myId });
      renderLyrics();
    };

    function pickLyricsResult(x) {
      const txt = x.plainLyrics || String(x.syncedLyrics).replace(/\[\d+:\d+(?:[.:]\d+)?\]\s*/g, '');
      setLyrics((x.trackName || '') + ' — ' + (x.artistName || ''), txt);
    }
    // Kapag may pinili/pinatugtog na video, awtomatikong hanapin ang lyrics nito (singer/admin lang)
    function autoLyricsFor(title) {
      if (!lyrCanEdit()) return;
      const q = cleanVideoTitle(title) || document.getElementById('ytSearchInput').value.trim();
      if (q.length < 2) return;
      document.getElementById('lyricsQ').value = q;
      searchLyrics(q, true);
    }

    window.searchLyrics = async (qArg, auto) => {
      const q = (typeof qArg === 'string' ? qArg : document.getElementById('lyricsQ').value).trim();
      const tok = ++lyrSearchTok;
      const res = document.getElementById('lyricsResults');
      if (q.length < 2) return;
      res.textContent = 'Searching...';
      try {
        const r = await fetch('https://lrclib.net/api/search?q=' + encodeURIComponent(q));
        const d = await r.json();
        if (tok !== lyrSearchTok) return; // may mas bago nang search
        const list = (Array.isArray(d) ? d : []).filter(x => x && !x.instrumental && (x.plainLyrics || x.syncedLyrics)).slice(0, 6);
        res.textContent = '';
        if (!list.length) { res.textContent = 'Walang nahanap. Subukan ang ibang spelling o idagdag ang artist.'; return; }
        if (auto) { // kusang i-display lang kung kumpiyansa: nasa pamagat ng video ang title AT artist
          const nq = lyrNorm(q);
          const hit = list.find(x => lyrNorm(x.trackName).length >= 3 && nq.includes(lyrNorm(x.trackName)) &&
                                     lyrNorm(x.artistName).length >= 2 && nq.includes(lyrNorm(x.artistName)));
          if (hit) {
			  pickLyricsResult(hit);
			  res.textContent = '';
			  const sm = document.createElement('small');
			  sm.style.cssText = 'font-size:10px; color:var(--muted);';
			  sm.textContent = '✓ Auto-loaded: ' + (hit.trackName || '') + ' — ' + (hit.artistName || '');
			  res.appendChild(sm);
			  return;
			}
        }
        list.forEach(x => {
          const b = document.createElement('button');
          b.className = 'lyr-hit';
          b.textContent = (x.trackName || '?') + ' — ' + (x.artistName || '?');
          b.onclick = () => { pickLyricsResult(x); res.textContent = ''; };
          res.appendChild(b);
        });
      } catch (e) { res.textContent = 'Hindi ma-load ang lyrics. Subukan ulit.'; }
    };

    // Ang kumakanta (o admin) ang nag-i-scroll; ratio (0-1) ang ipinapadala para pantay sa kahit anong screen
    document.getElementById('lyricsBox').addEventListener('scroll', (e) => {
      if (!lyrCanEdit() || Date.now() - lyrApplyAt < 150 || lyrSendT) return;
      lyrSendT = setTimeout(() => {
        lyrSendT = null;
        const el = e.target, max = el.scrollHeight - el.clientHeight;
        lyrics.r = max > 0 ? el.scrollTop / max : 0;
        sendStateMsg({ type: 'LYRICS_SCROLL', from: myId, r: +lyrics.r.toFixed(4) });
      }, 120);
    });

    let lastListenN = 0;
    function updateListenBadge() {
      const badge = document.getElementById('listenBadge');
      if (!badge) return;
      const n = Object.values(users).filter(u => u.ln && u.id !== myId).length;
      const mine = activeSingerId === myId; // singer lang ang may badge
      badge.style.display = mine ? 'inline-block' : 'none';
      document.getElementById('listenCount').textContent = n;
      badge.style.color = n > 0 ? 'var(--accent)' : 'var(--muted)';
      badge.style.borderColor = n > 0 ? 'var(--accent)' : 'var(--border)';
      if (mine && n > lastListenN) { // may bagong nakikinig: kislap saglit
        badge.animate([{ transform: 'scale(1.35)' }, { transform: 'scale(1)' }], { duration: 450 });
      }
      lastListenN = n;
    }

    function renderUI() {
      updateListenBadge();
      renderLyrics();
      userCountEl.textContent = Object.keys(users).length;
      userListEl.innerHTML = "";

      const sortedUsers = Object.values(users).sort((a, b) => {
        if (a.id === activeSingerId) return -1;
        if (b.id === activeSingerId) return 1;

        const aQ = queue.indexOf(a.id);
        const bQ = queue.indexOf(b.id);

        if (aQ !== -1 && bQ !== -1) return aQ - bQ;
        if (aQ !== -1) return -1;
        if (bQ !== -1) return 1;

        return a.name.localeCompare(b.name);
      });

      // End Stage button: ako ang singer, o admin, o wala na sa listahan ang singer (disconnected)
      const stageEndBtn = document.getElementById('stageEndBtn');
      if (stageEndBtn) {
        const canEnd = activeSingerId && (activeSingerId === myId || isAdmin || !users[activeSingerId]);
        stageEndBtn.style.display = canEnd ? "inline-block" : "none";
        stageEndBtn.textContent = activeSingerId === myId ? "🔓 Finish" : "🔓 End Stage";
      }

      const talkBtnEl = document.getElementById('talkBtn');
      if (talkBtnEl) {
        const canTalk = !activeSingerId && queue.indexOf(myId) !== -1;
        talkBtnEl.style.display = canTalk ? 'inline-flex' : 'none';
        if (!canTalk && (isTalking || talkHeld)) stopTalk();
      }

      const myQPos = queue.indexOf(myId);
      if (myQPos !== -1) {
        handBtn.textContent = "✋ Lower Hand";
        handBtn.style.color = "var(--singer-red)";
        handBtn.style.borderColor = "var(--singer-red)";
      } else {
        handBtn.textContent = "✋ Raise Hand";
        handBtn.style.color = "var(--accent)";
        handBtn.style.borderColor = "var(--accent)";
      }

      sortedUsers.forEach(u => {
        const row = document.createElement('div');
        const isSinger = (u.id === activeSingerId);
        const qPos = queue.indexOf(u.id);
        const isNext = (qPos === 0 && !activeSingerId);

        let rowClass = "user-row";
        if (isSinger) rowClass += " singer-active";
        else if (isNext) rowClass += " next-inline";
        row.className = rowClass;

        let statusBadge = `<span style="color:var(--muted); font-size:11px;">Audience</span>`;
        if (isSinger) {
          statusBadge = `<span style="color:var(--singer-red); font-weight:bold;">🎤 Currently Performing</span>`;
        } else if (qPos !== -1) {
          statusBadge = `<span class="queue-pos">Queue #${qPos + 1}</span>`;
        }

        if (!isSinger && (talkingUntil[keyOf(u.id)] || 0) > Date.now()) {
          statusBadge += ` <span style="font-size:12px;">🗣️</span>`;
        }

        let actionHTML = "";

        if (isSinger && u.id === myId) {
          actionHTML += `<button class="btn-action stop-sing" onclick="unlockStage()">🔓 Finish (Unlock Mic)</button>`;
        }
        else if (u.id === myId && qPos === 0 && !activeSingerId) {
          actionHTML += `<button class="btn-action start-sing" onclick="lockStageForMe()">🎤 Start Singing (Lock Mic)</button>`;
        }

        if (isAdmin) {
          if (isSinger && u.id !== myId) {
            actionHTML += `<button class="btn-action stop-sing" onclick="forceUnlock()">End Performance</button>`;
          } else if (!isSinger && u.id !== myId) {
            actionHTML += `<button class="btn-action admin-tool" onclick="adminPick('${u.id}')">Grant Stage</button>`;
          }
        }

        const me = (u.id === myId);
        const avatarHTML = u.avatar
          ? `<div class="avatar has-img${me ? ' me' : ''}"${me ? ' onclick="openProfile()" title="Profile"' : ''}><img src="${u.avatar}" alt=""></div>`
          : `<div class="avatar${me ? ' me' : ''}"${me ? ' onclick="openProfile()" title="Profile"' : ''}>${esc(u.letter || u.name.charAt(0).toUpperCase())}</div>`;

        row.innerHTML = `
          <div class="user-info">
            ${avatarHTML}
            <div>
              <strong>${esc(u.name)} ${u.id === myId ? '<span style="color:var(--blue); font-size:11px;">(You)</span>' : ''}</strong>
              <div style="margin-top:2px;">${statusBadge}</div>
            </div>
          </div>
          <div class="user-actions">
            ${actionHTML}
          </div>
        `;
        userListEl.appendChild(row);
      });

      syncMicIdle();
    }

    // Audio Engine
    let audioCtx = null, ws = null;
    let micStream = null, desktopStream = null;
    let isSendingAudio = false;
    let nextPlayTime = 0;

    let masterMixBus = null, studioLimiter = null;
    let micGainNode, musicGainNode, echoDelayNode, echoFeedbackNode, echoWetNode, echoFilterNode, echoSendNode, micInputNode;
    const ECHO_MAX = 0.8; // Lakas ng echo kapag 100% ang slider (relative sa raw mic)
    let monitorGainNode = null;
    let isMonitoringEnabled = true; // default: ECHO ONLY naka-on
    let isStageMuted = false; // Local lang: itinatahimik ang naririnig mula sa kumakanta (walang epekto sa iba)
    let isSoundcheck = false; // Mic bukas at gumagana ang DSP, pero HINDI nagta-transmit
    let micSourceNode = null, procNode = null;
    let monitorMode = 1; // 0 = OFF, 1 = ECHO ONLY (default), 2 = FULL (mic + echo)
    let monitorDryNode = null;

    function initAudio() {
      if (!audioCtx) {
        const AC = window.AudioContext || window.webkitAudioContext;
        try {
          // latencyHint: 0 = pinakamaliit na output buffer na kaya ng device
          audioCtx = new AC({ sampleRate: 48000, latencyHint: 0 });
        } catch (e) {
          audioCtx = new AC({ sampleRate: 48000, latencyHint: 'interactive' });
        }

        studioLimiter = audioCtx.createDynamicsCompressor();
        studioLimiter.threshold.setValueAtTime(-0.5, audioCtx.currentTime);
        studioLimiter.knee.setValueAtTime(4.0, audioCtx.currentTime);
        studioLimiter.ratio.setValueAtTime(12.0, audioCtx.currentTime);
        studioLimiter.attack.setValueAtTime(0.001, audioCtx.currentTime);
        studioLimiter.release.setValueAtTime(0.06, audioCtx.currentTime);

        masterMixBus = audioCtx.createGain();
        micInputNode = audioCtx.createGain(); // RAW mic (pre-fader): dito kumukuha ang monitor at echo
        micGainNode = audioCtx.createGain();   // Vocal slider: para lang sa broadcast
        micInputNode.connect(micGainNode);
        musicGainNode = audioCtx.createGain();

        // Echo Circuit Nodes
        echoDelayNode = audioCtx.createDelay(1.0);
        echoDelayNode.delayTime.value = 0.20;
        echoFeedbackNode = audioCtx.createGain();
        echoFeedbackNode.gain.value = 0;
        echoWetNode = audioCtx.createGain();
        echoWetNode.gain.value = 0;
        echoFilterNode = audioCtx.createBiquadFilter();
        echoFilterNode.type = "lowpass";
        echoFilterNode.frequency.value = 3800;

        // Feedback Loop
        echoDelayNode.connect(echoFilterNode);
        echoFilterNode.connect(echoFeedbackNode);
        echoFeedbackNode.connect(echoDelayNode);
        echoFilterNode.connect(echoWetNode);

        // 1. Direct Dry Vocal papunta sa Master Bus (Laging malinaw sa broadcast)
        micGainNode.connect(masterMixBus);

        // 2. Echo Send Node: Hiwalay na linya para sa Echo lang
        echoSendNode = audioCtx.createGain();
        echoSendNode.gain.value = 0; // Kontrolado ng Echo slider

        // Ikabit ang mic source sa echoSendNode, tapos papunta sa echo delay
        micInputNode.connect(echoSendNode); // pre-fader: hindi apektado ng Vocal slider
        echoSendNode.connect(echoDelayNode);

        // Echo wet out papunta sa master mix
        echoWetNode.connect(masterMixBus);

        // Music out papunta sa master mix
        musicGainNode.connect(masterMixBus);

        masterMixBus.connect(studioLimiter);

        // DIRECT VOCAL MONITORING (Sidetone): Mic + Echo Wet lamang, walang delay ng limiter
        monitorGainNode = audioCtx.createGain();
        monitorGainNode.gain.value = 0; // bubukas lang kapag Soundcheck o Live (applyMonitorGain)
        // Dry mic papunta sa monitor: sarado sa ECHO ONLY mode (para hindi madoble sa direct monitor ng mixer)
        monitorDryNode = audioCtx.createGain();
        monitorDryNode.gain.value = 0;
        micInputNode.connect(monitorDryNode);
        monitorDryNode.connect(monitorGainNode);
        echoWetNode.connect(monitorGainNode);
        monitorGainNode.connect(audioCtx.destination);

        updateSliders();
        applyMonitorGain();
      }
      if (audioCtx.state === 'suspended') audioCtx.resume();
    }

    function updateSliders() {
      const vocalEl = document.getElementById('vocalGain');
      const musicEl = document.getElementById('musicGain');
      const echoEl = document.getElementById('echoGain');
      const repeatEl = document.getElementById('echoRepeat');

      if (!vocalEl || !musicEl || !echoEl || !repeatEl) return;

      const vG = parseFloat(vocalEl.value) || 0;
      const mG = parseFloat(musicEl.value) || 0;
      const eG = parseFloat(echoEl.value) || 0;
      const eR = parseFloat(repeatEl.value) || 0;

      document.getElementById('vocalVal').textContent = Math.round(vG * 100) + "%";
      document.getElementById('musicVal').textContent = Math.round(mG * 100) + "%";
      document.getElementById('echoVal').textContent = Math.round(eG * 100) + "%";
      document.getElementById('repeatVal').textContent = Math.round(eR * 100) + "%";

      localStorage.setItem("kantahan_vocal", vG);
      localStorage.setItem("kantahan_music", mG);
      localStorage.setItem("kantahan_echo", eG);
      localStorage.setItem("kantahan_repeat", eR);

      if (!audioCtx) return;

      const now = audioCtx.currentTime;

      // Malakas at buong boses papunta sa kabilang linya
      micGainNode.gain.cancelScheduledValues(now);
      micGainNode.gain.setValueAtTime(vG, now);

      // Background Music
      musicGainNode.gain.cancelScheduledValues(now);
      musicGainNode.gain.setValueAtTime(mG, now);

      // ECHO: Linear at predictable.
      // - Echo slider (eG)  = lakas ng echo na naririnig (wet level)
      // - Repeats slider (eR) = ilang beses uulit (feedback)
      // - Kapag 0 ang Echo slider: sarado ang send, wet at feedback (walang echo, walang tail)
      const echoOn = eG > 0.005;
      const T = 0.012; // smoothing para walang "click/zipper" kapag ginagalaw ang slider

      [echoSendNode, echoWetNode, echoFeedbackNode].forEach(n => n.gain.cancelScheduledValues(now));

      echoSendNode.gain.setTargetAtTime(echoOn ? 1.0 : 0, now, T);
      echoWetNode.gain.setTargetAtTime(echoOn ? eG * eG * ECHO_MAX : 0, now, T); // squared taper: pino sa mababang values
      echoFeedbackNode.gain.setTargetAtTime(echoOn ? eR : 0, now, T);

      if (!echoOn) {
        // Siguraduhing eksaktong zero (hindi lang "halos" zero)
        [echoSendNode, echoWetNode, echoFeedbackNode].forEach(n => n.gain.setValueAtTime(0, now + 0.15));
      }
    }

    ['vocalGain','musicGain','echoGain','echoRepeat'].forEach(id => {
      document.getElementById(id).addEventListener('input', updateSliders);
    });

    // WebSocket Sync
    let isFirstConnect = true;

    function connectWS() {
      try {
        if (isFirstConnect) {
          wakeBanner.style.display = "block";
          connBadge.textContent = "○ Waking relay...";
          connBadge.className = "badge waking";
        }

        roomReady = false; // sa bawat (re)connect, hintayin ulit ang ROOM_INFO
        ws = new WebSocket(relayUrl());
        ws.binaryType = "arraybuffer";

        ws.onopen = () => {
          isFirstConnect = false;
          stageSynced = false;
          wakeBanner.style.display = "none";
          connBadge.textContent = "● Connected (Live)";
          connBadge.className = "badge live";

          sendStateMsg({ 
            type: 'USER_JOIN', 
            user: myUser() 
          });

          sendStateMsg({ type: 'REQUEST_ROOM_SYNC', requesterId: myId });
        };

        ws.onclose = (e) => {
          if (noReconnect || (e && (e.code === 4000 || e.code === 4003))) return;
          connBadge.textContent = "○ Reconnecting...";
          connBadge.className = "badge";
          setTimeout(connectWS, 3000);
        };

        ws.onmessage = (event) => {
          // Ang unang message ng relay ay laging ROOM_INFO o ROOM_ERROR; ito lang ang pinagkakatiwalaan
          if (!roomReady) {
            if (typeof event.data === 'string') {
              try {
                const m = JSON.parse(event.data);
                if (m.type === 'ROOM_INFO') onRoomInfo(m);
                else if (m.type === 'ROOM_ERROR') onRoomError(m.reason);
              } catch (e) {}
            }
            return;
          }
          if (event.data instanceof ArrayBuffer) {
            playIncomingAudio(event.data);
            return;
          }
          try {
            handleSyncEvent(JSON.parse(event.data));
          } catch(e){}
        };
      } catch(e){}
    }

    function sendStateMsg(msg) {
      if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(msg));
      }
    }

    function handleSyncEvent(msg) {
      if (msg.type === 'USER_JOIN') {
        const joined = sanitizeUser(msg.user);
        if (!joined) return;
        users[joined.id] = joined;
        sendStateMsg({
          type: 'ROOM_SNAPSHOT',
          activeSingerId: activeSingerId,
          activeSingerName: activeSingerName,
          stageSeq: stageSeq,
          queue: queue,
          users: users,
          lyrics: lyrics
        });
        renderUI();
      } 
      else if (msg.type === 'USER_UPDATE') {
        const upd = sanitizeUser(msg.user);
        if (upd && upd.id !== myId) {
          users[upd.id] = upd;
          renderUI();
        }
      }
      else if (msg.type === 'REQUEST_ROOM_SYNC') {
        sendStateMsg({
          type: 'ROOM_SNAPSHOT',
          activeSingerId: activeSingerId,
          activeSingerName: activeSingerName,
          stageSeq: stageSeq,
          queue: queue,
          users: users,
          lyrics: lyrics
        });
      }
      else if (msg.type === 'ROOM_SNAPSHOT') {
        const snapSeq = msg.stageSeq || 0;
        // Sundin lang ang snapshot kung MAS BAGO ito, o kung ito ang unang sync ng connection na ito.
        // Ang lumang snapshot ay hindi na makakapag-revive ng na-unlock nang stage.
        if (snapSeq > stageSeq || (!stageSynced && snapSeq >= stageSeq)) {
          stageSynced = true;
          stageSeq = snapSeq;
          if (msg.activeSingerId) {
            if (activeSingerId !== msg.activeSingerId) startTimer();
            activeSingerId = msg.activeSingerId;
            activeSingerName = msg.activeSingerName;
            lastStageBeat = Date.now();
          } else if (activeSingerId) {
            activeSingerId = null;
            activeSingerName = null;
            stopTimer();
          }
          if (Array.isArray(msg.queue)) {
            queue = msg.queue;
          }
        }
        if (msg.users && typeof msg.users === 'object') {
          const cleaned = {};
          Object.values(msg.users).forEach(raw => {
            const u = sanitizeUser(raw);
            if (u) cleaned[u.id] = u;
          });
          users = cleaned;
          users[myId] = myUser();
        }
        if (msg.lyrics && typeof msg.lyrics.text === 'string' && msg.lyrics.text && !lyrics.text) {
          lyrics = { title: cleanLyr(msg.lyrics.title).slice(0, 80), text: cleanLyr(msg.lyrics.text), singerId: activeSingerId, r: lyrRatio(msg.lyrics.r) };
        }
        renderUI();
      } 
      else if (msg.type === 'USER_LEAVE') {
        delete users[msg.userId];
        queue = queue.filter(id => id !== msg.userId);
        if (activeSingerId === msg.userId) {
          activeSingerId = null;
          activeSingerName = null;
          stopTimer();
        }
        renderUI();
      } 
      else if (msg.type === 'STAGE_BEAT') {
        stageSynced = true; // may tiyak na nang alam sa stage mula sa live event
        const seq = msg.stageSeq || 0;
        if (seq < stageSeq) return; // lumang lock na na-unlock na
        stageSeq = seq;
        lastStageBeat = Date.now();
        if (activeSingerId !== msg.singerId) {
          if (msg.singerId !== myId && isSendingAudio) stopBroadcasting();
          activeSingerId = msg.singerId;
          activeSingerName = msg.singerName;
          startTimer();
          renderUI();
        }
      }
      else if (msg.type === 'STAGE_LOCKED') {
        stageSynced = true; // may tiyak na nang alam sa stage mula sa live event
        stageSeq = Math.max(stageSeq, msg.stageSeq || 0);
        lastStageBeat = Date.now();
        // May ibang na-grant ng stage habang ako ay naka-live: patayin ang mic ko
        if (msg.singerId !== myId && isSendingAudio) stopBroadcasting();
        activeSingerId = msg.singerId;
        activeSingerName = msg.singerName;
        queue = msg.queue;
        startTimer();
        renderUI();
      } 
      else if (msg.type === 'STAGE_UNLOCKED') {
        stageSynced = true; // may tiyak na nang alam sa stage mula sa live event
        stageSeq = Math.max(stageSeq, msg.stageSeq || 0);
        // Na-End ng admin habang naka-live ako: dapat mamatay rin ang mic at transmit ko
        if (isSendingAudio) stopBroadcasting();
        activeSingerId = null;
        activeSingerName = null;
        queue = msg.queue;
        stopTimer();
        renderUI();
      } 
      else if (msg.type === 'QUEUE_UPDATE') {
        queue = msg.queue;
        renderUI();
      } 
      else if (msg.type === 'LYRICS' && msg.from !== myId && users[msg.from]) {
        lyrics = { title: cleanLyr(msg.title).slice(0, 80), text: cleanLyr(msg.text), singerId: activeSingerId, r: 0 };
        renderLyrics();
      }
      else if (msg.type === 'LYRICS_CLEAR' && msg.from !== myId && users[msg.from]) {
        lyrics = LYR_EMPTY();
        renderLyrics();
      }
      else if (msg.type === 'LYRICS_SCROLL' && msg.from !== myId && users[msg.from]) {
        lyrics.r = lyrRatio(msg.r);
        lyrApplyScroll();
      }
      else if (msg.type === 'CHAT') {
        appendMsg(msg.author, msg.text);
      }
    }

    // PLAY INCOMING AUDIO: Ganap na i-mute kapag ikaw ang aktibong kumakanta o nagbo-broadcast
    // Audio packet = 8-byte header + Float32 samples.
    // Header: [senderKey (23-bit), flags]  flags bit0 = Talk. Hindi nagiging ingay sa lumang version (denormal ≈ 0).
    function keyOf(id) {
      let h = 0x811c9dc5;
      for (let i = 0; i < id.length; i++) {
        h ^= id.charCodeAt(i);
        h = Math.imul(h, 0x01000193) >>> 0;
      }
      return (h & 0x7FFFFF) || 1;
    }

    function packAudio(input, flags) {
      const out = new ArrayBuffer(8 + input.length * 4);
      const hdr = new Uint32Array(out, 0, 2);
      hdr[0] = keyOf(myId);
      hdr[1] = flags;
      new Float32Array(out, 8, input.length).set(input);
      return out;
    }

    function markTalking(key) {
      const wasTalking = (talkingUntil[key] || 0) > Date.now();
      talkingUntil[key] = Date.now() + 700;
      if (!wasTalking) renderUI();
      clearTimeout(talkRefreshT);
      talkRefreshT = setTimeout(renderUI, 800);
    }

    function playIncomingAudio(bufData) {
      if (LISTEN_MODE && !listenArmed) return;
      let key = 0, isTalk = false, float32;
      if (bufData.byteLength === 16384) {
        float32 = new Float32Array(bufData); // lumang format (walang header)
      } else if (bufData.byteLength > 8 && (bufData.byteLength - 8) % 4 === 0) {
        const hdr = new Uint32Array(bufData, 0, 2);
        key = hdr[0];
        isTalk = (hdr[1] & 1) === 1;
        float32 = new Float32Array(bufData, 8);
      } else {
        return;
      }

      if (key && key === keyOf(myId)) return; // sariling boses na nag-reflect mula sa server

      if (isTalk) {
        if (activeSingerId) return; // naka-lock ang stage: walang Talk na papasok
      } else if (isSendingAudio || activeSingerId === myId) {
        return; // IWAS LOOPBACK: huwag patugtugin ang sariling boses
      }
      if (isStageMuted) return; // Local mute

      initAudio();
      const buf = audioCtx.createBuffer(1, float32.length, audioCtx.sampleRate);
      buf.copyToChannel(float32, 0);

      const src = audioCtx.createBufferSource();
      src.buffer = buf;
      src.connect(audioCtx.destination);

      // Kanya-kanyang schedule bawat nagsasalita, para magkapatong (hindi sunod-sunod) ang sabay-sabay na Talk
      const now = audioCtx.currentTime;
      let np = senderNext[key] || 0;
      if (np < now) np = now + 0.06;
      src.start(np);
      senderNext[key] = np + buf.duration;

      if (isTalk && key) markTalking(key);
      drawVisualizer(float32);
    }

    // Buksan ang mic at ikabit sa DSP chain. Ginagamit ng Soundcheck at ng Live broadcast.
    async function ensureMic() {
      initAudio();
      if (micStream && micStream.active) return true;
      try {
        micStream = await navigator.mediaDevices.getUserMedia({
          audio: {
            echoCancellation: false,
            noiseSuppression: false,
            autoGainControl: false,
            channelCount: 1,
            latency: 0
          }
        });
      } catch (e) {
        alert("Please allow microphone access: " + e.message);
        return false;
      }

      micSourceNode = audioCtx.createMediaStreamSource(micStream);
      micSourceNode.connect(micInputNode);

      // Isang beses lang gagawin ang tap papunta sa network/meter (hindi na dodoble kada turn)
      if (!procNode) {
        procNode = audioCtx.createScriptProcessor(4096, 1, 1);
        procNode.onaudioprocess = (e) => {
          const input = e.inputBuffer.getChannelData(0);
          if (isSendingAudio) {
            if (ws && ws.readyState === WebSocket.OPEN) ws.send(packAudio(input, 0));
            drawVisualizer(input);
          } else if (isSoundcheck && (!activeSingerId || activeSingerId === myId || isStageMuted)) {
            // Soundcheck: meter lang, walang ipinapadala. Hindi ginagalaw kapag may ibang kumakanta.
            drawVisualizer(input);
          }
        };
        studioLimiter.connect(procNode);

        const silent = audioCtx.createGain();
        silent.gain.value = 0;
        procNode.connect(silent);
        silent.connect(audioCtx.destination);
      }

      // Talk tap: kinukuha mula sa Vocal fader (walang echo, walang music)
      if (!talkProc) {
        talkProc = audioCtx.createScriptProcessor(4096, 1, 1);
        talkProc.onaudioprocess = (e) => {
          if (activeSingerId) return; // may naka-lock sa stage: bawal ang Talk
          if (!isTalking && talkTail <= 0) return;
          if (!isTalking) talkTail--;
          const input = e.inputBuffer.getChannelData(0);
          if (ws && ws.readyState === WebSocket.OPEN) ws.send(packAudio(input, 1));
          if (isTalking) drawVisualizer(input); // sariling peak meter habang naka-hold ang Talk
          markTalking(keyOf(myId));
        };
        micGainNode.connect(talkProc);
        const talkSilent = audioCtx.createGain();
        talkSilent.gain.value = 0;
        talkProc.connect(talkSilent);
        talkSilent.connect(audioCtx.destination);
      }

      setTimeout(refreshMonitorLabel, 600);
      return true;
    }

    function refreshSoundcheckLabel() {
      const btn = document.getElementById('soundcheckBtn');
      if (!btn) return;
      if (isSendingAudio) {
        btn.textContent = "🎙️ Live";
        btn.style.color = "var(--singer-red)";
        btn.style.borderColor = "var(--singer-red)";
      } else if (isSoundcheck) {
        btn.textContent = "🎙️ Test";
        btn.style.color = "var(--accent)";
        btn.style.borderColor = "var(--accent)";
      } else {
        btn.textContent = "🎙️ Test";
        btn.style.color = "var(--muted)";
        btn.style.borderColor = "var(--border)";
      }
    }

    // Test ng mic at DSP settings kahit hindi pa nakaka-lock ng stage. Walang naririnig ang iba.
    window.toggleSoundcheck = async () => {
      if (isSendingAudio) return; // Live na, hindi na kailangan
      if (!isSoundcheck) {
        isSoundcheck = true;
        const ok = await ensureMic();
        if (!ok) isSoundcheck = false;
        applyMonitorGain();
      } else {
        isSoundcheck = false;
        isStageMuted = false; refreshStageMuteLabel();
        applyMonitorGain();
        syncMicIdle();
        if (!activeSingerId) clearVisualizer();
      }
      refreshSoundcheckLabel();
    };

    function refreshStageMuteLabel() {
      const btn = document.getElementById('muteStageBtn');
      if (!btn) return;
      if (isStageMuted) {
        btn.textContent = "🔇";
        btn.style.color = "var(--gold)";
        btn.style.borderColor = "var(--gold)";
      } else {
        btn.textContent = "🔊";
        btn.style.color = "var(--muted)";
        btn.style.borderColor = "var(--border)";
      }
    }

    // Itahimik lang sa device mo ang kumakanta para makapag-test ka. Naririnig pa rin siya ng iba.
    window.toggleStageMute = () => {
      isStageMuted = !isStageMuted;
      if (isStageMuted) { nextPlayTime = 0; senderNext = {}; }
      refreshStageMuteLabel();
    };

    function refreshTalkBtn() {
      const b = document.getElementById('talkBtn');
      if (!b) return;
      const on = isTalking || talkHeld;
      b.classList.toggle('talking', on);
      b.textContent = on ? "🗣️ Talking..." : "🗣️ Talk";
    }

    // Press & hold: bukas ang mic habang naka-hold. Para lang sa nasa queue at habang WALANG naka-lock sa stage.
    async function startTalk() {
      if (activeSingerId || queue.indexOf(myId) === -1) return;
      talkHeld = true;
      refreshTalkBtn();
      initAudio();
      const ok = await ensureMic();
      if (!ok) { talkHeld = false; refreshTalkBtn(); return; }
      if (!talkHeld || activeSingerId) return; // binitawan na o may nag-lock habang naghihintay
      isTalking = true;
      talkTail = 0;
      refreshTalkBtn();
    }

    function stopTalk() {
      const was = isTalking;
      talkHeld = false;
      isTalking = false;
      if (was) talkTail = 1; // ipadala ang huling piraso para hindi putol ang dulo ng salita
      refreshTalkBtn();
      if (was) setTimeout(() => { if (!isTalking && !isSendingAudio && !isSoundcheck) drawIdleVisualizer(); }, 250);
    }

    (function setupTalkButton() {
      const b = document.getElementById('talkBtn');
      if (!b) return;
      b.addEventListener('pointerdown', (e) => {
        e.preventDefault();
        try { b.setPointerCapture(e.pointerId); } catch (err) {}
        startTalk();
      });
      ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(ev => b.addEventListener(ev, stopTalk));
      b.addEventListener('contextmenu', (e) => e.preventDefault());
      window.addEventListener('blur', stopTalk);
      document.addEventListener('visibilitychange', () => { if (document.hidden) stopTalk(); });
    })();

    // Isara ang mic kapag hindi na kailangan (wala sa queue, walang soundcheck/live)
    function syncMicIdle() {
      if (!micStream) return;
      const keepForTalk = queue.indexOf(myId) !== -1 && !activeSingerId;
      if (isSoundcheck || isSendingAudio || isTalking || talkHeld || activeSingerId === myId || keepForTalk) return;
      releaseMic();
    }

    function releaseMic() {
      if (micSourceNode) {
        try { micSourceNode.disconnect(); } catch (e) {}
        micSourceNode = null;
      }
      if (micStream) {
        micStream.getTracks().forEach(t => t.stop());
        micStream = null;
      }
    }

    async function startBroadcasting() {
      // Kung naka-Soundcheck na, gagamitin ang bukas nang mic (walang bagong permission prompt)
      const ok = await ensureMic();
      if (!ok) return;
      if (activeSingerId !== myId) return; // na-unlock na habang naghihintay ng mic permission

      try {
        desktopStream = await navigator.mediaDevices.getDisplayMedia({
          video: { displaySurface: "monitor", width: { max: 1 }, height: { max: 1 }, frameRate: { max: 1 } },
          audio: { echoCancellation: false, noiseSuppression: false, autoGainControl: false, channelCount: 2, sampleRate: 48000 },
          systemAudio: "include",
          surfaceSwitching: "include",
          selfBrowserSurface: "exclude"
        });

        const desktopAudioTracks = desktopStream.getAudioTracks();
        if (desktopAudioTracks.length > 0) {
          const desktopMediaStream = new MediaStream([desktopAudioTracks[0]]);
          const desktopSource = audioCtx.createMediaStreamSource(desktopMediaStream);
          desktopSource.connect(musicGainNode);
        }
      } catch (screenErr) {}

      // Na-unlock habang naghihintay ng share-audio prompt: huwag nang mag-live
      if (activeSingerId !== myId) {
        if (desktopStream) {
          desktopStream.getTracks().forEach(t => t.stop());
          desktopStream = null;
        }
        return;
      }

      isSendingAudio = true;
      applyMonitorGain();
      refreshSoundcheckLabel();
    }

    function stopBroadcasting() {
      isSendingAudio = false;
      isSoundcheck = false;
      isStageMuted = false; refreshStageMuteLabel();
      applyMonitorGain();
      releaseMic();
      if (desktopStream) {
        desktopStream.getTracks().forEach(t => t.stop());
        desktopStream = null;
      }
      clearVisualizer();
      refreshSoundcheckLabel();
    }

    const canvas = document.getElementById('meterCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;
    const listenCanvas = document.getElementById('listenMeter');
    const listenCtx = listenCanvas ? listenCanvas.getContext('2d') : null;
    let lastMeterAt = 0;

    function drawVisualizer(data) {
      lastMeterAt = Date.now();
      paintMeter(canvas, ctx, data);
      if (LISTEN_MODE && listenCanvas && listenCanvas.offsetParent) paintMeter(listenCanvas, listenCtx, data);
    }
    function drawIdleVisualizer() {
      paintIdle(canvas, ctx);
      if (LISTEN_MODE) paintIdle(listenCanvas, listenCtx);
    }

    function paintMeter(canvas, ctx, float32Data) {
      if (!canvas || !ctx) return;

      const pW = canvas.parentElement.clientWidth || 300;
      const pH = canvas.parentElement.clientHeight || 75;

      if (canvas.width !== pW) canvas.width = pW;
      if (canvas.height !== pH) canvas.height = pH;

      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const bars = 36;
      const step = Math.floor(float32Data.length / bars);
      const barWidth = (canvas.width / bars) - 2;

      for (let i = 0; i < bars; i++) {
        let sum = 0;
        for (let j = 0; j < step; j += 4) {
          sum += Math.abs(float32Data[i * step + j] || 0);
        }
        const avgVal = (sum / (step / 4));
        const boosted = Math.pow(avgVal, 0.65) * 4.8;
        const barHeight = Math.max(3, Math.min(canvas.height, boosted * canvas.height));
        
        const grad = ctx.createLinearGradient(0, canvas.height, 0, 0);
        grad.addColorStop(0, '#10b981');
        grad.addColorStop(0.65, '#38bdf8');
        grad.addColorStop(0.9, '#f59e0b');
        grad.addColorStop(1, '#ef4444');
        ctx.fillStyle = grad;

        const x = i * (barWidth + 2);
        const y = canvas.height - barHeight;

        ctx.fillRect(x, y, barWidth, barHeight);
      }
    }

    function paintIdle(canvas, ctx) {
      if (!canvas || !ctx) return;

      const pW = canvas.parentElement.clientWidth || 300;
      const pH = canvas.parentElement.clientHeight || 75;

      if (canvas.width !== pW) canvas.width = pW;
      if (canvas.height !== pH) canvas.height = pH;

      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const bars = 36;
      const barWidth = (canvas.width / bars) - 2;

      for (let i = 0; i < bars; i++) {
        const x = i * (barWidth + 2);
        const idleHeight = 4 + Math.sin(i * 0.45) * 2;
        
        ctx.fillStyle = "rgba(148, 163, 184, 0.18)";
        ctx.fillRect(x, canvas.height - idleHeight, barWidth, idleHeight);
      }

      ctx.fillStyle = "rgba(148, 163, 184, 0.35)";
      ctx.font = "600 11px system-ui, sans-serif";
      ctx.textAlign = "center";
      ctx.fillText("STAGE STANDBY • AUDIO READY", canvas.width / 2, (canvas.height / 2) + 4);
    }

    function clearVisualizer() {
      drawIdleVisualizer();
    }

    window.addEventListener('load', () => {
      setTimeout(drawIdleVisualizer, 150);
    });
    window.addEventListener('resize', () => {
      if (!activeSingerId) drawIdleVisualizer();
    });

    function startTimer() {
      stopTimer();
      seconds = 0;
      timerInt = setInterval(() => {
        seconds++;
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        activeTimerEl.textContent = `${m}:${s}`;
      }, 1000);
    }

    function stopTimer() {
      if (timerInt) clearInterval(timerInt);
      activeTimerEl.textContent = "00:00";
    }

    // Heartbeat: ang may hawak ng stage ay nagpapadala ng "buhay pa ako" kada 2s.
    // Kapag 9s nang walang beat mula sa singer (nag-disconnect/nag-crash), kusang nare-release ang stage.
    setInterval(() => {
      if (activeSingerId === myId) {
        sendStateMsg({ type: 'STAGE_BEAT', singerId: myId, singerName: myName, stageSeq });
      } else if (activeSingerId && Date.now() - lastStageBeat > 9000) {
        activeSingerId = null;
        activeSingerName = null;
        stopTimer();
        renderUI();
      }
    }, 2000);

    // Actions
    handBtn.onclick = () => {
      initAudio();
      const idx = queue.indexOf(myId);
      if (idx === -1) {
        queue.push(myId);
      } else {
        queue.splice(idx, 1);
      }
      sendStateMsg({ type: 'QUEUE_UPDATE', queue });
      renderUI();
    };

    window.lockStageForMe = () => {
      initAudio();
      activeSingerId = myId;
      activeSingerName = myName;
      lastStageBeat = Date.now();
      stopTalk();
      queue.shift();
      startBroadcasting();
      startTimer();
      stageSeq++; stageSynced = true;
      sendStateMsg({ type: 'STAGE_LOCKED', singerId: myId, singerName: myName, queue, stageSeq });
      renderUI();
    };

    window.unlockStage = () => {
      try { stopBroadcasting(); } catch (e) { console.error(e); }
      activeSingerId = null;
      activeSingerName = null;
      stopTimer();
      stageSeq++; stageSynced = true;
      sendStateMsg({ type: 'STAGE_UNLOCKED', queue, stageSeq });
      renderUI();
    };

    window.forceUnlock = () => {
      activeSingerId = null;
      activeSingerName = null;
      try { stopBroadcasting(); } catch (e) { console.error(e); }
      stopTimer();
      stageSeq++; stageSynced = true;
      sendStateMsg({ type: 'STAGE_UNLOCKED', queue, stageSeq });
      renderUI();
    };

    // Stage-level End button: gumagana kahit nawala na sa listahan ang singer (disconnected / ghost)
    window.endStageFromBar = () => {
      if (activeSingerId === myId) unlockStage();
      else forceUnlock();
    };

    window.adminPick = (id) => {
      activeSingerId = id;
      activeSingerName = users[id] ? users[id].name : "Performer";
      queue = queue.filter(x => x !== id);
      stageSeq++; stageSynced = true;
      sendStateMsg({ type: 'STAGE_LOCKED', singerId: id, singerName: activeSingerName, queue, stageSeq });
      renderUI();
    };

    adminToggleBtn.onclick = async () => {
      if (!isAdmin && !roomIsHost) {
        if (Date.now() < adminLockUntil) {
          alert("Masyadong maraming maling subok. Subukan ulit pagkalipas ng isang minuto.");
          return;
        }
        const enteredPin = prompt("Enter Admin PIN:");
        if (enteredPin === null) return;
        let ok = false;
        try {
          ok = (await pinHash(enteredPin)) === ADMIN_HASH;
        } catch (e) {
          alert("Hindi gumagana ang PIN check sa browser na ito (kailangan ng HTTPS).");
          return;
        }
        if (ok) {
          isAdmin = true;
          adminFails = 0;
        } else {
          adminFails++;
          if (adminFails >= 5) { adminLockUntil = Date.now() + 60000; adminFails = 0; }
          alert("Incorrect PIN. Access denied.");
          return;
        }
      } else {
        isAdmin = !isAdmin;
      }
      applyAdminBtn();
      renderUI();
    };

    window.sendChat = () => {
      const input = document.getElementById('chatInput');
      const text = input.value.trim();
      if (!text) return;
      sendStateMsg({ type: 'CHAT', author: myName, text });
      appendMsg(myName, text);
      input.value = "";
    };

    window.sendReaction = (emoji) => {
      sendStateMsg({ type: 'CHAT', author: myName, text: "sent cheers: " + emoji });
      appendMsg(myName, "sent cheers: " + emoji);
    };

    function appendMsg(sender, text) {
      const box = document.getElementById('chatMsgs');
      const d = document.createElement('div');
      d.className = 'msg-user';
      d.innerHTML = `<span class="msg-author">${esc(sender)}:</span> ${esc(text)}`;
      box.appendChild(d);
      box.scrollTop = box.scrollHeight;
    }

    window.addEventListener('beforeunload', () => {
      stopBroadcasting();
      if (activeSingerId === myId) {
        stageSeq++; stageSynced = true;
        sendStateMsg({ type: 'STAGE_UNLOCKED', queue: queue.filter(id => id !== myId), stageSeq });
      }
      sendStateMsg({ type: 'USER_LEAVE', userId: myId });
    });

    renderUI();
    refreshMonitorLabel();   // Monitor: ECHO ONLY na kita agad na naka-ON
    refreshSoundcheckLabel();
    refreshStageMuteLabel();

    // ===== MULTI-ROOM LOGIC =====
    const RELAY_HTTP = WS_URL.replace(/^ws/, 'http');
    const ROOM_RE = /^[A-Z0-9]{4,8}$/;
    const ROOM_ERR = {
      badcode: 'Hindi valid ang room code.',
      notfound: 'Walang room na may ganyang code.',
      exists: 'May gumagamit na ng code na iyan. Subukan ulit.',
      serverfull: 'Puno na ang lahat ng rooms ngayon. Subukan mamaya.',
      full: 'Puno na ang room na ito.',
      needpw: 'May password ang room na ito.',
      wrongpw: 'Maling password.'
    };
    let roomCode = '', roomMode = 'join', roomPw = '', roomName = '';
    let roomIsHost = false, roomReady = false, noReconnect = false, createTries = 0;
    const L = (id) => document.getElementById(id);

    function applyAdminBtn() {
      adminToggleBtn.className = `btn-badge ${isAdmin ? 'admin-on' : ''}`;
      adminToggleBtn.textContent = `👑 Admin: ${isAdmin ? 'ON' : 'OFF'}`;
    }

    function genCode() {
      const a = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
      const r = new Uint8Array(6);
      crypto.getRandomValues(r);
      return Array.from(r, n => a[n % a.length]).join('');
    }

    function relayUrl() {
      const hk = localStorage.getItem('kl_hk_' + roomCode) || '';
      return WS_URL + '/?room=' + encodeURIComponent(roomCode) + '&mode=' + roomMode +
        '&pw=' + encodeURIComponent(roomPw) + '&hk=' + encodeURIComponent(hk) +
        '&name=' + encodeURIComponent(roomName);
    }

    function lobbyMsg(t) { L('lobbyMsg').textContent = t || ''; }
    window.lobbyView = (v) => {
      ['Home', 'Create', 'Join'].forEach(n => { L('lobby' + n).style.display = (n === v) ? 'flex' : 'none'; });
      lobbyMsg('');
    };
    function showLobby(view) { L('lobbyModal').style.display = 'flex'; lobbyView(view || 'Home'); }

    function startRoom(code, mode, pw, name) {
      roomCode = code; roomMode = mode; roomPw = pw || ''; roomName = name || '';
      roomReady = false; noReconnect = false;
      if (ws) { try { ws.onclose = null; ws.close(); } catch (e) {} }
      lobbyMsg('Connecting...');
      connectWS();
    }

    window.createRoom = () => {
      createTries = 0;
      startRoom(genCode(), 'create', L('createPw').value.trim(), L('createName').value.trim());
    };
    window.joinRoom = () => {
      const code = L('joinCode').value.trim().toUpperCase();
      if (!ROOM_RE.test(code)) { lobbyMsg(ROOM_ERR.badcode); return; }
      startRoom(code, 'join', L('joinPw').value.trim(), '');
    };

    function onRoomInfo(m) {
      roomReady = true; roomMode = 'join';
      roomCode = m.code; roomName = m.name || roomName; roomIsHost = !!m.host;
      if (m.hostKey) localStorage.setItem('kl_hk_' + m.code, m.hostKey);
      try { history.replaceState(null, '', location.pathname + '?room=' + m.code + (LISTEN_MODE ? '&listen=1' : '')); } catch (e) {}
      L('lobbyModal').style.display = 'none';
      L('roomsModal').style.display = 'none';
      const rb = L('roomBtn');
      rb.style.display = 'inline-block';
      rb.textContent = (m.locked ? '🔒 ' : '🏠 ') + m.code;
      if (roomIsHost) isAdmin = true;
      applyAdminBtn();
      renderUI();
    }

    function onRoomError(reason) {
      noReconnect = true;
      wakeBanner.style.display = 'none';
      connBadge.textContent = '○ Not connected';
      connBadge.className = 'badge';
      if (reason === 'exists' && roomMode === 'create' && ++createTries < 4) {
        startRoom(genCode(), 'create', roomPw, roomName);
        return;
      }
      if (reason === 'notfound' || reason === 'badcode') {
        localStorage.removeItem('kl_hk_' + roomCode);
        try { history.replaceState(null, '', location.pathname); } catch (e) {}
      }
      const msg = ROOM_ERR[reason] || 'Hindi makapasok sa room.';
      showLobby(reason === 'needpw' || reason === 'wrongpw' ? 'Join' : 'Home');
      if (reason === 'needpw' || reason === 'wrongpw') {
        L('joinCode').value = roomCode;
        L('joinPw').style.display = 'block';
        L('joinPw').value = '';
        L('joinPw').focus();
      }
      lobbyMsg(msg);
    }

    // Rooms list modal
    window.closeRooms = () => { L('roomsModal').style.display = 'none'; };
    window.openRooms = () => { L('roomsModal').style.display = 'flex'; loadRooms(); };
    window.loadRooms = async () => {
      const box = L('roomsList');
      box.innerHTML = '<div class="rooms-empty">Loading... (hanggang 15-25s kung natutulog ang relay)</div>';
      try {
        const r = await fetch(RELAY_HTTP + '/rooms', { cache: 'no-store' });
        const d = await r.json();
        if (!d.rooms || !d.rooms.length) {
          box.innerHTML = '<div class="rooms-empty">Wala pang rooms. Gumawa ng una!</div>';
          return;
        }
        box.innerHTML = d.rooms.map(r => {
          const full = r.count >= r.cap;
          return `<div class="room-row">
            <div class="rn">${r.locked ? '🔒 ' : ''}${esc(r.name)}<small>${esc(r.code)}</small></div>
            <div class="rc">👥 ${r.count}/${r.cap}</div>
            <button class="btn-action" ${full ? 'disabled' : ''} data-code="${esc(r.code)}" data-locked="${r.locked ? 1 : 0}" style="padding:6px 12px;">${full ? 'Full' : 'Join'}</button>
          </div>`;
        }).join('');
      } catch (e) {
        box.innerHTML = '<div class="rooms-empty">Hindi ma-load ang listahan. Subukan ulit.</div>';
      }
    };
    L('roomsList').addEventListener('click', (e) => {
      const b = e.target.closest('button[data-code]');
      if (!b || b.disabled) return;
      const code = b.dataset.code;
      closeRooms();
      if (b.dataset.locked === '1') {
        showLobby('Join');
        L('joinCode').value = code;
        L('joinPw').style.display = 'block';
        L('joinPw').focus();
      } else {
        startRoom(code, 'join', '', '');
      }
    });
    L('roomsModal').addEventListener('click', (e) => { if (e.target === L('roomsModal')) closeRooms(); });

    // Room info modal
    window.openRoomInfo = () => {
      L('riName').textContent = roomName || ('Room ' + roomCode);
      L('riCode').textContent = roomCode;
      L('riHost').style.display = roomIsHost ? 'block' : 'none';
      L('riCopyBtn').textContent = '🔗 Copy invite link';
      L('riListenBtn').textContent = '🎧 Copy listen link';
      L('riShareBtn').style.display = navigator.share ? 'inline-flex' : 'none';
      L('roomInfoModal').style.display = 'flex';
    };
    window.closeRoomInfo = () => { L('roomInfoModal').style.display = 'none'; };
    L('roomInfoModal').addEventListener('click', (e) => { if (e.target === L('roomInfoModal')) closeRoomInfo(); });
    window.copyInvite = async () => {
      const link = location.origin + location.pathname + '?room=' + roomCode;
      try { await navigator.clipboard.writeText(link); L('riCopyBtn').textContent = '✓ Copied!'; }
      catch (e) { prompt('Copy this link:', link); }
    };
    function listenLink() { return location.origin + location.pathname + '?room=' + roomCode + '&listen=1'; }
    window.copyListen = async () => {
      const link = listenLink();
      try { await navigator.clipboard.writeText(link); L('riListenBtn').textContent = '✓ Copied!'; }
      catch (e) { prompt('Copy this link:', link); }
    };
    window.shareListen = async () => {
      try {
        await navigator.share({ title: '🎧 Live ngayon sa Kantahan Live!', text: 'Pakinggan ang kumakanta nang live!', url: listenLink() });
      } catch (e) { /* kinansela ng user */ }
    };
    window.leaveRoom = () => { location.href = location.pathname; };

    // ----- Listen popup -----
    window.closeListen = () => {
      L('listenModal').style.display = 'none';
      listenOpen = false;
      if (listenArmed) broadcastProfile(); // hindi na bilang na "nakikinig sa link"
    };
    window.startListening = () => {
      initAudio();                 // galing sa tap ng user kaya papayagan ng browser
      senderNext = {};
      isStageMuted = false; refreshStageMuteLabel();
      listenArmed = true;
      broadcastProfile();          // ipaalam sa singer na may nakikinig
      refreshListen();
    };
    function refreshListen() {
      if (!LISTEN_MODE) return;
      const btn = L('listenBtn'), now = L('listenNow'), mute = L('listenMuteBtn');
      L('listenRoom').textContent = roomReady ? (roomName || ('Room ' + roomCode)) : 'Connecting...';
      if (!roomReady) {
        now.textContent = 'Connecting... (hanggang 15-25s kung natutulog ang relay)';
        now.className = 'listen-now';
        btn.disabled = true;
        return;
      }
      const singing = !!activeSingerId;
      now.textContent = singing ? ('🎤 ' + (activeSingerName || 'Performer') + ' ay kumakanta ngayon') : 'Naghihintay ng kakanta...';
      now.className = 'listen-now' + (singing ? ' live' : '');
      btn.disabled = false;
      if (listenArmed) {
        btn.textContent = '🔊 Listening live';
        btn.className = 'listen-big on';
        mute.style.display = 'inline-flex';
        mute.textContent = isStageMuted ? '🔇 Unmute' : '🔊 Mute';
      }
      if (Date.now() - lastMeterAt > 700) paintIdle(listenCanvas, listenCtx); // tumigil ang audio: ibalik sa standby
    }
    if (LISTEN_MODE) {
      L('listenModal').style.display = 'flex';
      refreshListen();
      setInterval(refreshListen, 500);
    }

    function initLobby() {
      fetch(RELAY_HTTP + '/rooms').catch(() => {}); // pang-gising sa libreng relay
      const urlRoom = (new URLSearchParams(location.search).get('room') || '').toUpperCase();
      if (ROOM_RE.test(urlRoom)) startRoom(urlRoom, 'join', '', '');
      else showLobby('Home');
    }

    initLobby();
  </script>
</body>
</html>
