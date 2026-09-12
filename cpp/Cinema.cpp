#include<bits/stdc++.h>
using namespace std;

class Cinema{
    private:
        int id;
        string title;
        string genre;
        int released;
        int duration;

    public:
        // Constructor kosong
        Cinema(){}

        // Constructor dengan parameter
        Cinema(int i, string t, string g, int r, int d) {
            id = i;
            title = t;
            genre = g;
            released = r;
            duration = d;
        }

        // Getter Method
        int getId(){return id;}
        string getTitle(){return title;}
        string getGenre(){return genre; }
        int getReleased(){return released;}
        int getDuration(){return duration;}

        // Setter Method
        void setId(int id){
            this->id = id;
        }
        void setTitle(string title){
            this->title = title; 
        }
        void setGenre(string genre){
            this->genre = genre;
        }
        void setReleased(int released){
            this->released = released;
        }
        void setDuration(int duration){
            this->duration = duration;
        }

        ~Cinema(){}
};