import java.util.List;
import java.util.Arrays;

public class Cinema {
    private String id;
    private String title;
    private String genre;
    private int released;
    private int duration;

    // Konstruktor kosong
    public Cinema() {}

    // Konstruktor dengan parameter
    public Cinema(String id, String title, String genre, int released, int duration) {
        this.id = id;
        this.title = title;
        this.genre = genre;
        this.released = released;
        this.duration = duration;
    }

    // Getter Method
    public String getId() { return id; }
    public String getTitle() { return title; }
    public String getGenre() { return genre; }
    public int getReleased() { return released; }
    public int getDuration() { return duration; }

    // Setter Method
    public void setId(String id) { this.id = id; }
    public void setTitle(String title) { this.title = title; }
    public void setGenre(String genre) { this.genre = genre; }
    public void setReleased(int released) { this.released = released; }
    public void setDuration(int duration) { this.duration = duration; }

    // Data satu objek jadi satu baris untuk tabel
    public List<String> toRow() {
        return Arrays.asList(id, title, genre, duration + " menit", String.valueOf(released));
    }
}