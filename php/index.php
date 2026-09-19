<?php
require_once "Cinema.php";
session_start();

// List of object disimpan di session (tanpa database)
if (!isset($_SESSION["film"])) {
    $_SESSION["film"] = array();
}
$film = &$_SESSION["film"];

// Mencari index objek dari sebuah id
function cariIndexById($film, $id) {
    foreach ($film as $i => $f) {
        if ($f->getId() === $id) {
            return $i;
        }
    }
    return -1;
}

$pesan = "";
$error = "";
$keyword = "";
$old = array();
$editFilm = null;

$action = isset($_GET["action"]) ? $_GET["action"] : "";

// ---------- Proses Hapus ----------
if ($action === "hapus" && isset($_GET["id"])) {
    $idx = cariIndexById($film, $_GET["id"]);
    if ($idx !== -1) {
        array_splice($film, $idx, 1);
        $pesan = "Data berhasil dihapus!";
    } else {
        $error = "Film dengan ID tersebut tidak ditemukan.";
    }
}

// ---------- Proses Tambah / Update (POST) ----------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = trim(isset($_POST["id"]) ? $_POST["id"] : "");
    $title = trim(isset($_POST["title"]) ? $_POST["title"] : "");
    $genre = trim(isset($_POST["genre"]) ? $_POST["genre"] : "");
    $duration = isset($_POST["duration"]) ? (int) $_POST["duration"] : 0;
    $released = isset($_POST["released"]) ? (int) $_POST["released"] : 0;
    $foto = trim(isset($_POST["foto"]) ? $_POST["foto"] : "");

    $old = array(
        "id" => $id,
        "title" => $title,
        "genre" => $genre,
        "duration" => $duration,
        "released" => $released,
        "foto" => $foto,
        "update_id" => isset($_POST["update_id"]) ? $_POST["update_id"] : ""
    );

    if ($id === "" || $title === "" || $genre === "" || $foto === "") {
        $error = "Semua field harus diisi!";
    } elseif ($duration < 40) {
        $error = "Durasi film harus lebih dari atau sama dengan 40 menit!";
    } elseif ($released < 1895 || $released > 2026) {
        $error = "Tahun rilis tidak valid (1895 - 2026)!";
    } else {
        if ($old["update_id"] !== "") {
            // Update data berdasarkan id lama (id tidak diganti)
            $idx = cariIndexById($film, $old["update_id"]);
            if ($idx === -1) {
                $error = "Film dengan ID tersebut tidak ditemukan.";
            } else {
                $film[$idx]->setTitle($title);
                $film[$idx]->setGenre($genre);
                $film[$idx]->setDuration($duration);
                $film[$idx]->setReleased($released);
                $film[$idx]->setFoto($foto);
                $pesan = "Data berhasil diupdate!";
                $old = array();
            }
        } else {
            if (cariIndexById($film, $id) !== -1) {
                $error = "ID sudah dipakai film lain!";
            } else {
                $film[] = new Cinema($id, $title, $genre, $released, $duration, $foto);
                $pesan = "Data film berhasil ditambahkan!";
                $old = array();
            }
        }
    }
}

// ---------- Siapkan Form Update (GET) ----------
if ($action === "update" && isset($_GET["id"])) {
    $idx = cariIndexById($film, $_GET["id"]);
    if ($idx !== -1) {
        $editFilm = $film[$idx];
    } else {
        $error = "Film dengan ID tersebut tidak ditemukan.";
    }
}

// ---------- Proses Cari ----------
$hasilCari = null;
if (isset($_GET["cari"])) {
    $keyword = trim(isset($_GET["keyword"]) ? $_GET["keyword"] : "");
    if ($keyword === "") {
        $error = "Kata kunci tidak boleh kosong!";
    } else {
        $hasilCari = array();
        foreach ($film as $f) {
            if (strpos($f->getTitle(), $keyword) !== false) {
                $hasilCari[] = $f;
            }
        }
    }
}

// Data yang akan ditampilkan
if ($hasilCari !== null) {
    $tampil = $hasilCari;
    $judulTabel = "Hasil Pencarian";
} else {
    $tampil = $film;
    $judulTabel = "Daftar Film di Bioskop";
}

// Nilai untuk form edit
$showEdit = false;
$editId = "";
$valueId = "";
$valueTitle = "";
$valueGenre = "";
$valueDuration = "";
$valueReleased = "";
$valueFoto = "";

