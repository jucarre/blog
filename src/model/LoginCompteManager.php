<?php

namespace App\Model;

/**
 * LoginCompteManagaer regroupe tout les requêtes lié a l'identification, inscription de l'utilisateur et l'admin.
 */
class LoginCompteManager extends Manager
{
    /**
     * retourne les informations de l'utilisateur ou l'admin.
     *
     * @param string $username
     *
     * @return $login
     */
    public function getLogin($username)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM users WHERE username = :username');
        $req->execute(['username' => $username]);
        $login = $req->fetch();

        return $login;
    }

    /**
     * Vérifi si le nom est déjà dans la base.
     *
     * @throws Exception
     */
    public function checkUsername()
    {
        if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            throw new \Exception("Votre pseudo n'ai pas valide (alphanumérique)");
        } else {
            $bdd = $this->dbConnect();
            $req = $bdd->prepare('SELECT id FROM users WHERE username = ?');
            $req->execute([$_POST['username']]);
            $user = $req->fetch();

            if ($user) {
                throw new \Exception('Ce pseudo est déjà pris');
            }
        }
    }

    /**
     * Vérifi si l'email est valide et si il est déjà dans la base.
     *
     * @throws Exception
     */
    public function checkEmail()
    {
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("votre email n'est pas valide");
        } else {
            $email = $_POST['email'];
            $bdd = $this->dbConnect();
            $req = $bdd->prepare('SELECT id FROM users WHERE email = ?');
            $req->execute([$email]);
            $user = $req->fetch();

            if ($user) {
                throw new \Exception('Cet email est déjà utilisé pour un autre compte.');
            }
        }
    }

    /**
     * Vérifi le mot de passe.
     *
     * @throws Exception
     */
    public function checkPassword()
    {
        if (empty($_POST['password']) && $_POST['password'] != $_POST['password_confirm']) {
            throw new \Exception('vous devez rentrer un mot de passe valide');
        }
        if (empty($_POST['password']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['password'])) {
            throw new \Exception("Votre password n'ai pas valide (alphanumérique)");
        }
    }

    /**
     * inscrit l'utilisateur dans la base et lui envoie un mail de comfirmation.
     */
    public function registerUser()
    {
        $satuts = 2;
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO users SET username = ?, password = ?, email = ?, status = ?');
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $req->execute([$_POST['username'], $password, $_POST['email'], $satuts]);

        $entetemail = "From: Blog juju  <julienroquai@gmail.com>\r\n";
        $entetemail .= "Reply-To: julienroquai@gmail.com \n";
        $entetemail .= 'X-Mailer: PHP/'.phpversion()."\n";
        $entetemail .= "Content-Type: text/plain; charset=utf8\r\n";
        $objet = 'Comfirmation de la création de votre compte sur le blog de juju';
        $message_email = 'Votre compte a bien été créé '.$_POST['username'].', vous pouvez maintenant vous connecter.';

        mail($_POST['email'], $objet, $message_email, $entetemail);
    }
}
