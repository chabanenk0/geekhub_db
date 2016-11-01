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

    public function jsonAction()
    {
        $resultsData = $this->repository->findAll();
        $studentsWithCoursesToJson = [];
        foreach ($resultsData as $result) {
            $studentId = (int) $result['id'];
            if (!array_key_exists($studentId, $studentsWithCoursesToJson)) {
                $studentsWithCoursesToJson[$studentId] = [
                    'id' => $studentId,
                    'first_name' => $result['firstName'],
                    'last_name' => $result['lastName'],
                    'email' => $result['email'],
                    'courses' => [],
                ];
            }
            $studentsWithCoursesToJson[$studentId]['courses'][] = [
                'name' => $result['course'],
                'season' => $result['season'],
                'mark' => $result['mark'],
                'time' => $result['time'],
            ];
        }

        $mongoClient = new \MongoClient();
        $mongoDb = $mongoClient->test; // select the database;
        $mongoCollection = $mongoDb->students; // select the collection
        $studentsWithCoursesToJson2 = [];
        foreach ($studentsWithCoursesToJson as $studentData) {
            $studentsWithCoursesToJson2[] = $studentData;
            $mongoCollection->save($studentData);
        }

        return json_encode($studentsWithCoursesToJson2, true);
    }

}