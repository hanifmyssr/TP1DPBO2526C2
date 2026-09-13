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
    <title>Manajemen Data Cinema</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f4f4; }
        .container { max-width: 950px; margin: auto; background: #fff; padding: 25px; border-radius: 6px; }
        h1 { color: #333; margin-top: 0; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        fieldset { margin-bottom: 20px; border: 1px solid #aaa; border-radius: 4px; }
        legend { font-weight: bold; padding: 0 6px; }
        label { display: inline-block; width: 130px; }
        input[type=text], input[type=number] { width: 250px; padding: 5px; }
        .menu { margin-bottom: 15px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #eee; }
        a { margin-right: 8px; }
    </style>
</head>
<body>
<div class="container">

    <h1>MANAJEMEN DATA CINEMA</h1>

    <div class="menu">
        <a href="index.php">&lt;&lt; Kembali / Lihat Semua Data</a>
    </div>

    <?php if ($pesan !== "") : ?>
        <div class="alert success"><?php echo htmlspecialchars($pesan); ?></div>
    <?php endif; ?>
    <?php if ($error !== "") : ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <fieldset>
        <?php if ($showEdit) : ?>
            <legend>Update Data Film (ID: <?php echo htmlspecialchars($editId); ?>)</legend>
            <form method="post" action="index.php">
                <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($editId); ?>">
                <p><label>ID</label> <?php echo htmlspecialchars($valueId); ?>
                   <small>(ID tidak dapat diubah)</small></p>
                <p><label>Judul</label><input type="text" name="title" value="<?php echo htmlspecialchars($valueTitle); ?>"></p>
                <p><label>Genre</label><input type="text" name="genre" value="<?php echo htmlspecialchars($valueGenre); ?>"></p>
                <p><label>Durasi (menit)</label><input type="number" name="duration" min="40" value="<?php echo htmlspecialchars($valueDuration); ?>"></p>
                <p><label>Tahun rilis</label><input type="number" name="released" min="1895" max="2026" value="<?php echo htmlspecialchars($valueReleased); ?>"></p>
                <p><label>Path gambar</label><input type="text" name="foto" value="<?php echo htmlspecialchars($valueFoto); ?>"></p>
                <button type="submit">Update</button>
                <a href="index.php">Batal</a>
            </form>
        <?php else : ?>
            <legend>Tambah Data Film</legend>
            <form method="post" action="index.php">
                <p><label>ID</label><input type="text" name="id" value="<?php echo htmlspecialchars($addId); ?>"></p>
                <p><label>Judul</label><input type="text" name="title" value="<?php echo htmlspecialchars($addTitle); ?>"></p>
                <p><label>Genre</label><input type="text" name="genre" value="<?php echo htmlspecialchars($addGenre); ?>"></p>
                <p><label>Durasi (menit)</label><input type="number" name="duration" min="40" value="<?php echo htmlspecialchars($addDuration); ?>"></p>
                <p><label>Tahun rilis</label><input type="number" name="released" min="1895" max="2026" value="<?php echo htmlspecialchars($addReleased); ?>"></p>
                <p><label>Path gambar</label><input type="text" name="foto" value="<?php echo htmlspecialchars($addFoto); ?>"></p>
                <button type="submit">Simpan</button>
            </form>
        <?php endif; ?>
    </fieldset>

    <h3><?php echo $judulTabel; ?></h3>

    <form method="get" action="index.php">
        <input type="hidden" name="cari" value="1">
        <input type="text" name="keyword" placeholder="Judul/kata kunci" value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit">Cari</button>
        <a href="index.php">Reset</a>
    </form>

    <?php if (count($tampil) === 0) : ?>
        <?php if ($hasilCari !== null) : ?>
            <p>Film tidak ditemukan.</p>
        <?php else : ?>
            <p>Belum ada data film.</p>
        <?php endif; ?>
    <?php else : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Duration</th>
                <th>Released</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($tampil as $f) : ?>
            <tr>
                <td><?php echo htmlspecialchars($f->getId()); ?></td>
                <td><?php echo htmlspecialchars($f->getTitle()); ?></td>
                <td><?php echo htmlspecialchars($f->getGenre()); ?></td>
                <td><?php echo $f->getDuration(); ?> menit</td>
                <td><?php echo $f->getReleased(); ?></td>
                <td><?php echo htmlspecialchars($f->getFoto()); ?></td>
                <td>
                    <a href="index.php?action=update&amp;id=<?php echo urlencode($f->getId()); ?>">Update</a>
                    <a href="index.php?action=hapus&amp;id=<?php echo urlencode($f->getId()); ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>
</body>
</html>