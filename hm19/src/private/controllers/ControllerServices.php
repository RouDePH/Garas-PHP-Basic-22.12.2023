<?php

namespace Controllers;

use Classes\AbstractController;

class ControllerServices extends AbstractController
{
    function index(): void
    {
        $this->view->render('services_view.tpl', 'template_view.tpl');
    }
}
