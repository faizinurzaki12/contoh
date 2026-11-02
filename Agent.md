# Laporan Pengecekan & Pengujian Aplikasi Museum Karya SMKN 4 Tasikmalaya

## 1. Pengujian Fungsionalitas & Autentikasi

	- Beranda & Cache Publik: Pengujian pembacaan data publik melalui `MuseumCache::remember(...)` berjalan lancar tanpa error.
	- Registrasi & Kode Undangan: Validasi masa berlaku, batas pemakaian, serta status aktif/nonaktif kode undangan per jurusan berfungsi dengan benar.
	- Login Google (Socialite): Pengujian alur login Google dan kewajiban memasukkan kode undangan bagi akun baru.

## 2. Pengujian Sisi Siswa (Role: 2)

	- Sidebar & Navigasi: Akses halaman Dashboard, Profil, My Karya Gue, dan Kirim Project sesuai batasan peran.
	- Form Upload Karya:
		- Penyesuaian otomatis form berdasarkan jurusan akun siswa yang login (PPLG, TKJ, DKV, TOI, TSM).
		- Validasi wajib link GitHub khusus jurusan PPLG.
		- Kolom input akun Instagram khusus siswa jurusan TJKT dan TSM.
		- Kompresi gambar di sisi klien (`public/assets/js`).

## 3. Pengujian Sisi Admin & Super Admin

	- Moderasi Admin Jurusan: Pembatasan akses admin (`/admin/*`) hanya pada data siswa dan karya dari jurusan yang sama.


	- Aksi Approve & Reject: Pengujian catatan persetujuan dan alasan penolakan karya, serta pemanggilan `MuseumCache::flush(...)` pada grup data terkait (`karya`, `stat`, `artikel`, `komentar.{id}`).


	- Akses Global Super Admin: Pengujian manajemen admin dan visibilitas lintas jurusan pada route `/superadmin/*`.



## 4. Pengujian Desain Neubrutalism & Performa Server

	- Desain & Tampilan: Verifikasi penerapan border tebal 3px (`#1e293b`), bayangan keras tanpa blur, elemen squircle (`rounded-xl` / `rounded-2xl`), font Plus Jakarta Sans, helper CSS (`.neo-border`, `.neo-shadow`), serta ikon SVG inline.
	- Simulasi Hardware Terbatas (RAM 1GB, CPU Rendah, Storage 60GB):
	- Pengujian stabilitas memori PHP saat memuat cache file dan query berelasi.
	-Pengujian beban I/O penyimpanan terhadap direktori upload dan file log.

## Larangan 
- jangan git push dan juga git add
- meskipun di branch main jangan push 
- test sampai lulus 
- jangan pernah git add dan juga commit 
- jangan push di branch main 
- sekali lagi jangan sentuh git branch 
- jangan push 
##Format Laporan Temuan

	- bug di bagian .....
	- kurang tampilan desain .....
	- dan seterusnya .....