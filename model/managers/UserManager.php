<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class UserManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\User";
    protected $tableName = "user";

    public function __construct(){
        parent::connect ();
    }

    // Méthode pour créer un nouvel utilisateur
    public function nvUser($nickname, $email, $password) {
        $sql = "INSERT INTO {$this->tableName} (nickname, email, password) 
                VALUES (:nickname, :email, :password)";
       
        // Hash du mot de passe avant l'insertion
        $mdpHache = password_hash($password, PASSWORD_DEFAULT);

        return DAO::insert($sql, [
            'nickname' => $nickname,
            'email' => $email,
            'password' => $mdpHache
        ]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->tableName} WHERE email = :email";

        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false),
            $this->className
        );
    }

    // Méthode pour récupérer les 5 derniers topics d'un utilisateur
    public function getLastFiveTopics($userId) {
        $sql = "SELECT * FROM topic WHERE user_id = :user_id ORDER BY creationDate DESC LIMIT 5";

        return DAO::select($sql, ['user_id' => $userId]);
    }
}