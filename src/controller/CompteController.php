<?php

namespace App\Controller;

use App\Model\CommentManager;

/**
 * CompteController est le controller de l'espace utilisateur.
 */
class CompteController
{
    private $renderer;
    private $verif;
    private $commentManager;

    public function __construct()
    {
        $this->verif = new FunctionComponent();
        $this->renderer = new TwigRenderer();
        $this->commentManager = new CommentManager();

        if (empty($_SESSION)) {
            $_SESSION['init'] = 1;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['auth'])) {
            $_SESSION['flash']['danger'] = 'Vous n\'avez pas le droit d\'accéder à cette page';
            header('Location: /login');
            die();
        }
    }

    public function interfaceCompte()
    {
        $userId = $this->verif->check($_SESSION['auth']->id);

        $comments = $this->commentManager->getUserComment($userId);
        $this->renderer->render('compte/compteView', ['data_comments' => $comments]);
        $_SESSION['flash'] = array();
    }

    public function comment($id)
    {
        $comment = $this->commentManager->getComment($id);

        if ($_SESSION['auth']->status != 1 && $_SESSION['auth']->id != $comment->id_user) {
            $_SESSION['flash']['danger'] = 'Vous n\'avez pas les droits pour modifier ce commentaire';
            header('Location: /user');
        } else {
            $this->renderer->render('compte/editComment', ['data_comment' => $comment]);
            $_SESSION['flash'] = array();
        }
    }

    public function addComment($id)
    {
        $author = $this->verif->check($_POST['author']);

        $comment = $this->verif->check($_POST['comment']);

        $idUser = $this->verif->check($_SESSION['auth']->id);

        $affectedLines = $this->commentManager->postComment($id, $idUser, $author, $comment);

        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible d\'ajouter le commentaire !';
        } else {
            $_SESSION['flash']['success'] = 'Votre commentaire a bien été ajouter.';
        }
        header('Location: /user');
    }

    public function editComment($id)
    {
        $author = $this->verif->check($_POST['author']);

        $comment = $this->verif->check($_POST['comment']);

        $affectedLines = $this->commentManager->updateComment($id, $author, $comment);

        if ($affectedLines === false) {
            $_SESSION['flash']['danger'] = 'Impossible de modifier le commentaire !';
        } else {
            $_SESSION['flash']['success'] = 'Votre commentaire a bien été modifier.';
        }
        header('Location: /user');
    }

    public function removeCommentManager($id)
    {
        $comment = $this->commentManager->getComment($id);

        if ($_SESSION['auth']->status != 1 && $_SESSION['auth']->id != $comment->id_user) {
            $_SESSION['flash']['danger'] = 'Vous n\'avez pas les droits pour supprimer ce commentaire';
        } else {
            $affectedLines = $this->commentManager->removeComment($id);

            if ($affectedLines === false) {
                $_SESSION['flash']['danger'] = 'Impossible de suprrimer ce commentaire.';
            } else {
                $_SESSION['flash']['success'] = 'Votre commentaire a bien été supprimer.';
            }
        }
        header('Location: /user');
    }

    public function erroView($errorMessage)
    {
        $this->renderer->render('frontend/errorView', ['data_message' => $errorMessage]);
        $_SESSION['flash'] = array();
    }
}
