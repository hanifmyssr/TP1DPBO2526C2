# TP1DPBO2526C2

## JANJI
Saya Muhammad Hanif Muyassar dengan NIM 2510593 mengerjakan Tugas Praktikum 1 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## FITUR UTAMA & FLOW KODE
- Tambah Data: Menambah objek baru.
- Tampilkan Data: Menampilkan semua objek yang tersimpan.
- Update Data: Mengubah data objek berdasarkan identifier unik (seperti ID).
- Hapus Data: Menghapus objek berdasarkan identifier unik (ID).
- Cari Data: Mencari satu objek spesifik.

### Class `Cinema`

Satu class dipakai konsisten di keempat bahasa, merepresentasikan satu data film.

| Atribut    | Tipe    | Keterangan                                             |
|------------|---------|----------------------------------------------------------|
| `id`       | string  | Identifier unik film (diisi manual, harus unik)          |
| `title`    | string  | Judul film                                                |
| `genre`    | string  | Genre film                                                |
| `duration` | int     | Durasi film dalam menit                                   |
| `released` | int     | Tahun rilis film                                           |
| `foto`     | string  | *(khusus versi PHP)* Path lokal gambar/poster film, bukan URL |

Setiap atribut bersifat `private`, diakses lewat method **getter/setter**. Data disimpan dalam **list/array of object** (`vector<Cinema>` di C++, `ArrayList<Cinema>` di Java, `list` di Python, array PHP di dalam `$_SESSION`).

### Flow Kode — Versi CLI (C++, Java, Python)

1. Program menampilkan **menu angka** (1–5, 0 untuk keluar) dalam perulangan `do-while` / `while True`
2. Input pilihan menu divalidasi — kalau bukan angka, program menampilkan pesan error dan **tidak** menutup program (pilihan direset ke nilai selain 0, supaya tidak salah kena kondisi keluar)
3. Setiap aksi (`tambahData`, `updateData`, dst.) memanggil fungsi input terpisah yang **mengulang input sampai valid**:
   - `id`, `title`, `genre`, dan keyword pencarian → tidak boleh kosong
   - `id` baru → tidak boleh duplikat dengan `id` yang sudah ada
   - `duration` → harus angka dan minimal 40 menit
   - `released` → harus angka dan berada di rentang 1895–2026 (1895 dipilih karena itu tahun pemutaran komersial film pertama oleh Lumière bersaudara)
4. Fungsi `cetakTabel()` menghitung lebar tiap kolom secara **dinamis** (menyesuaikan data terpanjang di kolom itu, dibandingkan dengan lebar header), baru kemudian mencetak tabel dengan border `+---+`
5. Saat memilih menu `0`, program menjalankan animasi keluar (efek loading titik-titik + banner ASCII) sebelum program berhenti

### Flow Kode — Versi Web (PHP)

1. `Cinema.php` di-`require` **sebelum** `session_start()` dipanggil — urutan ini penting, karena kalau dibalik, PHP akan gagal mengenali class `Cinema` saat meng-*unserialize* data session, sehingga atribut object film yang tersimpan bisa hilang
2. Data disimpan di `$_SESSION["film"]` (array of object `Cinema`) selama sesi browser berlangsung — sesuai ketentuan tugas, **tidak** menggunakan database
3. Alur setiap request:
   - `GET index.php?action=hapus&id=...` → menghapus data berdasarkan ID
   - `GET index.php?action=update&id=...` → menyiapkan form update terisi data lama
   - `GET index.php?cari=1&keyword=...` → mencari film berdasarkan judul
   - `POST index.php` → menambah data baru, atau memperbarui data lama (dibedakan lewat hidden field `update_id`)
4. Validasi dilakukan di sisi server (bukan cuma HTML5 `min`/`max`) — field kosong, durasi minimal 40 menit, tahun rilis 1895–2026, dan ID duplikat semuanya dicek ulang sebelum data disimpan
5. Atribut `foto` menyimpan **path lokal** (misal `img/parasite.jpg`), bukan URL — gambar poster diletakkan manual di folder `php/img/`
6. Semua output ditulis lewat `htmlspecialchars()` untuk mencegah XSS

## Error Handling di semua program
- ID tidak boleh kosong, dan input akan diminta ulang sampai diisi.<br>
  <img width="307" height="103" alt="ID tidak boleh kosong" src="dokumentasi/error handling/Screenshot 2026-09-20 134224.png" />

