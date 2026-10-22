# Tugas 1 PHP - Struktur Kendali dan Form Processing

Form sederhana dengan input **Nama**, **Email**, dan **Nilai Ujian**.

## Aturan
- Nilai ujian > 70 → **Lulus**
- Nilai ujian ≤ 70 → **Remedial**

## Cara Menjalankan
1. Install XAMPP, lalu start **Apache**.
2. Salin `tugas1.php` ke folder `C:\xampp\htdocs\` (misalnya `htdocs/tugas1/`).
3. Buka `http://localhost/tugas1/tugas1.php` di browser.

## Konsep yang Dipakai
- Form processing dengan metode POST (`$_POST`, `isset`)
- Struktur kendali `if - elseif - else`
- Validasi input & `htmlspecialchars()` untuk keamanan output
