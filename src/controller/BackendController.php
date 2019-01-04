<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Model\PostManager;

class BackendController
{
    private $renderer;
    private $verif;
    private $postManager;
    private $commentManager;

    public function __construct()
    {
        $this->verif = new FunctionComponent();
        $this->renderer = new TwigRenderer();
        $this->postManager = new PostManager();
        $this->commentManager = new CommentManager();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['auth'])) {
            $_SESSION['flash']['danger'] = 'Vous n\'avez pas le droit d\'accéder à cette page';
            header('Location: /login');
            die();
        }
        if (isset($_SESSION['auth'])) {
            if ($_SESSION['auth']->status != 1) {
                $_SESSION['flash']['danger'] = 'Vous n\'avez pas le droit d\'accéder à cette page';
                header('Location: /user');
                die();
            }
        }
    }

    public function interfaceAdmin()
    {
        $data_posts = $this->postManager->getPostPreview();

        $data_comments = $this->commentManager->getCommentsInvalid();

        $this->renderer->render('backend/adminView', ['data_posts' => $data_posts, 'data_comments' => $data_comments]);
        $_SESSION['flash'] = array();
    }

    public function commentValid()
    {
        $id = $this->verif->check($_POST['id']);

        $affectedLines = $this->commentManager->setCommentValid($id);

        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de valider le commentaire !';
        } else {
            $_SESSION['flash']['success'] = 'Votre commentaire a bien été valider.';
        }
        header('Location: /admin');
    }

    public function viewAddPost()
    {
        $this->renderer->render('backend/addPostView');
    }

    public function addPostManager()
    {
        $title = $this->verif->check($_POST['title']);

        $chapo = $this->verif->check($_POST['chapo']);

        $content = $this->verif->check($_POST['content']);

        $idUser = $this->verif->check($_SESSION['auth']->id);

        $affectedLines = $this->postManager->addpost($title, $chapo, $content, $idUser);
        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible d\'ajouter cette article.';
        } else {
            $_SESSION['flash']['success'] = 'Votre article a bien été ajouter.';
        }
        header('Location: /admin');
    }

    public function post($id)
    {
        $data_post = $this->postManager->getPost($id);

        $this->renderer->render('backend/editPostView', ['data_post' => $data_post]);
        $_SESSION['flash'] = array();
    }

    public function editPostManager($id)
    {
        $title = $this->verif->check($_POST['title']);

        $chapo = $this->verif->check($_POST['chapo']);

        $content = $this->verif->check($_POST['content']);

        $idUser = $this->verif->check($_SESSION['auth']->id);

        $affectedLines = $this->postManager->setPost($id, $title, $chapo, $content, $idUser);
        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de modifier cette article.';
        } else {
            $_SESSION['flash']['success'] = 'Votre article à bien été modifier.';
        }
        header('Location: /admin');
    }

    public function removePostManager()
    {
        $postId = $this->verif->check($_POST['postId']);

        $affectedLines = $this->postManager->removePost($postId);
        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de suprrimer cette article.';
        } else {
            $_SESSION['flash']['success'] = 'Votre article à bien été supprimer.';
        }
        header('Location: /admin');
    }

    public function comment($id)
    {
        $comment = $this->commentManager->getComment($id);

        $this->renderer->render('backend/editComment', ['data_comment' => $comment]);
        $_SESSION['flash'] = array();
    }

    public function editComment($id)
    {
        $author = $this->verif->check($_POST['author']);

        $comment = $this->verif->check($_POST['comment']);

        $affectedLines = $this->commentManager->updateComment($id, $author, $comment);

        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de modifier le commentaire !';
        } else {
            $_SESSION['flash']['success'] = 'Votre commentaire à bien été modifier.';
        }

        header('Location: /admin');
    }

    public function removeCommentManager($id)
    {
        $affectedLines = $this->commentManager->removeComment($id);
        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de suprrimer ce commentaire.';
        } else {
            $_SESSION['flash']['success'] = 'Votre commentaire a bien été supprimer.';
        }
        header('Location: /admin');
    }

    public function erroView($errorMessage)
    {
        $this->renderer->render('frontend/errorView', ['data_message' => $errorMessage]);
        $_SESSION['flash'] = array();
    }
}
