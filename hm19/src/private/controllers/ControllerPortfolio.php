<?php

namespace Controllers;

use Classes\{AbstractController, View};
use Models\ModelPortfolio;

class ControllerPortfolio extends AbstractController
{
    function __construct()
    {
        $this->model = new ModelPortfolio();
        $this->view = new View();
    }

    function index(): void
    {
        $data = $this->model->get_data();
        $this->view->render('portfolio_view.tpl', 'template_view.tpl', $data);
    }
}
