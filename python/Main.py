from Cinema import Cinema

# 
import time
import sys

# Tabel dinamis : Lebar kolom menyesuaikan data terpanjang
def cetak_tabel(header, rows):
    n = len(header)
    # loop untuk set nilai lebar ke-i sesuai panjang header
    lebar = [len(h) for h in header]
    # bandingkan dengan atribut objek untuk mencari lebar terpanjang
    for row in rows:
        for i in range(n):
            if len(row[i]) > lebar[i]:
                lebar[i] = len(row[i])
    # ditambah dua untuk jarak dengan "|"
    lebar = [l + 2 for l in lebar]

    # fungsi untuk mencetak garis
    def garis():
        print("".join("+" + "-" * l for l in lebar) + "+")

    garis()
    print("".join("|" + " " + header[i].ljust(lebar[i] - 1) for i in range(n)) + "|")
    garis()
    for row in rows:
        print("".join("|" + " " + row[i].ljust(lebar[i] - 1) for i in range(n)) + "|")
    garis()


# --------------- CRUD ---------------

# list untuk menyimpan data objek
Film = []

# Mencari index objek dari sebuah id
def cari_index_by_id(id):
    for i, f in enumerate(Film):
        if f.get_id() == id:
            return i
    return -1


# Error handling untuk input durasi film
def input_duration():
    while True:
        try:
            d = int(input("Durasi (menit)  : "))
        except ValueError:
            print("Input harus berupa angka!")
            continue
        if d < 40:
            print("Durasi film harus lebih dari atau sama dengan 40 menit!")
            continue
        return d


# Error handling untuk input tahun rilis film
def input_released():
    while True:
        try:
            r = int(input("Tahun rilis     : "))
        except ValueError:
            print("Input harus berupa angka!")
            continue
        # jika inputan tidak dalam rentang yg ditentukan
        if r < 1895 or r > 2026:
            print("Tahun rilis tidak valid (1895 - 2026)!")
            continue
        return r


# Error handling untuk genre
def input_genre():
    while True:
        g = input("Genre           : ").strip()
        if not g:
            print("Genre tidak boleh kosong!")
            continue
        return g


# Error handling untuk title
def input_title():
    while True:
        t = input("Judul           : ").strip()
        if not t:
            print("Title tidak boleh kosong!")
            continue
        return t


# prosedur untuk menambahkan objek baru
def tambah_data():
    print("\n--- Tambah Data Film ---")
    # id tidak boleh kosong dan tidak boleh duplikat
    while True:
        id = input("ID              : ").strip()
        if not id:
            print("ID tidak boleh kosong!")
            continue
        if cari_index_by_id(id) != -1:
            print("ID sudah dipakai film lain!")
            continue
        break

    title = input_title()
    genre = input_genre()
    duration = input_duration()
    released = input_released()

    Film.append(Cinema(id, title, genre, released, duration))
    print("Data film berhasil ditambahkan!")


# Prosedur untuk mencari data objek
def cari_data():
    print("\n--- Cari Data Film ---")
    while True:
        keyword = input("Judul/kata kunci: ").strip()
        if not keyword:
            print("Kata kunci tidak boleh kosong!")
            continue
        break

    header = ["ID", "Title", "Genre", "Duration", "Released"]
    rows = [f.to_row() for f in Film if keyword in f.get_title()]

    if not rows:
        print("Film tidak ditemukan.")
    else:
        cetak_tabel(header, rows)


# Prosedur untuk menampilkan semua data objek
def tampilkan_data():
    print("\n--- Daftar Film di Bioskop ---")

    # Jika tidak ada objek sama sekali
    if not Film:
        print("Belum ada data film.")
        return

    header = ["ID", "Title", "Genre", "Duration", "Released"]
    rows = [f.to_row() for f in Film]
    cetak_tabel(header, rows)


# Prosedur untuk mengubah data objek
def update_data():
    print("\n--- Update Data Film ---")
    while True:
        id = input("Masukkan ID film: ").strip()
        if not id:
            print("ID tidak boleh kosong!")
            continue
        break

    idx = cari_index_by_id(id)
    if idx == -1:
        print("Film dengan ID tersebut tidak ditemukan.")
        return

    title = input_title()
    genre = input_genre()
    duration = input_duration()
    released = input_released()

    Film[idx].set_title(title)
    Film[idx].set_genre(genre)
    Film[idx].set_duration(duration)
    Film[idx].set_released(released)
    print("Data berhasil diupdate!")


# Prosedur untuk menghapus objek
def hapus_data():
    print("\n--- Hapus Data Film ---")
    while True:
        id = input("Masukkan ID film: ").strip()
        if not id:
            print("ID tidak boleh kosong!")
            continue
        break

    idx = cari_index_by_id(id)
    if idx == -1:
        print("Film dengan ID tersebut tidak ditemukan.")
        return

    Film.pop(idx)
    print("Data berhasil dihapus!")


# Menampilkan semua command yg tersedia
def tampilkan_menu():
    print("\n===============================")
    print("     MANAJEMEN DATA CINEMA")
    print("===============================")
    print("1. Tambah Data Cinema")
    print("2. Tampilkan Semua Data Cinema")
    print("3. Update Data Cinema")
    print("4. Hapus Data Cinema")
    print("5. Cari Data Cinema")
    print("0. Keluar")
    print("Pilih menu: ", end="")


# Animasi Exit
def animasi_keluar():
    print("\nMenutup program", end="")
    for _ in range(3):
        time.sleep(0.4)
        print(".", end="")
        sys.stdout.flush()
    print("\n")

    banner = [
        "#####  ####    ##       ###    ###  #   # ##### ",
        "  #    #   #    #       #  #  #   # ##  # #     ",
        "  #    ####     #       #  #  #   # # # # ####  ",
        "  #    #        #       #  #  #   # #  ## #     ",
        "  #    #       ###      ###    ###  #   # ##### ",
    ]

    for baris in banner:
        time.sleep(0.15)
        print(baris)
    print()


def main():
    while True:
        tampilkan_menu()
        try:
            pilihan = int(input())
        except ValueError:
            print("Input harus berupa angka!")
            continue

        if pilihan == 1:
            tambah_data()
        elif pilihan == 2:
            tampilkan_data()
        elif pilihan == 3:
            update_data()
        elif pilihan == 4:
            hapus_data()
        elif pilihan == 5:
            cari_data()
        elif pilihan == 0:
            animasi_keluar()
            break
        else:
            print("Pilihan tidak valid!")


if __name__ == "__main__":
    main()