# TP1DPBO2526C2

## JANJI
Saya Muhammad Hanif Muyassar dengan NIM 2510593 mengerjakan Tugas Praktikum 1 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

## FITUR UTAMA (CRUD)
- Tambah Data: Menambah objek baru.
- Tampilkan Data: Menampilkan semua objek yang tersimpan.
- Update Data: Mengubah data objek berdasarkan identifier unik (seperti ID).
- Hapus Data: Menghapus objek berdasarkan identifier unik (ID).
- Cari Data: Mencari satu objek spesifik.

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

## Cara Menjalankan

| Bahasa | Perintah |
|---|---|
| C++ | `g++ -std=c++17 -pthread -o cinema cpp/Main.cpp` lalu `./cinema` |
| Java | `javac java/Cinema.java java/Main.java` lalu `java -cp java Main` |
| Python | `python python/Main.py` |
| PHP | `php -S localhost:8000` di folder `php/`, buka `http://localhost:8000/index.php` |

