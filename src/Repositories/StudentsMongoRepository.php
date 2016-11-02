<?php

namespace Repositories;

class StudentsMongoRepository implements RepositoryInterface
{
    private $connector;

    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct(MongoConnector $connector = null)
    {
        if (!$connector) {
            $connector = new MongoConnector();
        }
        $this->connector = $connector;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($limit = 1000, $offset = 0)
    {
        $collection = $this->connector->getCollection('students');
        $cursor = $collection->find();

        return $this->fetchStudentData($cursor);
    }

    /**
     * @param $cursor
     * @return array
     */
    private function fetchStudentData($cursor)
    {
        $results = [];
        foreach ($cursor as $document) {
            $results[] = [
                'id'            => $document['id'],
                'firstName'     => $document['first_name'],
                'lastName'      => $document['last_name'],
                'email'         => $document['email'],
                'courses'       => $document['courses'],
            ];
        }

        return $results;
    }

    /**
     * @param array $studentData
     * @return bool
     * @throws \Exception
     */
    public function insert(array $studentData)
    {
        $collection = $this->connector->getCollection('students');
        foreach ($collection->find()->sort(['id' => -1]) as $student) {
            $studentWithMaxId = $student;
            break;
        }
        $maxId = $studentWithMaxId['id'] + 1;

        $dataToInsert = [
            'id' => $maxId,
            'first_name' => $studentData['first_name'],
            'last_name' => $studentData['last_name'],
            'email' => $studentData['email'],
            'courses' => [],
        ];
        try {
            $collection->insert($dataToInsert);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $collection = $this->connector->getCollection('students');
        $cursor = $collection->find(['id' => (int) $id]);
        $studentsData = $this->fetchStudentData($cursor);

        return $studentsData[0];
    }

    /**
     * @param array $studentData
     * @throws \Exception
     */
    public function update(array $studentData)
    {
        $studentId = (int) $studentData['id'];
        $student = $this->find($studentId);
        if (!$student) {
            throw new \Exception('Student with id' . $studentId . ' not found');
        }

        $collection = $this->connector->getCollection('students');
        foreach ($studentData  as $key => $value) {
            $student[$key] = $value;
        }
        try {
            $collection->update(['id' => $studentId], $student);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @todo use mongo's id, because usual id could be absent or duplicated
     * @param array $studentData
     * @return bool
     * @throws \Exception
     */
    public function remove(array $studentData)
    {
        $studentId = (int) $studentData['id'];
        $collection = $this->connector->getCollection('students');

        try {
            $collection->remove(['id' => $studentId]);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }


    /**
     * Search all entity data in the DB like $criteria rules
     * @param array $criteria
     * @return mixed
     */
    public function findBy($criteria = [])
    {
        $collection = $this->connector->getCollection('students');
        $cursor = $collection->find($criteria);

        return $this->fetchStudentData($cursor);
    }
}