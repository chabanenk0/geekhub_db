<?php

namespace Controllers;

use Repositories\StudentsRepository;
use Views\Renderer;
//require_once '/var/www/geekhub_db/src/Repositories/StudentsRepository.php';

class StudentsController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct()
    {
        $this->repository = new StudentsRepository('gh_database', 'ghuser', '111');
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $studentsData = $this->repository->getAllStudents();

        return $this->twig->render('students.html.twig', ['students' =>$studentsData]);
    }
}