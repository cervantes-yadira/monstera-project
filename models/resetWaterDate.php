<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$path = $_SERVER['DOCUMENT_ROOT'].'/../config.php';
require_once $path;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['lastWatered'])) {
    try {
        // Instantiate our PDO database object
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE Plants SET LastWatered = :lastWatered WHERE id = :id";
        $statement = $dbh->prepare($sql);
        $statement->bindParam(':lastWatered', $data['lastWatered']);
        $statement->bindParam(':id', $data['id'], PDO::PARAM_INT);

        if ($statement->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update plant.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}