<?php

namespace Classes;

use Interfaces\IView;

class View implements IView
{
    function render(string $content_view, string $template_view, array $data = null): void
    {
        include __DIR__.'/../views/' . $template_view;
    }
}