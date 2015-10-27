<?php

namespace Repositories;

class ResultsRepository
{
    private $connector;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;
    }

    public function getAllResults($limit = 100, $offset = 0)
    {
        $statement = $this->connector->getPdo()->prepare('
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