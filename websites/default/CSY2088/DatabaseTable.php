<?php
namespace CSY2088;
class DatabaseTable
{
    private $pdo; //database connection
    private $table; //table name 
    private $id; //primary key of the table

    public function __construct($pdo, $table, $id)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->id = $id;

    }

    function Find($field, $value)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value';
        $stmt = $this->pdo->prepare($query);
        $criteria = ['value' => $value];
        $stmt->execute($criteria);
        $jokes = $stmt->fetchAll();
        return $jokes;
    }

    function findSimilar($field, $value) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $field . '   LIKE :value';
        $stmt = $this->pdo->prepare($query);
        $criteria = ['value' => $value . '%'];
        $stmt->execute($criteria);
        $jokes = $stmt->fetchAll();
        return $jokes;
    }

    function FindAll()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $jokes = $stmt->fetchAll();
        return $jokes;
    }

    /* value needs to have all the values to be added. */
    function Insert($record)
    {
        $keys = array_keys($record);
        $insertCols = implode(', ', $keys);
        $insertValues = implode(', :', $keys);
        $stmt = $this->pdo->prepare('INSERT INTO ' . $this->table . '(' . $insertCols . ') 
    VALUES (:' . $insertValues . ')');
        $stmt->execute($record);
    }

    function DeleteValue($field, $value)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :value';
        $stmt = $this->pdo->prepare($query);
        $criteria = ['value' => $value];
        $stmt->execute($criteria);
    }

    /* record needs to have all the values to be added. */
    function Update($record)
    {   
        $params = [];
        foreach ($record as $key => $value) {
            $params[] = $key . ' = :' . $key;
        }
        $query = 'UPDATE ' . $this->table . ' SET ' . implode(' ,', $params) . ' WHERE (' . $this->id . ' = :primaryKey)';
        $record['primaryKey'] = $record[$this->id];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($record);

    }

    function Save($record)
    {
        if (empty($record[$this->id])) {
            unset($record[$this->id]);
        }
        try {
            $this->Insert($record);
        } catch (\Exception $exception) {
            $this->Update($record);
        }
    }
}