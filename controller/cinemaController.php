<?php

namespace Controller;
use Model\Connect;
include "model/model.php";

class CinemaController{

    // fonction lister films+acteurs+realisateur+genres+roles

    public function listFilms(){

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("SELECT id_film, titre, anneeSortie
            FROM film
            ORDER BY anneeSortie DESC
        ");

        require "view/list/listFilms.php";
    }

    public function listActeurs(){

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("SELECT a.id_acteur, p.nom AS Nom, p.prenom AS Prenom, p.DateNaissance AS Date_de_naissance
            FROM acteur a
            INNER JOIN personne p ON a.id_personne = p.id_personne
            ORDER BY p.nom, p.prenom
        ");

        require "view/list/listActeurs.php";
    }

    public function listRealisateurs(){

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("SELECT r.id_realisateur, p.nom AS Nom, p.prenom AS Prenom, p.DateNaissance AS Date_de_naissance
            FROM realisateur r
            INNER JOIN personne p ON r.id_personne = p.id_personne
            ORDER BY p.nom, p.prenom
        ");

        require "view/list/listRealisateurs.php";
    }

    public function listGenres(){

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("SELECT g.id_genre, g.nomGenre
            FROM genre g
            ORDER BY g.nomGenre
        ");

        require "view/list/listGenres.php";
    }

    public function listRoles(){

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("
            SELECT ro.id_role, ro.nomRole
            FROM role ro
            ORDER BY ro.nomRole
        ");

        require "view/list/listRoles.php";
    }

    public function detailFilm($id){
        $pdo = Connect :: seConnecter();
        $requeteFilm = $pdo->prepare("SELECT f.titre AS titre FROM film f WHERE id_film = :id");
        $requeteFilm->execute(["id"=>$id]);

        $requeteCasting = $pdo->prepare("SELECT p.nom, p.prenom, r.nomRole 
        FROM casting c
        INNER JOIN role r ON c.id_role = r.id_role
        INNER JOIN acteur a ON c.id_acteur = a.id_acteur 
        INNER JOIN personne p ON a.id_personne = p.id_personne
        WHERE id_film = :id");
        $requeteCasting->execute(["id"=>$id]);
                
        require "view/detail/detailFilm.php";
    }

    public function detailActeur($id){
        $pdo = Connect:: seConnecter();
        $requeteActeur = $pdo->prepare("SELECT p.prenom, p.nom, ROUND(DATEDIFF( NOW(), p.DateNaissance)/365) AS age, p.sexe
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne 
        WHERE id_acteur = :id
        ");
        $requeteActeur->execute(["id"=>$id]);

        require "view/detail/detailActeur.php";
    }
}