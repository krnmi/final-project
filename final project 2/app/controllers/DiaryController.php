<?php

namespace app\controllers;

use app\models\Entry;

class DiaryController
{
    public function validateDiary($inputData)
    {
        $errors = [];
        $title = $inputData['title'];
        $entry = $inputData['entry'];
        $date = $inputData['date'];

        if ($title) {
            $title = htmlspecialchars($title, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            if (strlen($title) < 1) {
                $errors['titleShort'] = 'title is too short';
            }
        } else {
            $errors['requiredTitle'] = 'title is required';
        }

        if ($entry) {
            $entry = htmlspecialchars($entry, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            if (strlen($entry) < 10) {
                $errors['entryShort'] = 'entry is too short';
            }
        } else {
            $errors['requiredEntry'] = 'entry is required';
        }

        if ($date) {
            $date = htmlspecialchars($date, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            $d = \DateTime::createFromFormat('Y-m-d', $date);
            if (!($d && $d->format('Y-m-d') === $date)) {
                $errors['invalidDate'] = 'date is not valid, please enter in yyyy-mm-dd.';
            }
        } else {
            $errors['requiredDate'] = 'date is required';
        }

        if (count($errors)) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }

        return [
            'title' => $title,
            'entry' => $entry,
            'date' => $date,
        ];
    }

    public function getAllEntries() {
        $entryModel = new Entry();
        header("Content-Type: application/json");
        $entries = $entryModel->getAllEntries();
        echo json_encode($entries);
        exit();
    }

    public function getEntryByID($id) {
        $entryModel = new Entry();
        header("Content-Type: application/json");
        $entry = $entryModel->getEntryByID($id);
        echo json_encode($entry);
        exit();    
    }

    public function saveEntry() {
        $inputData = [
            'title' => $_POST['title'] ?: null,
            'entry' => $_POST['entry'] ?: null,
            'date' => $_POST['date'] ?: null,
        ];
        $entryData = $this->validateDiary($inputData);

        $entryModel = new Entry();

        if ($entryModel->entryExists($entryData['date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'an entry already exists for this date.']);
            exit();
        }

        $entryModel->saveEntry([
            'title' => $entryData['title'],
            'entry' => $entryData['entry'],
            'date' => $entryData['date'],
        ]);

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }
    
    public function updateEntry($id) {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        $_PUT = json_decode(file_get_contents('php://input'), true);

        $inputData = [
            'title' => $_PUT['title'] ?: null,
            'entry' => $_PUT['entry'] ?: null,
            'date' => $_PUT['date'] ?: null,
        ];
        $entryData = $this->validateDiary($inputData);

        $entryModel = new Entry();
        $entryModel->updateEntry(
        [
            'id' => $id,
            'title' => $entryData['title'],
            'entry' => $entryData['entry'],
            'date' => $entryData['date'],
        ]
    );

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function deleteEntry($id) {
        if (!$id) {
            http_response_code(404);
            echo json_encode(["error" => "entry not found."]);
            exit();
            }

        $entryModel = new Entry();
        $result = $entryModel->deleteEntry(['id' => $id]);

        if ($result) {
            echo json_encode(['success' => true]);
            } else {
            echo json_encode(['error' => 'error deleting entry.']);
            }
        exit();
    }

    public function entriesView() {
        include 'public/assets/views/entries/entry-view.html';
        exit();
    }

    public function entriesAddView() {
        include 'public/assets/views/entries/entry-add.html'; 
        exit();
    }

    public function entriesDeleteView($id) {
        $entryModel = new Entry();
        $entry = $entryModel->getEntryByID($id);
        
        if (!$entry) {
            http_response_code(404);
            echo "entry not found";
            exit();
        }

        include 'public/assets/views/entries/entry-delete.html'; 
        exit();
    }

    public function entriesUpdateView($id) {
        $entryModel = new Entry();
        $entry = $entryModel->getEntryByID($id);
        
        if (!$entry) {
            http_response_code(404);
            echo "entry not found";
            exit();
        }
    
        include 'public/assets/views/entries/entry-update.html'; 
        exit();    
    }
}
