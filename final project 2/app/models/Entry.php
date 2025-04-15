<?php

namespace app\models;

use app\models\Model;

class Entry extends Model {

    public function getEntriesByDate($date) {
        $query = "SELECT * FROM entries WHERE date = :date";
        return $this->query($query, ['date' => $date]);
    }

    public function getAllEntries() {
        $query = "SELECT * FROM entries";
        return $this->query($query);
    }

    public function getEntryByID($id) {
        $query = "SELECT * FROM entries WHERE id = :id";
        return $this->query($query, ['id' => $id]);
    }

    public function entryExists($date) {
        $query = "SELECT COUNT(*) FROM entries WHERE date = :date";
        $result = $this->query($query, ['date' => $date]);
        return $result[0]['COUNT(*)'] > 0;  
    }

    public function saveEntry($data) {
        if ($this->entryExists($data['date'])) {
            return false; 
        }

        $query = "INSERT INTO entries (title, entry, date) VALUES (:title, :entry, :date)";
        return $this->query($query, $data);
    }

    public function updateEntry($data) {
        $query = "UPDATE entries SET title = :title, entry = :entry, date = :date WHERE id = :id";
        return $this->query($query, $data);
    }

    public function deleteEntry($data) {
        $query = "DELETE FROM entries WHERE id = :id";
        return $this->query($query, $data);
    }    
}
?>
