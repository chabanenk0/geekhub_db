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

    public function __construct($connector)
    {
        $this->repository = new StudentsRepository($connector);
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $studentsData = $this->repository->findAll();

        return $this->twig->render('students.html.twig', ['students' => $studentsData]);
    }

    public function newAction()
    {
        if (isset($_POST['first_name'])) {
            $this->repository->insert(
                [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                ]
            );
            return $this->indexAction();
        }
        return $this->twig->render('students_form.html.twig',
            [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
            ]
        );
    }

    public function editAction()
    {
        if (isset($_POST['first_name'])) {
            $this->repository->update(
                [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                    'id'         => (int) $_GET['id'],
                ]
            );
            return $this->indexAction();
        }
        $studentData = $this->repository->find((int) $_GET['id']);
        return $this->twig->render('students_form.html.twig',
            [
                'first_name' => $studentData['firstName'],
                'last_name' => $studentData['lastName'],
                'email' => $studentData['email'],
            ]
        );
    }

    public function deleteAction()
    {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $this->repository->remove(['id' => $id]);
            return $this->indexAction();
        }
        return $this->twig->render('students_delete.html.twig', array('student_id' => $_GET['id']));
    }
}