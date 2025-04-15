<?php
require_once "app/models/Model.php";
require_once "app/models/Entry.php"; 
require_once "app/controllers/DiaryController.php";

$env = parse_ini_file(__DIR__ . '/.env');
define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);

use app\controllers\DiaryController;

$uri = strtok($_SERVER["REQUEST_URI"], '?'); 
$uriArray = explode("/", trim($uri, "/")); 

if (empty($uriArray[0])) {
    include 'public/assets/views/entries/homepage.html';
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'api' && isset($uriArray[1]) && $uriArray[1] === 'diary') {
    $diaryController = new DiaryController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $diaryController->getAllEntries();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $diaryController->saveEntry();
    }
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'api' && isset($uriArray[1]) && $uriArray[1] === 'entries') {
    $diaryController = new DiaryController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
        if ($id) {
            $diaryController->getEntryByID($id);
        } else {
            $diaryController->getAllEntries();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $diaryController->saveEntry();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
        $diaryController->updateEntry($id);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = isset($uriArray[2]) ? intval($uriArray[2]) : null;
        $diaryController->deleteEntry($id);
    }
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'entries' && $uriArray[1] === 'view') {
    $diaryController = new DiaryController();
    $diaryController->entriesView();
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'entries' && $uriArray[1] === 'add') {
    $diaryController = new DiaryController();
    $diaryController->entriesAddView();
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'entries' && $uriArray[1] === 'delete' && isset($uriArray[2])) {
    $diaryController = new DiaryController();
    $diaryController->entriesDeleteView($uriArray[2]);
    exit();
}

if (isset($uriArray[0]) && $uriArray[0] === 'entries' && $uriArray[1] === 'update' && isset($uriArray[2])) {
    $diaryController = new DiaryController();
    $diaryController->entriesUpdateView($uriArray[2]);
    exit();
}


include __DIR__ . '/public/assets/views/notFound.html';
exit();
