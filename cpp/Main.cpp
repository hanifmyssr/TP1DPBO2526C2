#include "Cinema.cpp"

// Tabel dinamis : Lebar kolom menyesuaikan data terpanjang
// const & = parameter cuma dipinjam untuk dibaca, tidak dicopy dan tidak diubah
void cetakTabel(const vector<string>& header, const vector<vector<string>>& rows) {
    int n = header.size();
    vector<size_t> lebar(n);
    // loop untuk set nilai lebar ke-i sesuai panjang header
    for (int i = 0; i < n; i++) lebar[i] = header[i].size();
    // bandingkan dengan atribut objek untuk mencari lebar terpanjang
    for (const auto& row : rows)
        for (int i = 0; i < n; i++)
            if (row[i].size() > lebar[i]) lebar[i] = row[i].size();
    // ditambah dua untuk jarak dengan "|"
    for (auto& l : lebar) l += 2; 

    // lambda function untuk mencetak garis
    auto garis = [&]() {
        for (size_t l : lebar) cout << "+" << string(l, '-');
        cout << "+" << endl;
    };

    garis();
    cout << left; // supaya rata kiri karena pakai setw
    // cetak header
    for (int i = 0; i < n; i++) cout << "|" << setw(lebar[i]) << (" " + header[i]);
    cout << "|" << endl;
    garis();
    // cetak objek
    for (const auto& row : rows) {
        for (int i = 0; i < n; i++) cout << "|" << setw(lebar[i]) << (" " + row[i]);
        cout << "|" << endl;
    }
    garis();
}

// --------------- CRUD ---------------

// vector untuk menyimpan data objek
vector<Cinema> Film;

// Mencari index objek dari sebuah id
int cariIndexById(string id) {
    for (int i = 0; i < (int)Film.size(); i++)
        if (Film[i].getId() == id) return i;
    return -1;
}

// Error handling untuk input durasi film
int inputDuration() {
    int d;
    while (true) {
        cout << "Durasi (menit)  : ";
        cin >> d;
        // jika inputan bukan angka
        if (cin.fail()) {
            cin.clear();
            cin.ignore(1000, '\n');
            cout << "Input harus berupa angka!\n";
            continue;
        }
        // jika inputan lebih kecil dari 40
        if (d < 40) {
            cout << "Durasi film harus lebih dari atau sama dengan 40 menit!\n";
            continue;
        }
        return d;
    }
}

// Error handling untuk input tahun rilis film
int inputReleased() {
    int r;
    while (true) {
        cout << "Tahun rilis     : ";
        cin >> r;
        // jika inputan bukan berupa angka
        if (cin.fail()) {
            cin.clear();
            cin.ignore(1000, '\n');
            cout << "Input harus berupa angka!\n";
            continue;
        }
        /* jika inputan tidak dalam rentang yg ditentukan
        FYI : Pemutaran Komersial Pertama (1895): Lumière bersaudara memutar film
        komersial pertama untuk umum di Paris menggunakan alat Cinematographe*/
        if (r < 1895 || r > 2026) {
            cout << "Tahun rilis tidak valid (1895 - 2026)!\n";
            continue;
        }
        return r;
    }
}

// Error handling untuk genre
string inputGenre(){
    string g;
    while(true){
        cout << "Genre           : ";
        getline(cin , g);
        if(g.empty()){
            cout << "Genre tidak boleh kosong!\n";
            continue;
        }
        break;
    }
    return g;
}

// Error handling untuk title
string inputTitle(){
    string t;
    while(true){
        cout << "Judul           : ";
        getline(cin , t);
        if(t.empty()){
            cout << "Title tidak boleh kosong!\n";
            continue;
        }
        break;
    }
    return t;
}

// prosedur untuk menambahkan objek baru
void tambahData() {
    string id;
    cout << "\n--- Tambah Data Film ---\n";

    // id tidak boleh kosong dan tidak boleh duplikat
    cin.ignore();
    while (true) {
        cout << "ID              : ";
        getline(cin, id);
        if(id.empty()){
            cout << "ID tidak boleh kosong!\n";
            continue;
        }
        if(cariIndexById(id) != -1){
            cout << "ID sudah dipakai film lain!\n";
            continue;
        }
        break;
    }

    string title = inputTitle();
    string genre = inputGenre();
    int duration = inputDuration();
    int released = inputReleased();

    Film.push_back(Cinema(id, title, genre, released, duration));
    cout << "Data film berhasil ditambahkan!\n";
}

