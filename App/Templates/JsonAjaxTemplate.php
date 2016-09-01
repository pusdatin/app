<?php

namespace App\Templates;

use Linna\Mvc\TemplateInterface;

class JsonAjaxTemplate implements TemplateInterface
{
    public $data = null;
    
    
    public function __construct()
    {
        $this->data = (object) null;
    }
    
    public function output()
    {
        echo json_encode($this->data);
    }
}