- ID yang baru tidak boleh duplikat dengan ID yang sudah ada.<br>
  <img width="328" height="102" alt="ID sudah dipakai" src="dokumentasi/error handling/Screenshot 2026-09-20 134716.png" />

- Durasi wajib berupa angka; kalau bukan angka, program meminta input ulang.<br>
  <img width="286" height="81" alt="Durasi harus angka" src="dokumentasi/error handling/Screenshot 2026-09-20 134317.png" />

- Durasi minimal 40 menit, dan tahun rilis harus berada di rentang 1895–2026 (1895 dipilih karena itu tahun pemutaran komersial film pertama oleh Lumière bersaudara).<br>
  <img width="514" height="183" alt="Validasi durasi dan tahun rilis" src="dokumentasi/error handling/Screenshot 2026-09-20 134440.png" />

- Pilihan menu yang tidak tersedia (misal mengetik angka di luar 0–5) akan ditolak tanpa menutup program.<br>
  <img width="348" height="247" alt="Pilihan menu tidak valid" src="dokumentasi/error handling/Screenshot 2026-09-20 140334.png" />

## DOKUMENTASI OUTPUT
Program dapat menambahkan, menampilkan, memperbarui, menghapus, dan mencari data film.

## Output program C++
### Menambahkan data
<img width="342" height="411" alt="Tambah data C++" src="dokumentasi/cpp/Insert-cpp.png" />

### Menampilkan semua data
<img width="628" height="411" alt="Tampilkan data C++" src="dokumentasi/cpp/Show-cpp.png" />

### Memperbarui data
<img width="399" height="417" alt="Update data C++" src="dokumentasi/cpp/Update-cpp.png" />

### Mencari film
<img width="585" height="411" alt="Cari data C++" src="dokumentasi/cpp/Find-cpp.png" />

### Menghapus data
<img width="640" height="696" alt="Hapus data C++" src="dokumentasi/cpp/Delete-cpp.png" />


## Output program Java
### Menambahkan data
<img width="340" height="411" alt="Tambah data Java" src="dokumentasi/java/Insert-java.png" />

### Menampilkan semua data
<img width="631" height="433" alt="Tampilkan data Java" src="dokumentasi/java/Show-java.png" />

### Memperbarui data
<img width="685" height="607" alt="Update data Java" src="dokumentasi/java/Update-java.png" />

### Mencari film
<img width="664" height="429" alt="Cari data Java" src="dokumentasi/java/Find-java.png" />

### Menghapus data
<img width="660" height="504" alt="Hapus data Java" src="dokumentasi/java/Delete-java.png" />


## Output program Python
### Menambahkan data
<img width="802" height="451" alt="Tambah data Python" src="dokumentasi/python/Insert-py.png" />

### Menampilkan semua data
<img width="739" height="400" alt="Tampilkan data Python" src="dokumentasi/python/Show-py.png" />

### Memperbarui data
<img width="717" height="561" alt="Update data Python" src="dokumentasi/python/Update-py.png" />

### Mencari film
<img width="373" height="330" alt="Cari data Python" src="dokumentasi/python/Find-py.png" />

### Menghapus data
<img width="357" height="430" alt="Hapus data Python" src="dokumentasi/python/Delete-py.png" />


## Output program PHP
### Tampilan awal
<img width="1591" height="928" alt="Tampilan awal PHP" src="dokumentasi/php/TampilanAwal-php.png" />

### Menambahkan data
<img width="1486" height="630" alt="Tambah data PHP" src="dokumentasi/php/Insert-php.png" />

### Menampilkan semua data
<img width="1512" height="766" alt="Tampilkan data PHP" src="dokumentasi/php/Show-php.png" />

### Memperbarui data
<img width="1554" height="625" alt="Form update PHP" src="dokumentasi/php/Update-php.png" />
<br>
Hasil setelah update:<br>
<img width="1513" height="781" alt="Hasil update PHP" src="dokumentasi/php/AfterUpdate-php.png" />

### Mencari film
Pencarian bersifat *case-insensitive* (huruf besar/kecil dianggap sama).<br>
<img width="1504" height="451" alt="Cari data PHP" src="dokumentasi/php/Find-php.png" />

### Menghapus data
<img width="1507" height="646" alt="Hapus data PHP" src="dokumentasi/php/Delete-php.png" />

---