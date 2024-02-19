<?php

namespace Controllers;

use Classes\AbstractController;

class ControllerMain extends AbstractController
{
    function index(): void
    {
        $this->view->render('main_view.tpl', 'template_view.tpl');
    }
}