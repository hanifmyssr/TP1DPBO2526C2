class Cinema:
    def __init__(self, id="", title="", genre="", released=0, duration=0):
        self._id = id
        self._title = title
        self._genre = genre
        self._released = released
        self._duration = duration

    # Getter Method
    def get_id(self):
        return self._id

    def get_title(self):
        return self._title

    def get_genre(self):
        return self._genre

    def get_released(self):
        return self._released

    def get_duration(self):
        return self._duration

    # Setter Method
    def set_id(self, id):
        self._id = id

    def set_title(self, title):
        self._title = title

    def set_genre(self, genre):
        self._genre = genre

    def set_released(self, released):
        self._released = released

    def set_duration(self, duration):
        self._duration = duration

    # Data satu objek jadi satu baris untuk tabel
    def to_row(self):
        return [self._id, self._title, self._genre,
                f"{self._duration} menit", str(self._released)]

    def __str__(self):
        return f"Cinema({self._id}, {self._title}, {self._genre}, {self._released}, {self._duration})"