if ($editFilm !== null) {
    $showEdit = true;
    $editId = $editFilm->getId();
    $valueId = $editFilm->getId();
    $valueTitle = $editFilm->getTitle();
    $valueGenre = $editFilm->getGenre();
    $valueDuration = $editFilm->getDuration();
    $valueReleased = $editFilm->getReleased();
    $valueFoto = $editFilm->getFoto();
} elseif (isset($old["update_id"]) && $old["update_id"] !== "") {
    // Jika update gagal, tampilkan kembali form edit dengan nilai lama
    $showEdit = true;
    $editId = $old["update_id"];
    $valueId = $old["id"];
    $valueTitle = $old["title"];
    $valueGenre = $old["genre"];
    $valueDuration = $old["duration"];
    $valueReleased = $old["released"];
    $valueFoto = $old["foto"];
}

// Nilai untuk form tambah
$addId = "";
$addTitle = "";
$addGenre = "";
$addDuration = "";
$addReleased = "";
$addFoto = "";
if (!empty($old) && $old["update_id"] === "") {
    $addId = $old["id"];
    $addTitle = $old["title"];
    $addGenre = $old["genre"];
    $addDuration = $old["duration"];
    $addReleased = $old["released"];
    $addFoto = $old["foto"];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Cinema</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #0f0f1a;
            color: #eee;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #1f1c2c 0%, #928dab 100%);
            padding: 25px 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,.5);
        }
        .header h1 {
            font-size: 26px;
            letter-spacing: 2px;
            color: #fff;
        }
        .header p { color: #cfcbe8; margin-top: 4px; font-size: 13px; }
        .container {
            max-width: 1000px;
            margin: 25px auto;
            padding: 0 20px;
        }
        .card {
            background: #1b1b2f;
            border-radius: 10px;
            padding: 22px;
            margin-bottom: 25px;
            box-shadow: 0 6px 18px rgba(0,0,0,.45);
            border: 1px solid #2c2c4a;
        }
        .card h2 {
            color: #f5a623;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #f5a623;
            padding-bottom: 8px;
        }
        .alert {
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 0;
            border-left: 5px solid;
            font-size: 14px;
        }
        .alert + .alert { margin-top: 10px; }
        .success { background: #123b2a; color: #7ee2a8; border-left-color: #2fbf71; }
        .error { background: #40141b; color: #ff9aa2; border-left-color: #e63946; }
        .search-box {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        .search-box input[type=text] {
            flex: 1;
            padding: 9px 12px;
            border-radius: 6px;
            border: 1px solid #3a3a5c;
            background: #14142a;
            color: #eee;
            font-size: 14px;
        }
        .search-box input[type=text]:focus {
            outline: none;
            border-color: #f5a623;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #14142a;
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            border: 1px solid #2c2c4a;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        table thead th {
            background: #f5a623;
            color: #1f1c2c;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 13px;
        }
        table tbody tr:nth-child(even) { background: #171731; }
        table tbody tr:hover { background: #22224a; }
        img.thumb {
            width: 55px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid #2c2c4a;
            display: block;
            background: #0f0f1a;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            transition: filter .2s;
        }
        .btn:hover { filter: brightness(1.15); }
        .btn-primary { background: #e63946; }
        .btn-warn { background: #f5a623; }
        .btn-link { background: #457b9d; }
        .btn-reset { background: #6c757d; }
        .btn-ghost { background: transparent; border: 1px solid #6c757d; color: #cfcbe8; }
        form p { margin-bottom: 12px; }
        form label {
            display: inline-block;
            width: 140px;
            font-size: 14px;
            color: #cfcbe8;
        }
        form input[type=text], form input[type=number] {
            width: 280px;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #3a3a5c;
            background: #14142a;
            color: #eee;
            font-size: 14px;
        }
        form input:focus { outline: none; border-color: #f5a623; }
        .empty { color: #8b88a8; text-align: center; padding: 20px; font-style: italic; }
        .lokasi { color: #888; font-size: 12px; vertical-align: middle; }
        .aksi { white-space: nowrap; }
        @media (max-width: 700px) {
            form input[type=text], form input[type=number], .search-box { width: 100%; }
            form label { display: block; width: 100%; margin-bottom: 4px; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>MANAJEMEN DATA CINEMA</h1>
</div>

<div class="container">

    <?php if ($pesan !== "" || $error !== "") : ?>
    <div class="card">
        <?php if ($pesan !== "") : ?>
            <div class="alert success"><?php echo htmlspecialchars($pesan); ?></div>
        <?php endif; ?>
        <?php if ($error !== "") : ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- ===== DAFTAR / CARI FILM (paling atas) ===== -->
    <div class="card">
        <h2><?php echo $judulTabel; ?></h2>

        <form class="search-box" method="get" action="index.php">
            <input type="hidden" name="cari" value="1">
            <input type="text" name="keyword" placeholder="Cari film berdasarkan judul / kata kunci..."
                   value="<?php echo htmlspecialchars($keyword); ?>">
            <button class="btn btn-warn" type="submit">Cari</button>
            <?php if ($hasilCari !== null) : ?>
                <a class="btn btn-reset" href="index.php">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (count($tampil) === 0) : ?>
            <p class="empty">
                <?php if ($hasilCari !== null) : ?>
                    Film tidak ditemukan.
                <?php else : ?>
                    Belum ada data film. Silakan tambah data di bawah.
                <?php endif; ?>
            </p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Duration</th>
                        <th>Released</th>
                        <th>Lokasi Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tampil as $f) : ?>
                    <tr>
                        <td>
                            <img class="thumb" src="<?php echo htmlspecialchars($f->getFoto()); ?>"
                                 alt="<?php echo htmlspecialchars($f->getTitle()); ?>"
                                 onerror="this.style.display='none';this.insertAdjacentHTML('afterend','<span class=&quot;lokasi&quot;>Gambar tidak ditemukan</span>');">
                        </td>
                        <td><?php echo htmlspecialchars($f->getId()); ?></td>
                        <td><?php echo htmlspecialchars($f->getTitle()); ?></td>
                        <td><?php echo htmlspecialchars($f->getGenre()); ?></td>
                        <td><?php echo $f->getDuration(); ?> menit</td>
                        <td><?php echo $f->getReleased(); ?></td>
                        <td class="lokasi"><?php echo htmlspecialchars($f->getFoto()); ?></td>
                        <td class="aksi">
                            <a class="btn btn-link" href="index.php?action=update&amp;id=<?php echo urlencode($f->getId()); ?>">Update</a>
                            <a class="btn btn-primary" href="index.php?action=hapus&amp;id=<?php echo urlencode($f->getId()); ?>"
                               onclick="return confirm('Hapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- ===== FORM TAMBAH / UPDATE (di bawah) ===== -->
    <div class="card">
        <?php if ($showEdit) : ?>
            <h2>Update Data Film (ID: <?php echo htmlspecialchars($editId); ?>)</h2>
            <form method="post" action="index.php">
                <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($editId); ?>">
                <p>
                    <label>ID</label>
                    <span><?php echo htmlspecialchars($valueId); ?></span>
                    <small class="lokasi">(ID tidak dapat diubah)</small>
                </p>
                <p><label>Judul</label><input type="text" name="title" value="<?php echo htmlspecialchars($valueTitle); ?>"></p>
                <p><label>Genre</label><input type="text" name="genre" value="<?php echo htmlspecialchars($valueGenre); ?>"></p>
                <p><label>Durasi (menit)</label><input type="number" name="duration" min="40" value="<?php echo htmlspecialchars($valueDuration); ?>"></p>
                <p><label>Tahun rilis</label><input type="number" name="released" min="1895" max="2026" value="<?php echo htmlspecialchars($valueReleased); ?>"></p>
                <p><label>Path gambar</label><input type="text" name="foto" value="<?php echo htmlspecialchars($valueFoto); ?>" placeholder="img/poster.jpg"></p>
                <button class="btn btn-warn" type="submit">Update</button>
                <a class="btn btn-ghost" href="index.php">Batal</a>
            </form>
        <?php else : ?>
            <h2>Tambah Data Film</h2>
            <form method="post" action="index.php">
                <p><label>ID</label><input type="text" name="id" value="<?php echo htmlspecialchars($addId); ?>" placeholder="F001"></p>
                <p><label>Judul</label><input type="text" name="title" value="<?php echo htmlspecialchars($addTitle); ?>" placeholder="Nama film"></p>
                <p><label>Genre</label><input type="text" name="genre" value="<?php echo htmlspecialchars($addGenre); ?>" placeholder="Drama / Aksi / ..."></p>
                <p><label>Durasi (menit)</label><input type="number" name="duration" min="40" value="<?php echo htmlspecialchars($addDuration); ?>"></p>
                <p><label>Tahun rilis</label><input type="number" name="released" min="1895" max="2026" value="<?php echo htmlspecialchars($addReleased); ?>"></p>
                <p><label>Path gambar</label><input type="text" name="foto" value="<?php echo htmlspecialchars($addFoto); ?>" placeholder="img/poster.jpg"></p>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </form>
        <?php endif; ?>
    </div>

</div>
</body>
</html>