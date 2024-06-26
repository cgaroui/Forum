<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class TopicManager extends Manager{

    // On indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Topic";
    protected $tableName = "topic";

    public function __construct(){
        parent::connect();
    }

    // Récupérer tous les topics d'une catégorie spécifique (par son id)
    public function findTopicsByCategory($id) {

        $sql = "SELECT *
                FROM ".$this->tableName." t 
                WHERE t.category_id = :id
                ORDER BY t.creationDate desc"; // Correction ici : fermeture de la chaîne SQL

        // La requête renvoie plusieurs enregistrements --> getMultipleResults
        return $this->getMultipleResults(
            DAO::select($sql, ['id' => $id]), 
            $this->className
        );
    }

    public function closeTopic($id){  
        $sql = "UPDATE topic
                SET closed = 1
                WHERE id_topic = :id";

        return $this-> getOneOrNullResult(  
            DAO::select($sql, ['id' => $id]),
            $this->className);
    }


    public function openTopic($id){
        $sql = "UPDATE topic
                SET closed = 0
                WHERE id_topic = :id";

        return $this-> getOneOrNullResult( 
            DAO::select($sql, ['id' => $id]), 
            $this->className);
    }

     // Méthode pour récupérer les 5 derniers topics d'un utilisateur
     public function getLastFiveTopics($userId) {
        $sql = "SELECT * 
                FROM ".$this->tableName." 
                WHERE user_id = :user_id 
                ORDER BY creationDate DESC LIMIT 5";

       
         // Utilisation de getMultipleResults pour transformer les résultats en objets Topic
         return $this->getMultipleResults(
            DAO::select($sql, ['user_id' => $userId]), 
            $this->className
        );    }

}
