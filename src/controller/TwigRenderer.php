<?php
namespace App\Controller;

use App\Controller\FilterManager;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigRenderer extends FilterManager
{

    private $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new Twig_Loader_Filesystem('public/view');
        $this->twig = new Twig_Environment($this->loader, [
            'cache' => false, // __DIR__ . /tmp',
        ]);
        if (empty($_SESSION)) {$_SESSION['init'] = 1;}
        $this->twig->addGlobal('_session', $_SESSION);
        $this->twig->addGlobal('_post', $_POST);
        $this->twig->addGlobal('_get', $_GET);
    }

    protected function render($view, array $prams = [])
    {
        echo $this->twig->render($view . '.twig', $prams);
    }

}
