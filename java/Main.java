import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class Main {

    // Scanner global, semua input teks pakai nextLine supaya tidak bermasalah
    private static final Scanner sc = new Scanner(System.in);

    // list untuk menyimpan data objek
    private static final List<Cinema> Film = new ArrayList<>();

    // Mencari index objek dari sebuah id
    private static int cariIndexById(String id) {
        for (int i = 0; i < Film.size(); i++) {
            if (Film.get(i).getId().equals(id)) return i;
        }
        return -1;
    }

    // Utilitas untuk mengulang karakter (pengganti "-".repeat)
    private static String ulang(char c, int n) {
        StringBuilder sb = new StringBuilder();
        for (int i = 0; i < n; i++) sb.append(c);
        return sb.toString();
    }

    // Tabel dinamis: lebar kolom menyesuaikan data terpanjang
    private static void cetakTabel(String[] header, List<List<String>> rows) {
        int n = header.length;
        int[] lebar = new int[n];
        for (int i = 0; i < n; i++) lebar[i] = header[i].length();
        for (List<String> row : rows) {
            for (int i = 0; i < n; i++) {
                if (row.get(i).length() > lebar[i]) lebar[i] = row.get(i).length();
            }
        }
        for (int i = 0; i < n; i++) lebar[i] += 2;

        // fungsi untuk mencetak garis
        Runnable garis = () -> {
            for (int l : lebar) System.out.print("+" + ulang('-', l));
            System.out.println("+");
        };

        garis.run();
        StringBuilder hd = new StringBuilder();
        for (int i = 0; i < n; i++) hd.append(String.format("|%-" + lebar[i] + "s", " " + header[i]));
        hd.append("|");
        System.out.println(hd);
        garis.run();
        for (List<String> row : rows) {
            StringBuilder rb = new StringBuilder();
            for (int i = 0; i < n; i++) rb.append(String.format("|%-" + lebar[i] + "s", " " + row.get(i)));
            rb.append("|");
            System.out.println(rb);
        }
        garis.run();
    }

    // Error handling untuk input durasi film
    private static int inputDuration() {
        while (true) {
            System.out.print("Durasi (menit)  : ");
            try {
                int d = Integer.parseInt(sc.nextLine().trim());
                if (d < 40) {
                    System.out.println("Durasi film harus lebih dari atau sama dengan 40 menit!");
                    continue;
                }
                return d;
            } catch (NumberFormatException e) {
                System.out.println("Input harus berupa angka!");
            }
        }
    }

    // Error handling untuk input tahun rilis film
    private static int inputReleased() {
        while (true) {
            System.out.print("Tahun rilis     : ");
            try {
                int r = Integer.parseInt(sc.nextLine().trim());
                if (r < 1895 || r > 2026) {
                    System.out.println("Tahun rilis tidak valid (1895 - 2026)!");
                    continue;
                }
                return r;
            } catch (NumberFormatException e) {
                System.out.println("Input harus berupa angka!");
            }
        }
    }

    // Error handling untuk genre
    private static String inputGenre() {
        while (true) {
            System.out.print("Genre           : ");
            String g = sc.nextLine().trim();
            if (g.isEmpty()) {
                System.out.println("Genre tidak boleh kosong!");
                continue;
            }
            return g;
        }
    }

    // Error handling untuk title
    private static String inputTitle() {
        while (true) {
            System.out.print("Judul           : ");
            String t = sc.nextLine().trim();
            if (t.isEmpty()) {
                System.out.println("Title tidak boleh kosong!");
                continue;
            }
            return t;
        }
    }

    // Prosedur untuk menambahkan objek baru
    private static void tambahData() {
        System.out.println();
        System.out.println("--- Tambah Data Film ---");

        // id tidak boleh kosong dan tidak boleh duplikat
        String id;
        while (true) {
            System.out.print("ID              : ");
            id = sc.nextLine().trim();
            if (id.isEmpty()) {
                System.out.println("ID tidak boleh kosong!");
                continue;
            }
            if (cariIndexById(id) != -1) {
                System.out.println("ID sudah dipakai film lain!");
                continue;
            }
            break;
        }

        String title = inputTitle();
        String genre = inputGenre();
        int duration = inputDuration();
        int released = inputReleased();

        Film.add(new Cinema(id, title, genre, released, duration));
        System.out.println("Data film berhasil ditambahkan!");
    }

    // Prosedur untuk mencari data objek
    private static void cariData() {
        System.out.println();
        System.out.println("--- Cari Data Film ---");
        String keyword;
        while (true) {
            System.out.print("Judul/kata kunci: ");
            keyword = sc.nextLine().trim();
            if (keyword.isEmpty()) {
                System.out.println("Kata kunci tidak boleh kosong!");
                continue;
            }
            break;
        }

        String[] header = {"ID", "Title", "Genre", "Duration", "Released"};
        List<List<String>> rows = new ArrayList<>();
        for (Cinema f : Film) {
            if (f.getTitle().contains(keyword)) rows.add(f.toRow());
        }

        if (rows.isEmpty()) System.out.println("Film tidak ditemukan.");
        else cetakTabel(header, rows);
    }

    // Prosedur untuk menampilkan semua data objek
    private static void tampilkanData() {
        System.out.println();
        System.out.println("--- Daftar Film di Bioskop ---");

        // Jika tidak ada objek sama sekali
        if (Film.isEmpty()) {
            System.out.println("Belum ada data film.");
            return;
        }

        String[] header = {"ID", "Title", "Genre", "Duration", "Released"};
        List<List<String>> rows = new ArrayList<>();
        for (Cinema f : Film) rows.add(f.toRow());
        cetakTabel(header, rows);
    }

    // Prosedur untuk mengubah data objek
    private static void updateData() {
        System.out.println();
        System.out.println("--- Update Data Film ---");
        String id;
        while (true) {
            System.out.print("Masukkan ID film: ");
            id = sc.nextLine().trim();
            if (id.isEmpty()) {
                System.out.println("ID tidak boleh kosong!");
                continue;
            }
            break;
        }

        int idx = cariIndexById(id);
        if (idx == -1) {
            System.out.println("Film dengan ID tersebut tidak ditemukan.");
            return;
        }

        Cinema c = Film.get(idx);
        c.setTitle(inputTitle());
        c.setGenre(inputGenre());
        c.setDuration(inputDuration());
        c.setReleased(inputReleased());
        System.out.println("Data berhasil diupdate!");
    }

    // Prosedur untuk menghapus objek
    private static void hapusData() {
        System.out.println();
        System.out.println("--- Hapus Data Film ---");
        String id;
        while (true) {
            System.out.print("Masukkan ID film: ");
            id = sc.nextLine().trim();
            if (id.isEmpty()) {
                System.out.println("ID tidak boleh kosong!");
                continue;
            }
            break;
        }

        int idx = cariIndexById(id);
        if (idx == -1) {
            System.out.println("Film dengan ID tersebut tidak ditemukan.");
            return;
        }

        Film.remove(idx);
        System.out.println("Data berhasil dihapus!");
    }

    // Menampilkan semua command yg tersedia
    private static void tampilkanMenu() {
        System.out.println();
        System.out.println("===============================");
        System.out.println("     MANAJEMEN DATA CINEMA");
        System.out.println("===============================");
        System.out.println("1. Tambah Data Cinema");
        System.out.println("2. Tampilkan Semua Data Cinema");
        System.out.println("3. Update Data Cinema");
        System.out.println("4. Hapus Data Cinema");
        System.out.println("5. Cari Data Cinema");
        System.out.println("0. Keluar");
        System.out.print("Pilih menu: ");
    }

    // Animasi Exit
    private static void animasiKeluar() {
        System.out.print("\nMenutup program");
        for (int i = 0; i < 3; i++) {
            try { Thread.sleep(400); } catch (InterruptedException e) {}
            System.out.print(".");
            System.out.flush();
        }
        System.out.println("\n");

        String[] banner = {
            "#####  ####    ##       ###    ###  #   # ##### ",
            "  #    #   #    #       #  #  #   # ##  # #     ",
            "  #    ####     #       #  #  #   # # # # ####  ",
            "  #    #        #       #  #  #   # #  ## #     ",
            "  #    #       ###      ###    ###  #   # ##### "
        };

        for (String baris : banner) {
            try { Thread.sleep(150); } catch (InterruptedException e) {}
            System.out.println(baris);
        }
        System.out.println();
    }

    public static void main(String[] args) {
        int pilihan;

        do {
            tampilkanMenu();
            try {
                pilihan = Integer.parseInt(sc.nextLine().trim());
            } catch (NumberFormatException e) {
                System.out.println("Input harus berupa angka!");
                pilihan = -1;
                continue;
            }

            switch (pilihan) {
                case 1: tambahData(); break;
                case 2: tampilkanData(); break;
                case 3: updateData(); break;
                case 4: hapusData(); break;
                case 5: cariData(); break;
                case 0: animasiKeluar(); break;
                default: System.out.println("Pilihan tidak valid!");
            }
        } while (pilihan != 0);
    }
}