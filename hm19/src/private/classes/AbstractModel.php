<?php

namespace Classes;

use Interfaces\IModel;

abstract class AbstractModel implements IModel
{
    abstract public function get_data();
}