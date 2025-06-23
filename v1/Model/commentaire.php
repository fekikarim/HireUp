<?php

require_once(__DIR__ . '/../config.php');

class Commentaire
{
    // Attributs
    private $id_commentaire;    // identifiant du commentaire
    private $id_article;        // identifiant de l'article associé au commentaire
    private $auteur_id;         // auteur_id du commentaire
    private $contenu;           // contenu du commentaire
    private $date_commentaire;  // date du commentaire
    private $sender_id;         // identifiant de l'expéditeur (utilisateur qui a envoyé le commentaire)

    // Constructeur sans paramètres
    public function __construct()
    {
    }

    // Getter et setter pour chaque attribut

    public function getIdCommentaire()
    {
        return $this->id_commentaire;
    }

    public function getIdArticle()
    {
        return $this->id_article;
    }

    public function setIdArticle($id_article)
    {
        $this->id_article = $id_article;
    }

    public function getAuteur()
    {
        return $this->auteur_id;
    }

    public function setAuteur($auteur_id)
    {
        $this->auteur_id = $auteur_id;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    public function getDateCommentaire()
    {
        return $this->date_commentaire;
    }

    public function setDateCommentaire($date_commentaire)
    {
        $this->date_commentaire = $date_commentaire;
    }

    public function getSenderId()
    {
        return $this->sender_id;
    }

    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    // Méthode pour sauvegarder les données dans la base de données
    public function save()
    {
        try {
            $db = $GLOBALS['db'];
            // Préparer la requête SQL
            $query = "INSERT INTO commentaires (id_article, auteur_id, contenu, date_commentaire, sender_id) VALUES (?, ?, ?, ?, ?)";

            // Préparer la déclaration SQL
            $statement = $db->prepare($query);

            // Lier les valeurs
            $statement->bindParam(1, $this->id_article);
            $statement->bindParam(2, $this->auteur_id);
            $statement->bindParam(3, $this->contenu);
            $statement->bindParam(4, $this->date_commentaire);
            $statement->bindParam(5, $this->sender_id);

            // Exécuter la requête
            $result = $statement->execute();

            // Fermer la connexion à la base de données
            $db = null;

            return $result;
        } catch (PDOException $e) {
            // Gérer les erreurs de base de données
            echo "Erreur de base de données : " . $e->getMessage();
            return false;
        }
    }
}
