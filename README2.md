# School Competition Informational System

Sebuah website yang memungkinkan siswa untuk menelusuri perlombaan, kegiatan, atau acara akademik maupun non-akademik. Website juga menyediakan profil bawaan untuk semua siswa SMK Negeri 1 Denpasar guna memperlihatkan pencapaian mereka sebagai prestasi.

---

## Sasaran (Progres 100%)

### Siswa
1. Halaman penambahan data siswa (registrasi mandiri).
2. Halaman login akun siswa beserta session management.
3. Halaman detail profil siswa.
   - Daftar pencapaian dari perlombaan (dikelola admin).
   - Daftar lomba yang diikuti beserta status (Pending/Diterima/Ditolak).
   - Fitur edit profil siswa (upload foto profil, hapus foto, edit deskripsi).
4. Halaman daftar perlombaan.
5. Halaman detail lomba.
   - Foto thumbnail, deskripsi, nama guru pendamping, tanggal pelaksanaan.
   - Tautan menuju postingan lomba di platform lain (jumlah dinamis).
   - Tombol daftar lomba (hanya tampil jika lomba terbuka dan siswa belum terdaftar).
   - Status pendaftaran real-time (Pending, Diterima, Ditolak).
   - Opsi daftar ulang jika pendaftaran ditolak.
6. Fitur penelusuran.
   - Pencarian siswa berdasarkan nama, NIS, atau kelas.
   - Pencarian lomba berdasarkan judul atau jurusan.
   - Badge status lomba (Terbuka/Tertutup).
7. Sistem notifikasi.
   - Notifikasi saat pendaftaran diterima atau ditolak.
   - Notifikasi pengumuman dari admin.

### Admin
8. Halaman login admin (terpisah dari login siswa).
9. Dashboard admin dengan statistik (total siswa, lomba, pending, lomba berlangsung).
10. Kelola lomba (CRUD lengkap).
    - Tambah lomba (thumbnail, ikon, tautan eksternal dinamis, pilih jurusan).
    - Edit lomba (update semua field, hapus/ganti thumbnail dan ikon).
    - Hapus lomba (cascade ke pendaftaran, pencapaian, tautan, notifikasi).
    - Pencarian lomba.
    - Lihat daftar pendaftar per lomba.
11. Kelola siswa.
    - Lihat daftar siswa dengan pencarian.
    - Tambah akun siswa langsung dari panel admin.
    - Hapus siswa (individual dan bulk select).
    - Kelola pencapaian per siswa (tambah/hapus, pilih lomba, pilih hasil).
12. Kelola pendaftaran lomba.
    - Terima, tolak, atau batalkan status pendaftaran.
    - Notifikasi otomatis ke siswa saat status berubah.
    - Pencarian berdasarkan nama, lomba, atau status.
    - Hapus siswa dari daftar lomba.
13. Pengumuman.
    - Kirim ke semua siswa, siswa tertentu, atau siswa di lomba tertentu.
    - Filter riwayat pengumuman (hari ini, kemarin, 7 hari lalu, semua).
    - Hapus pengumuman (individual dan bulk select).

### Umum
14. Popup konfirmasi custom (menggantikan browser alert()).
15. Multiple select dengan bulk action (kelola siswa, pengumuman).
16. Foto profil konsisten di seluruh halaman setelah login.
17. Navigasi responsif antara halaman siswa dan panel admin.

---

## Keperluan

### Software
1. VSCode sebagai editor kode.
2. XAMPP untuk localhost PHP dan MySQL di phpMyAdmin.

### Bahasa
1. HTML, CSS, dan JavaScript untuk sisi front-end.
2. Bahasa PHP sebagai bahasa pemrograman server / back-end.
3. MySQL sebagai penyimpanan data (database).

---

## Database

Nama database: db_scis

## Progres

### 4 Februari 2026
1. Merancang halaman formulir HTML pendaftaran siswa.
2. Merancang database SCIS beserta tabel jurusan, kelas, dan siswa.
3. Berhasil menghubungkan pendaftaran akun dengan database.

### 11 Februari 2026
1. Membuat repositori Github untuk memusatkan kinerja proyek.

### Februari - Mei 2026
1. Membangun seluruh halaman siswa (beranda, lomba, profil, login, registrasi).
2. Membangun panel admin lengkap (dashboard, CRUD lomba, CRUD siswa, pendaftaran, pengumuman).
3. Mengimplementasi session management untuk siswa dan admin.
4. Mengimplementasi sistem notifikasi real-time.
5. Membangun fitur pendaftaran lomba dengan status tracking.
6. Menambahkan fitur pencapaian siswa yang dikelola admin.
7. Menambahkan fitur pencarian di seluruh halaman yang memerlukan.
8. Menambahkan multiple select dengan bulk action.
9. Mengganti browser alert() dengan popup konfirmasi custom.
10. Memastikan konsistensi foto profil di seluruh halaman.
11. Menyelesaikan seluruh bug dan edge case.
12. Project dinyatakan 100% selesai dan siap dipresentasikan.