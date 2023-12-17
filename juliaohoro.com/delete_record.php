<?php
global $pdo;
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config/connection_database.php";

if (!$pdo) {
    echo "Error: Unable to establish a database connection.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    try {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting record: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}
?>
