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

        return $this->twig->render('students.html.twig', ['students' => $studentsData]);
    }

    public function newAction()
    {
        if (isset($_POST['first_name'])) {
            $this->repository->addStudent(
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
            $this->repository->updateStudent(
                (int) $_GET['id'],
                [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                ]
            );
            return $this->indexAction();
        }
        $studentData = $this->repository->getStudentById((int) $_GET['id'])[0];
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
            $this->repository->deleteStudent((int)$_POST['id']);
            return $this->indexAction();
        }
        return $this->twig->render('students_delete.html.twig', array('student_id' => $_GET['id']));
    }
}