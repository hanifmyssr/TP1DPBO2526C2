<?php
class Cinema {
    private $id;
    private $title;
    private $genre;
    private $released;
    private $duration;
    private $foto;

    // Konstruktor dengan parameter
    public function __construct($id = "", $title = "", $genre = "", $released = 0, $duration = 0, $foto = "") {
        $this->id = $id;
        $this->title = $title;
        $this->genre = $genre;
        $this->released = $released;
        $this->duration = $duration;
        $this->foto = $foto;
    }

    // Getter Method
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getGenre() { return $this->genre; }
    public function getReleased() { return $this->released; }
    public function getDuration() { return $this->duration; }
    public function getFoto() { return $this->foto; }

    // Setter Method
    public function setId($id) { $this->id = $id; }
    public function setTitle($title) { $this->title = $title; }
    public function setGenre($genre) { $this->genre = $genre; }
    public function setReleased($released) { $this->released = $released; }
    public function setDuration($duration) { $this->duration = $duration; }
    public function setFoto($foto) { $this->foto = $foto; }

    // Data satu objek jadi satu baris untuk tabel
    public function toRow() {
        return array($this->id, $this->title, $this->genre, $this->duration . " menit", $this->released, $this->foto);
    }
}
?>