<?php

namespace jucarre\Blog\Model;

require_once("model/Manager.php");

class LoginManager extends ManagerFetchModeOff
{
    public function getLogin($pseudo, $key)
    {
        $bdd= $this->dbConnect();
        $req = $bdd->prepare('SELECT id, pass FROM users WHERE pseudo = :pseudo');
        $req->execute(array(
            'pseudo' => $pseudo));
        $login = $req->fetch();

        return $login;
        
    }

}