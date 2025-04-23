<?php

namespace Salma\Belajar\PHP\MVC\Controller;

use Salma\Belajar\PHP\MVC\App\View;

class HomeController
{

    function index(){
        View::render('Home/index', [
            "title" => "PHP Login Management"
        ]);
    }

}