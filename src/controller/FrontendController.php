<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Model\FormManager;
use App\Model\LoginCompteManager;
use App\Model\PostManager;

/**
 * FrontendController est le controller de la parti public du Blog.
 */
class FrontendController
{
    private $renderer;
    private $verif;
    private $loginManager;
    private $postManager;
    private $commentManager;
    private $formManager;

    public function __construct()
    {
        $this->verif = new FunctionComponent();
        $this->renderer = new TwigRenderer();
        $this->loginManager = new LoginCompteManager();
        $this->postManager = new PostManager();
        $this->commentManager = new CommentManager();
        $this->formManager = new FormManager();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Affiche la page d'accueil du site.
     */
    public function homeView()
    {
        $this->renderer->render('frontend/homeView');
        $_SESSION['flash'] = array();
    }

    /**
     * Affiche la page de connexion et d'inscription.
     */
    public function loginView()
    {
        $this->renderer->render('frontend/loginView');
        $_SESSION['flash'] = array();
    }

    /**
     * Traitre la connexion , puis redirige vers l'interface utilisateur ou administrateur.
     */
    public function connect()
    {
        if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            throw new \Exception('Votre pseudo '.$_POST['username']." n'est pas valide (alphanumérique)");
        }
        if (empty($_POST['password']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['password'])) {
            throw new \Exception("Votre password n'est pas valide (alphanumérique)");
        }
        $username = strip_tags(htmlspecialchars($_POST['username']));
        $password = strip_tags(htmlspecialchars($_POST['password']));

        $user = $this->loginManager->getLogin($username);

        if (!$user) {
            $_SESSION['flash']['danger'] = 'Mauvais identifiant ou mot de passe !';
            header('Location: /login');
        } else {
            $isPasswordCorrect = password_verify($password, $user->password);

            if ($isPasswordCorrect != 1) {
                $_SESSION['flash']['danger'] = 'Mauvais identifiant ou mot de passe !';
                header('Location: /login');
            } else {
                $_SESSION['auth'] = $user;
                if ($_SESSION['auth']->status == 1) {
                    header('Location: /admin');
                } else {
                    header('Location: /user');
                }
            }
        }
    }

    /**
     * Traite l'inscription Utilisateur.
     */
    public function register()
    {
        $this->loginManager->checkUsername();
        $this->loginManager->checkEmail();
        $this->loginManager->checkPassword();

        $this->loginManager->registerUser();
        header('Location: /login');
    }

    /**
     * Affiche la liste des articles.
     */
    public function listPosts()
    {
        $articlesParPage = 5;
        $articlesTotalesReq = $this->postManager->getPostsTotal();
        $articlesTotales = $articlesTotalesReq->rowcount();
        $pagesTotales = ceil($articlesTotales / $articlesParPage);
        $_SESSION['pagestotales'] = $pagesTotales;

        if (empty($_GET['page'])) {
            $_GET['page'] = 1;
        }
        if (!empty($_GET['page']) && ($_GET['page'] < 0 || $_GET['page'] > $pagesTotales)) {
            $_GET['page'] = 1;
        }

        $pageCourante = $_GET['page'];
        $depart = ($pageCourante - 1) * $articlesParPage;

        $list_posts = $this->postManager->getPosts($depart, $articlesParPage);

        $this->renderer->render('frontend/listPostView', ['listposts' => $list_posts, 'pagestotales' => $pagesTotales, 'pagecourante' => $pageCourante]);
        $_SESSION['flash'] = array();
    }

    /**
     * Affiche un article et ses commentaires.
     *
     * @param int $id id du l'article
     */
    public function post($id)
    {
        $post = $this->postManager->getPost($id);
        $comments = $this->commentManager->getComments($id);

        if (isset($_SESSION['auth']->id)) {
            $user = [
                'id' => $_SESSION['auth']->id,
                'username' => $_SESSION['auth']->username,
                'status' => $_SESSION['auth']->status,
            ];
        } else {
            $user = ['id' => 0, 'username' => 0, 'status' => 0];
        }

        $this->renderer->render('frontend/postView', ['data_post' => $post, 'data_comments' => $comments, 'data_user' => $user]);
        $_SESSION['flash'] = array();
    }

    /**
     * Affiche l'excpetion 404.
     */
    public function erroView()
    {
        if (!isset($_SESSION['errorMessage'])) {
            $_SESSION['errorMessage'] = 'Page not found.';
        }
        $errorMessage = $_SESSION['errorMessage'];
        $this->renderer->render('frontend/errorView', ['data_message' => $errorMessage]);
        $_SESSION['flash'] = array();
    }

    /**
     * function de déconnexion.
     */
    public function deco()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();
        session_destroy();

        header('Location: /login');
    }

    /**
     * Traite le formulaire de contact de la page d'accueil.
     */
    public function contactForm()
    {
        if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash']['danger'] = 'Tous les champs ne sont pas remplis ou corrects.';
        } else {
            $nom = strip_tags(htmlspecialchars($_POST['nom']));
            $prenom = strip_tags(htmlspecialchars($_POST['prenom']));
            $email = strip_tags(htmlspecialchars($_POST['email']));
            $message = strip_tags(htmlspecialchars($_POST['message']));

            $this->formManager->fromTraiment($nom, $prenom, $email, $message);
            $_SESSION['flash']['success'] = 'Votre formulaire a bien été envoyer.';
        }
        header('Location: /');
    }

    /**
     * Transmait le CV pdf.
     */
    public function cvjuju()
    {
        $file = 'public/pdf/CV_julien_carre.pdf';
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($file));
            readfile($file);
        }
    }
}
