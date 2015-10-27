<?php

namespace Repositories;

class StudentsRepository
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

    public function getAllStudents($limit = 100, $offset = 0)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM students LIMIT :limit OFFSET :offset');
        $statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $statement->execute();
        return $this->fetchStudentData($statement);
    }

    private function fetchStudentData($statement)
    {
        $results = [];
        while ($result = $statement->fetch()) {
            $results[] = [
                'id' => $result['id'],
                'firstName' => $result['first_name'],
                'lastName' => $result['last_name'],
                'email' => $result['email'],
            ];
        }

        return $results;
    }

    public function addStudent(array $studentData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO students (first_name, last_name, email) VALUES(:firstName, :lastName, :email)');
        $statement->bindValue(':firstName', $studentData['first_name']);
        $statement->bindValue(':lastName', $studentData['last_name']);
        $statement->bindValue(':email', $studentData['email']);

        return $statement->execute();
    }

    public function getStudentById($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM students WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        return $this->fetchStudentData($statement);

    }

    public function updateStudent($id, array $studentData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE students SET first_name = :firstName, last_name = :lastName, email = :email WHERE id = :id");

        $statement->bindValue(':firstName', $studentData['first_name'], \PDO::PARAM_STR);
        $statement->bindValue(':lastName', $studentData['last_name'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $studentData['email'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function deleteStudent($id)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM students WHERE id = :id");

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);

        return $statement->execute();
    }

}