<?php

namespace Classes;

use Interfaces\IModel;

abstract class AbstractController
{

    public IModel $model;
    public View $view;

    function __construct()
    {
        $this->view = new View();
    }

    public abstract function index(): void;
}
