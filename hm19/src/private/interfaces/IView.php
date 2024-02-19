<?php

namespace Interfaces;

interface IView
{
    public function render(string $content_view, string $template_view, array $data) : void;
}