// Prosedur untuk mencari data objek
void cariData() {
    string keyword;
    cout << "\n--- Cari Data Film ---\n";
    cin.ignore();
    while (true) {
        cout << "Judul/kata kunci: ";
        getline(cin, keyword);
        if (keyword.empty()){
            cout << "Kata kunci tidak boleh kosong!\n";
            continue;
        }
        break;
    }

    vector<string> header = {"ID", "Title", "Genre", "Duration", "Released"};
    vector<vector<string>> rows;
    for (auto& f : Film)
        if (f.getTitle().find(keyword) != string::npos) rows.push_back(f.toRow());

    if (rows.empty()) cout << "Film tidak ditemukan.\n";
    else cetakTabel(header, rows);
}

// Prosedur untuk menampilkan semua data objek
void tampilkanData() {
    cout << "\n--- Daftar Film di Bioskop ---\n";

    // Jika tidak ada objek sama sekali
    if (Film.empty()){
        cout << "Belum ada data film.\n";
        return;
    }

    vector<string> header = {"ID", "Title", "Genre", "Duration", "Released"};
    vector<vector<string>> rows;
    for (auto& f : Film) rows.push_back(f.toRow());
    cetakTabel(header, rows);
}

// Prosedur untuk mengubah data objek
void updateData() {
    string id;
    cout << "\n--- Update Data Film ---\n";
    cin.ignore();
    while (true) {
        cout << "Masukkan ID film: ";
        getline(cin, id);
        if (id.empty()){
            cout << "ID tidak boleh kosong!\n";
            continue;
        }
        break;
    }

    int idx = cariIndexById(id);
    if (idx == -1){
        cout << "Film dengan ID tersebut tidak ditemukan.\n";
        return;
    }

    string title = inputTitle();
    string genre = inputGenre();
    int duration = inputDuration();
    int released = inputReleased();

    Film[idx].setTitle(title);
    Film[idx].setGenre(genre);
    Film[idx].setDuration(duration);
    Film[idx].setReleased(released);
    cout << "Data berhasil diupdate!\n";
}

// Prosedur untuk menghapus objek
void hapusData() {
    string id;
    cout << "\n--- Hapus Data Film ---\n";
    cin.ignore();
    while (true) {
        cout << "Masukkan ID film: ";
        getline(cin, id);
        if (id.empty()){
            cout << "ID tidak boleh kosong!\n";
            continue;
        }
        break;
    }

    int idx = cariIndexById(id);
    if (idx == -1) { cout << "Film dengan ID tersebut tidak ditemukan.\n"; return; }

    Film.erase(Film.begin() + idx);
    cout << "Data berhasil dihapus!\n";
}

// Menampilkan semua command yg tersedia
void tampilkanMenu() {
    cout << "\n===============================\n";
    cout << "     MANAJEMEN DATA CINEMA\n";
    cout << "===============================\n";
    cout << "1. Tambah Data Cinema\n2. Tampilkan Semua Data Cinema\n";
    cout << "3. Update Data Cinema\n4. Hapus Data Cinema\n5. Cari Data Cinema\n0. Keluar\n";
    cout << "Pilih menu: ";
}

// Animasi Exit
void animasiKeluar() {
    cout << "\nMenutup program";
    for (int i = 0; i < 3; i++) {
        this_thread::sleep_for(chrono::milliseconds(400));
        cout << "." << flush;
    }
    cout << "\n\n";
 
    vector<string> banner = {
        "#####  ####    ##       ###    ###  #   # ##### ",
        "  #    #   #    #       #  #  #   # ##  # #     ",
        "  #    ####     #       #  #  #   # # # # ####  ",
        "  #    #        #       #  #  #   # #  ## #     ",
        "  #    #       ###      ###    ###  #   # ##### "
    };
 
    for (const auto& baris : banner) {
        this_thread::sleep_for(chrono::milliseconds(150));
        cout << baris << endl;
    }
    cout << endl;
}

int main(){

    int pilihan;

    do {
        tampilkanMenu();
        cin >> pilihan;
        if (cin.fail()){
        cin.clear();
        cin.ignore(1000, '\n');
        cout << "Input harus berupa angka!\n";
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
            default: cout << "Pilihan tidak valid!\n";
        }
    } while (pilihan != 0);
    
    return 0;
}