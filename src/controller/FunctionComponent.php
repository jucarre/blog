<?php

namespace App\Controller;

class FunctionComponent
{
    public function check($param)
    {
        if (isset($param) && ($param != '')) {
            return $param;
        } else {
            $_SESSION['flash']['danger'] = 'Le champ '.print_r($param).' est vide.';
            header('Location: /admin');
            die;
        }
    }
}