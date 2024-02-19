<?php

namespace Controllers;

use Classes\AbstractController;

class Controller404 extends AbstractController
{
    function index(): void
    {
        $this->view->render('404_view.tpl', 'template_view.tpl');
    }
}
