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

// connect
$m = new MongoClient();

// select a database
$db = $m->comedy;

// select a collection (analogous to a relational database's table)
$collection = $db->cartoons;

// add a record
$document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
$collection->insert($document);

// add another record, with a different "shape"
$document = array( "title" => "XKCD", "online" => true );
$collection->insert($document);

// find everything in the collection
$cursor = $collection->find();

// iterate through the results
foreach ($cursor as $document) {
    echo $document["title"] . "\n";
}
