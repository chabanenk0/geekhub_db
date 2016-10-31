<?php

namespace Controllers;

use Repositories\ResultsRepository;
use Views\Renderer;

class ResultsController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct($connector)
    {
        $this->repository = new ResultsRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $resultsData = $this->repository->findAll();

        return $this->twig->render('results.html.twig', ['results' => $resultsData ]);
    }
}