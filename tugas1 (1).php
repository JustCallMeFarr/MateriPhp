<?php
// ===== Inisialisasi variabel =====
$nama = "";
$email = "";
$nilai = "";
$keterangan = "";
$error = "";
$submitted = false;

// ===== Form processing (metode POST) =====
if (isset($_POST['proses'])) {
  $submitted = true;

  // Menangkap data
  $nama  = trim($_POST['nama'] ?? "");
  $email = trim($_POST['email'] ?? "");
  $nilai = trim($_POST['nilai'] ?? "");

  // Validasi sisi server
  if ($nama === "" || $email === "" || $nilai === "") {
    $error = "Semua field wajib diisi.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid.";
  } elseif (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
    $error = "Nilai ujian harus berupa angka 0 - 100.";
  } else {
    // ===== Struktur kendali if-else =====
    if ($nilai > 70) {
      $keterangan = "Lulus";
    } else {
      // nilai <= 70 (termasuk tepat 70) dianggap Remedial
      $keterangan = "Remedial";
    }
  }
}

$ok = $submitted && $error === "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tugas 1 - Form Nilai Ujian</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg1: #eef2ff;
      --bg2: #fdf2f8;
      --card: rgba(255, 255, 255, 0.82);
      --text: #0f172a;
      --muted: #64748b;
      --border: #e2e8f0;
      --input: #ffffff;
      --primary: #6366f1;
      --primary-2: #8b5cf6;
      --ok: #10b981;
      --ok-soft: #d1fae5;
      --bad: #f43f5e;
      --bad-soft: #ffe4e6;
      --shadow: 0 20px 50px -12px rgba(79, 70, 229, 0.25);
    }
    [data-theme="dark"] {
      --bg1: #0b1020;
      --bg2: #1a1033;
      --card: rgba(22, 27, 45, 0.85);
      --text: #e5e7eb;
      --muted: #94a3b8;
      --border: #2a3350;
      --input: #111729;
      --ok-soft: rgba(16, 185, 129, 0.18);
      --bad-soft: rgba(244, 63, 94, 0.18);
      --shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.6);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      color: var(--text);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      transition: background .3s, color .3s;
    }

    .card {
      width: 100%;
      max-width: 460px;
      background: var(--card);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: var(--shadow);
      padding: 32px 28px;
      animation: rise .6s cubic-bezier(.2, .8, .2, 1) both;
    }
    @keyframes rise { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

    .top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; }
    .sub { color: var(--muted); font-size: .9rem; margin-top: 4px; }

    .theme-btn {
      border: 1px solid var(--border);
      background: var(--input);
      color: var(--text);
      width: 40px; height: 40px;
      border-radius: 12px;
      cursor: pointer;
      font-size: 1.1rem;
      transition: transform .2s;
      flex-shrink: 0;
    }
    .theme-btn:hover { transform: rotate(18deg) scale(1.08); }

    form { margin-top: 24px; display: grid; gap: 18px; }

    .field label {
      display: block;
      font-size: .8rem;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: 6px;
      letter-spacing: .02em;
    }
    .input-wrap { position: relative; }
    .input-wrap .ico {
      position: absolute; left: 14px; top: 50%;
      transform: translateY(-50%);
      font-size: 1rem; opacity: .7; pointer-events: none;
    }
    .input-wrap input {
      width: 100%;
      padding: 13px 40px 13px 42px;
      border-radius: 14px;
      border: 1.5px solid var(--border);
      background: var(--input);
      color: var(--text);
      font: inherit;
      font-size: .95rem;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .input-wrap input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, .18);
    }
    .input-wrap .status {
      position: absolute; right: 14px; top: 50%;
      transform: translateY(-50%) scale(0);
      font-size: .95rem; font-weight: 700;
      transition: transform .2s;
    }
    .field.valid .input-wrap input { border-color: var(--ok); }
    .field.valid .status { transform: translateY(-50%) scale(1); color: var(--ok); }
    .field.invalid .input-wrap input { border-color: var(--bad); }
    .field.invalid .status { transform: translateY(-50%) scale(1); color: var(--bad); }
    .hint { font-size: .75rem; color: var(--bad); margin-top: 6px; min-height: 0; display: none; }
    .field.invalid .hint { display: block; }

    /* Slider */
    .slider-row { margin-top: 12px; }
    input[type="range"] {
      -webkit-appearance: none; appearance: none;
      width: 100%; height: 8px; border-radius: 99px; outline: none;
      background: linear-gradient(90deg, var(--bad) 0%, var(--bad) 70%, var(--ok) 70%, var(--ok) 100%);
      opacity: .9;
    }
    input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none; appearance: none;
      width: 22px; height: 22px; border-radius: 50%;
      background: #fff; border: 4px solid var(--primary);
      box-shadow: 0 2px 8px rgba(0, 0, 0, .25); cursor: grab;
    }
    input[type="range"]::-moz-range-thumb {
      width: 16px; height: 16px; border-radius: 50%;
      background: #fff; border: 4px solid var(--primary); cursor: grab;
    }
    .ticks { display: flex; justify-content: space-between; font-size: .7rem; color: var(--muted); margin-top: 6px; }

    /* Live preview */
    .preview {
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 16px; border-radius: 14px;
      background: var(--input); border: 1.5px dashed var(--border);
      font-size: .85rem; color: var(--muted);
      transition: background .25s, border-color .25s;
    }
    .badge {
      padding: 5px 14px; border-radius: 99px;
      font-weight: 700; font-size: .8rem;
      background: var(--border); color: var(--muted);
      transition: all .25s;
    }
    .preview.lulus { border-color: var(--ok); background: var(--ok-soft); }
    .preview.lulus .badge { background: var(--ok); color: #fff; }
    .preview.remedial { border-color: var(--bad); background: var(--bad-soft); }
    .preview.remedial .badge { background: var(--bad); color: #fff; }

    .actions { display: flex; gap: 10px; margin-top: 4px; }
    .btn {
      flex: 1; padding: 14px; border: none; border-radius: 14px;
      font: inherit; font-weight: 700; font-size: .95rem; cursor: pointer;
      transition: transform .15s, box-shadow .2s, opacity .2s;
    }
    .btn:active { transform: scale(.97); }
    .btn-primary {
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-2));
      box-shadow: 0 10px 24px -8px rgba(99, 102, 241, .6);
    }
    .btn-primary:hover { box-shadow: 0 14px 30px -8px rgba(99, 102, 241, .75); transform: translateY(-1px); }
    .btn-primary:disabled { opacity: .5; cursor: not-allowed; box-shadow: none; transform: none; }
    .btn-ghost { flex: 0 0 auto; padding: 14px 18px; background: var(--input); color: var(--text); border: 1.5px solid var(--border); }
    .btn-ghost:hover { border-color: var(--primary); }

    /* Hasil */
    .error {
      margin-top: 20px; padding: 14px 16px; border-radius: 14px;
      background: var(--bad-soft); color: var(--bad);
      font-size: .9rem; font-weight: 600;
      animation: shake .4s;
    }
    @keyframes shake { 20%,60% { transform: translateX(-6px); } 40%,80% { transform: translateX(6px); } }

    .result {
      margin-top: 24px; padding: 22px; border-radius: 20px;
      border: 1.5px solid var(--border); background: var(--input);
      animation: pop .5s cubic-bezier(.2, .9, .3, 1.3) both;
    }
    .result.lulus { border-color: var(--ok); background: var(--ok-soft); }
    .result.remedial { border-color: var(--bad); background: var(--bad-soft); }
    @keyframes pop { from { opacity: 0; transform: scale(.9); } to { opacity: 1; transform: none; } }

    .result-head { display: flex; align-items: center; gap: 18px; }
    .ring { position: relative; width: 92px; height: 92px; flex-shrink: 0; }
    .ring svg { transform: rotate(-90deg); }
    .ring .track { stroke: var(--border); }
    .ring .bar { transition: stroke-dashoffset 1.2s cubic-bezier(.3, .8, .3, 1); }
    .result.lulus .ring .bar { stroke: var(--ok); }
    .result.remedial .ring .bar { stroke: var(--bad); }
    .ring .num {
      position: absolute; inset: 0; display: grid; place-items: center;
      font-weight: 800; font-size: 1.4rem;
    }
    .verdict { font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
    .result.lulus .verdict { color: var(--ok); }
    .result.remedial .verdict { color: var(--bad); }
    .verdict-sub { font-size: .85rem; color: var(--muted); margin-top: 2px; }

    .detail { margin-top: 16px; display: grid; gap: 8px; font-size: .9rem; }
    .detail div { display: flex; justify-content: space-between; gap: 12px; padding-top: 8px; border-top: 1px solid var(--border); }
    .detail span:first-child { color: var(--muted); }
    .detail span:last-child { font-weight: 600; text-align: right; word-break: break-all; }

    #confetti { position: fixed; inset: 0; pointer-events: none; z-index: 50; }

    @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
  </style>
</head>
<body>
  <canvas id="confetti"></canvas>

  <main class="card">
    <div class="top">
      <div>
        <h1>Form Nilai Ujian</h1>
        <p class="sub">Isi data di bawah, status kelulusan langsung terlihat.</p>
      </div>
      <button type="button" class="theme-btn" id="themeBtn" title="Ganti tema" aria-label="Ganti tema">🌙</button>
    </div>

    <form method="POST" id="form" novalidate>
      <div class="field" id="f-nama">
        <label for="nama">NAMA</label>
        <div class="input-wrap">
          <span class="ico">👤</span>
          <input type="text" id="nama" name="nama" placeholder="Nama lengkap" autocomplete="name"
                 value="<?= htmlspecialchars($nama) ?>" />
          <span class="status"></span>
        </div>
        <div class="hint">Nama minimal 2 karakter.</div>
      </div>

      <div class="field" id="f-email">
        <label for="email">EMAIL</label>
        <div class="input-wrap">
          <span class="ico">✉️</span>
          <input type="email" id="email" name="email" placeholder="nama@email.com" autocomplete="email"
                 value="<?= htmlspecialchars($email) ?>" />
          <span class="status"></span>
        </div>
        <div class="hint">Format email belum valid.</div>
      </div>

      <div class="field" id="f-nilai">
        <label for="nilai">NILAI UJIAN</label>
        <div class="input-wrap">
          <span class="ico">📝</span>
          <input type="number" id="nilai" name="nilai" min="0" max="100" placeholder="0 - 100"
                 inputmode="numeric" value="<?= htmlspecialchars($nilai) ?>" />
          <span class="status"></span>
        </div>
        <div class="slider-row">
          <input type="range" id="slider" min="0" max="100" value="<?= is_numeric($nilai) ? (int)$nilai : 0 ?>" aria-label="Geser nilai" />
          <div class="ticks"><span>0</span><span>Batas 70</span><span>100</span></div>
        </div>
        <div class="hint">Nilai harus angka 0 - 100.</div>
      </div>

      <div class="preview" id="preview">
        <span>Pratinjau status</span>
        <span class="badge" id="badge">Belum ada nilai</span>
      </div>

      <div class="actions">
        <button type="button" class="btn btn-ghost" id="resetBtn">Reset</button>
        <button type="submit" class="btn btn-primary" name="proses" value="1" id="submitBtn" disabled>Proses</button>
      </div>
    </form>

    <?php if ($error !== ""): ?>
      <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php elseif ($ok): ?>
      <section class="result <?= strtolower($keterangan) ?>" id="result" data-skor="<?= (float)$nilai ?>" data-lulus="<?= $keterangan === 'Lulus' ? '1' : '0' ?>">
        <div class="result-head">
          <div class="ring">
            <svg width="92" height="92" viewBox="0 0 92 92">
              <circle class="track" cx="46" cy="46" r="40" fill="none" stroke-width="9"/>
              <circle class="bar" id="bar" cx="46" cy="46" r="40" fill="none" stroke-width="9"
                      stroke-linecap="round" stroke-dasharray="251.33" stroke-dashoffset="251.33"/>
            </svg>
            <div class="num" id="num">0</div>
          </div>
          <div>
            <div class="verdict"><?= $keterangan === 'Lulus' ? '🎉 ' : '📚 ' ?><?= $keterangan ?></div>
            <div class="verdict-sub">
              <?= $keterangan === 'Lulus' ? 'Selamat, kamu melewati batas nilai 70!' : 'Tetap semangat, ikuti remedial ya.' ?>
            </div>
          </div>
        </div>
        <div class="detail">
          <div><span>Nama</span><span><?= htmlspecialchars($nama) ?></span></div>
          <div><span>Email</span><span><?= htmlspecialchars($email) ?></span></div>
          <div><span>Nilai Ujian</span><span><?= htmlspecialchars($nilai) ?></span></div>
          <div><span>Keterangan</span><span><?= $keterangan ?></span></div>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <script>
    // ===== Tema gelap / terang =====
    const root = document.documentElement;
    const themeBtn = document.getElementById('themeBtn');
    function setTheme(t) {
      root.setAttribute('data-theme', t);
      themeBtn.textContent = t === 'dark' ? '☀️' : '🌙';
      try { localStorage.setItem('theme', t); } catch (e) {}
    }
    let saved = null;
    try { saved = localStorage.getItem('theme'); } catch (e) {}
    setTheme(saved || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
    themeBtn.addEventListener('click', () => setTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'));

    // ===== Validasi & pratinjau langsung =====
    const $ = id => document.getElementById(id);
    const nama = $('nama'), email = $('email'), nilai = $('nilai'), slider = $('slider');
    const preview = $('preview'), badge = $('badge'), submitBtn = $('submitBtn');
    const touched = { nama: false, email: false, nilai: false };

    const rules = {
      nama:  v => v.trim().length >= 2,
      email: v => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()),
      nilai: v => v !== '' && !isNaN(v) && +v >= 0 && +v <= 100
    };

    function mark(key, el) {
      const field = $('f-' + key), ok = rules[key](el.value);
      field.classList.remove('valid', 'invalid');
      const status = field.querySelector('.status');
      if (el.value !== '' || touched[key]) {
        field.classList.add(ok ? 'valid' : 'invalid');
        status.textContent = ok ? '✓' : '✕';
      }
      return ok;
    }

    function updatePreview() {
      preview.classList.remove('lulus', 'remedial');
      if (!rules.nilai(nilai.value)) {
        badge.textContent = 'Belum ada nilai';
        return;
      }
      const lulus = +nilai.value > 70;   // sama dengan aturan di PHP
      preview.classList.add(lulus ? 'lulus' : 'remedial');
      badge.textContent = lulus ? '✅ Lulus' : '📚 Remedial';
    }

    function refresh() {
      const a = mark('nama', nama), b = mark('email', email), c = mark('nilai', nilai);
      updatePreview();
      submitBtn.disabled = !(a && b && c);
    }

    [nama, email, nilai].forEach(el => {
      el.addEventListener('input', () => { touched[el.id] = true; refresh(); });
      el.addEventListener('blur', () => { touched[el.id] = true; refresh(); });
    });

    // Sinkron slider <-> input angka
    slider.addEventListener('input', () => { nilai.value = slider.value; touched.nilai = true; refresh(); });
    nilai.addEventListener('input', () => {
      if (rules.nilai(nilai.value)) slider.value = nilai.value;
    });

    // Reset: kosongkan semua termasuk hasil
    $('resetBtn').addEventListener('click', () => {
      [nama, email, nilai].forEach(el => el.value = '');
      slider.value = 0;
      Object.keys(touched).forEach(k => touched[k] = false);
      ['nama', 'email', 'nilai'].forEach(k => {
        $('f-' + k).classList.remove('valid', 'invalid');
        $('f-' + k).querySelector('.status').textContent = '';
      });
      const res = $('result'); if (res) res.remove();
      const err = document.querySelector('.error'); if (err) err.remove();
      refresh();
      nama.focus();
    });

    refresh();

    // ===== Animasi hasil (lingkaran skor + hitung angka) =====
    const result = $('result');
    if (result) {
      const skor = parseFloat(result.dataset.skor);
      const lulus = result.dataset.lulus === '1';
      const bar = $('bar'), num = $('num'), C = 251.33;

      requestAnimationFrame(() => {
        bar.style.strokeDashoffset = C - (C * Math.min(skor, 100) / 100);
      });

      const start = performance.now(), dur = 1200;
      (function tick(now) {
        const p = Math.min((now - start) / dur, 1), e = 1 - Math.pow(1 - p, 3);
        num.textContent = Math.round(skor * e);
        if (p < 1) requestAnimationFrame(tick);
      })(start);

      result.scrollIntoView({ behavior: 'smooth', block: 'center' });
      if (lulus && !matchMedia('(prefers-reduced-motion: reduce)').matches) confetti();
    }

    // ===== Confetti sederhana (tanpa library) =====
    function confetti() {
      const cv = $('confetti'), ctx = cv.getContext('2d');
      cv.width = innerWidth; cv.height = innerHeight;
      const colors = ['#6366f1', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#38bdf8'];
      const parts = Array.from({ length: 120 }, () => ({
        x: cv.width / 2 + (Math.random() - .5) * 120,
        y: cv.height * .55,
        vx: (Math.random() - .5) * 12,
        vy: -Math.random() * 14 - 4,
        s: Math.random() * 7 + 4,
        r: Math.random() * Math.PI,
        vr: (Math.random() - .5) * .3,
        c: colors[(Math.random() * colors.length) | 0]
      }));
      let frames = 0;
      (function loop() {
        ctx.clearRect(0, 0, cv.width, cv.height);
        parts.forEach(p => {
          p.vy += .35; p.x += p.vx; p.y += p.vy; p.r += p.vr;
          ctx.save(); ctx.translate(p.x, p.y); ctx.rotate(p.r);
          ctx.fillStyle = p.c; ctx.fillRect(-p.s / 2, -p.s / 2, p.s, p.s * .6);
          ctx.restore();
        });
        if (++frames < 150) requestAnimationFrame(loop);
        else ctx.clearRect(0, 0, cv.width, cv.height);
      })();
    }
  </script>
</body>
</html>
