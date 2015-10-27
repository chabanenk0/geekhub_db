<?php

namespace Repositories;

class ResultsRepository
{
    private $pdo;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $databasename
     * @param $user
     * @param $pass
     */
    public function __construct($databasename, $user, $pass)
    {
        $this->pdo = new \PDO('mysql:host=localhost;dbname=' . $databasename . ';charset=UTF8', $user, $pass);
        if (!$this->pdo) {
            return false;
            //throw new Exception('Error connecting to the database');
        }

    }

    public function getAllResults($limit = 100, $offset = 0)
    {
        $statement = $this->pdo->prepare('
            SELECT * FROM results
            JOIN students ON results.student_id = students.id
            JOIN courses ON results.course_id = courses.id
            LIMIT :limit OFFSET :offset
        ');
        $statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $statement->execute();
        return $this->fetchResultsData($statement);
    }

    private function fetchResultsData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'firstName' => $result['first_name'],
                'lastName' => $result['last_name'],
                'course' => $result['name'],
                'mark' => $result['mark'],
                'time' => $result['time_seconds'],
            ];
        }

        return $results;
    }
}