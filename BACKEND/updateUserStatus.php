<?php
include '../COMMON/utility.php';

session_start();
// Gestisci l'accesso
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}

// Decodifica il corpo della richiesta JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}
$userId = $data['userId'];
$action = $data['action'];


// Connessione al database
$conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Scegli l'azione da eseguire
switch ($action) {
    case 'authorize':
        $query = "UPDATE user SET authorized = 1 WHERE username = ?";
        break;
    case 'unauthorize':
        $query = "UPDATE user SET authorized = 0 WHERE username = ?";
        break;
    case 'block':
        $query = "UPDATE user SET blocked = 1 WHERE username = ?";
        break;
    case 'unblock':
        $query = "UPDATE user SET blocked = 0 WHERE username = ?";
        break;
    case 'delete':
        $query = "DELETE FROM user WHERE username = ?";
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

// Prepara e esegue la query
$stmt = $conn->prepare($query);


if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("s", $userId);



if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
}
