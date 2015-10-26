<?php

namespace Views;

class Renderer
{
    private $templatesDirectory;

    public function __construct($templatesDirectory)
    {
        $this->templatesDirectory = $templatesDirectory;
    }

    public function render($template, $data)
    {

        var_dump($data);
//        ob_start();
//        eval($data);
//        require $this->templatesDirectory . $template;
//        $renderedHtml = ob_get_clean();
//
//        return $renderedHtml;
    }
}