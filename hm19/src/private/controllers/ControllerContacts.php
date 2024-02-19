<?php

namespace Controllers;

use Classes\AbstractController;

class ControllerContacts extends AbstractController
{
    function index(): void
    {
        $this->view->render('contacts_view.tpl', 'template_view.tpl');
    }
}
