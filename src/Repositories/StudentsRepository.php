<?php

namespace Repositories;

class StudentsRepository
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
        $this->pdo = new \PDO('mysql:host=localhost;dbname='.$databasename, $user, $pass);
        if (!$this->pdo) {
            return false;
            //throw new Exception('Error connecting to the database');
        }

    }

    public function getAllStudents($limit = 10, $offset = 0)
    {
        $statement = $this->pdo->prepare('SELECT * FROM students');
//        $statement->limit($limit); // @todo fix limit and offset if present
//        $statement->offset($offset);
        $statement->execute();
        return $this->fetchStudentData($statement);
    }

    private function fetchStudentData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'firstName' => $result['first_name'],
                'lastName' => $result['last_name'],
                'email' => $result['email'],
            ];
        }

        return $results;
    }
}