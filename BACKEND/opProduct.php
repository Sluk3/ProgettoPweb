<?php
include '../COMMON/utility.php';

session_start();
// Gestisci l'accesso
if (!isset($_SESSION['admin'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit;
}
// Connessione al database
$conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$data = [];

// Recupera i dati testuali da $_POST
$data['id'] = $_POST['id'];
$data['title'] = mysqli_real_escape_string($conn, $_POST['title']);
$data['type_id'] = $_POST['type_id'];
$data['descr'] = isset($_POST['descr']) ? mysqli_real_escape_string($conn, $_POST['descr']) : null;
$data['bpm'] = $_POST['bpm'] ?? null;
$data['tonality'] = $_POST['tonality'] ?? null;
$data['genre'] = $_POST['genre'];
$data['num_sample'] = $_POST['num_sample'] ?? null;
$data['num_tracks'] = $_POST['num_tracks'] ?? null;
$data['price'] = $_POST['price'];
$data['listnprice'] = $_POST['listnprice'] ?? null;
$data['dateprice'] = $_POST['dateprice'] ?? null;
$data['action'] = $_POST['action'];
$data['active'] = 0;
// Recupera il file da $_FILES, se presente
if (isset($_FILES['audiopath'])) {
    $fileTmpPath = $_FILES['audiopath']['tmp_name'];
    $fileName = str_replace(' ', '_', $_FILES['audiopath']['name']);
    $uploadFileDir = '../AUDIO/';
    $dest_path = $uploadFileDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $data['audiopath'] = $dest_path; // Puoi salvare il percorso o altre info del file
    } else {
        $data['audiopath'] = null; // Gestisci l'errore in caso di fallimento del caricamento
    }
}
if (isset($_FILES['productpath'])) {
    $fileTmpPath = $_FILES['productpath']['tmp_name'];
    $fileName = str_replace(' ', '_', $_FILES['productpath']['name']);
    $uploadFileDir = '../PRODUCTS/';
    $dest_path = $uploadFileDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $data['productpath'] = $dest_path; // Puoi salvare il percorso o altre info del file
    } else {
        $data['productpath'] = null; // Gestisci l'errore in caso di fallimento del caricamento
    }
}

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}
// Connessione al database
$conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);

$pchange = prodCheckValidity($data, $conn);
$action = $data['action'];
unset($data['action']);



if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}
try {
    $conn->begin_transaction();

    // Scegli l'azione da eseguire
    switch ($action) {
        case 'update':
            $stmt = $conn->prepare('SELECT id FROM product WHERE id = ? AND type_id = ?');
            $stmt->bind_param('si', $data['id'], $data['type_id']);
            $typechange = false;
            $stmt->execute();
            $result = $stmt->get_result(); // Ottieni il risultato della query
            $typechange = $result->num_rows == 0;
            $stmt->close();
            // controllo pricechange

            $prodId = $data['id'];
            $query = "UPDATE product SET ";
            foreach ($data as $key => $value) {

                if (!str_contains($key, "price")) {
                    if (!empty($value)) {
                        if ($key == "id" && $typechange) {
                            $newpid = newPid($data['type_id'], $conn); //Create new id if type changed
                            $query = $query . $key . " = '" . $newpid . "', ";

                            unset($data[$key]);
                        } else {

                            if (is_string($value)) {
                                $query = $query . $key . " = '" . $value . "', ";
                                unset($data[$key]);
                            } else {
                                $query = $query . $key . " = " . $value . ", ";
                                unset($data[$key]);
                            }
                        }
                    } else {
                        unset($data[$key]);
                    }
                }
            }
            $query = rtrim($query, ', ') . " WHERE id = '" . $prodId . "' ;";

            // Prepara e esegue la query
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
                exit;
            }

            if ($stmt->execute()) {
                if ($pchange) {
                    //------------------------price query---------------------------------
                    $query2 = "INSERT INTO list_prices (prod_id, price, date, list_id) VALUES (?, ?, ?, ?)";
                    $stmt2 = $conn->prepare($query2);
                    if (!$stmt2) {
                        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
                        exit;
                    }
                    $prodId = isset($newpid) ? $newpid : $prodId;
                    $stmt2->bind_param("sdsi", $prodId, $data['price'], $data['dateprice'], $data['listnprice']);


                    if ($stmt2->execute()) {
                        $conn->commit();
                        cleanupUnusedFiles($conn);
                        echo json_encode(['success' => true]);
                    } else {
                        error_log("Execution failed: " . $stmt2->error);
                        error_log("MySQL Error: " . $conn->error);
                        error_log("Statement Debug:\n" . print_r($stmt2, true));
                        echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                        $conn->rollback();
                        exit;
                    }
                } else {
                    $conn->commit();
                    cleanupUnusedFiles($conn);
                    echo json_encode(['success' => true]);
                }
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                exit;
            }

            break;
        case 'insert':

            $data['id'] = newPid($data['type_id'], $conn);
            $prodId = $data['id'];

            $query = "INSERT INTO product (";
            foreach ($data as $key => $value) {
                if (!str_contains($key, "price")) {
                    if (!empty($value)) {
                        if (!str_contains($key, "price")) {
                            $query = $query . $key  . ", ";
                        }
                    } else {
                        unset($data[$key]);
                    }
                }
            }
            $query = rtrim($query, ', ')  . ") VALUES (";
            foreach ($data as $key => $value) {
                if (!str_contains($key, "price")) {
                    if (!empty($value)) {

                        if (is_string($value)) {
                            $query = $query . "'" . htmlspecialchars($value)  . "', ";
                            unset($data[$key]);
                        } else {
                            $query = $query .  $value  . ", ";
                            unset($data[$key]);
                        }
                    } else {
                        unset($data[$key]);
                    }
                }
            }
            $query = rtrim($query, ', ')  . ");";

            // Prepara e esegue la query
            $stmt = $conn->prepare($query);

            if ($stmt->execute()) {

                //------------------------price query---------------------------------
                $query2 = "INSERT INTO list_prices (prod_id, price, date, list_id) VALUES (?, ?, ?, ?)";

                $stmt2 = $conn->prepare($query2);
                if (!$stmt2) {
                    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
                    exit;
                }

                $stmt2->bind_param("sdsi", $prodId, $data['price'], $data['dateprice'], $data['listnprice']);


                if ($stmt2->execute()) {
                    $conn->commit();
                    echo json_encode(['success' => true]);
                    exit;
                } else {
                    error_log("Execution failed: " . $stmt2->error);
                    error_log("MySQL Error: " . $conn->error);
                    error_log("Statement Debug:\n" . print_r($stmt2, true));
                    echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                    $conn->rollback();
                    exit;
                }
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                exit;
            }

            break;
        case 'delete':
            $query = "DELETE FROM product WHERE id = ? ;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $data['id']);

            if ($stmt->execute()) {
                $conn->commit();
                cleanupUnusedFiles($conn);
                echo json_encode(['success' => true]);
                exit;
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                exit;
            }
            $stmt->close();
            break;
        case 'toggleActive':
            $stmt = $conn->prepare('SELECT active FROM product WHERE id = ?');
            $stmt->bind_param('s', $data['id']);
            $stmt->execute();
            $stmt->bind_result($data['active']);
            $stmt->fetch();
            $stmt->close();

            if ($data['active']) {
                $query = "UPDATE product SET active = 0 WHERE id = '" . $data['id'] . "' ;";
            } else {
                $query = "UPDATE product SET active = 1 WHERE id = '" . $data['id'] . "' ;";
            }
            error_log($data['active'] . " " . $data['id'] . " " . $query);
            $stmt = $conn->prepare($query);

            if ($stmt->execute()) {
                $conn->commit();
                $stmt->close();
                echo json_encode(['success' => true]);
                exit;
            } else {
                $conn->rollback();
                $stmt->close();
                echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
                exit;
            }

            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
    }
} catch (Exception $e) {
    // Rollback in caso di errore
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Execution failed: ' .  $e->getMessage()]);
}
function prodCheckValidity($data, $conn)
{
    $checkStmt = $conn->prepare('SELECT COUNT(*) FROM list_prices WHERE prod_id = ? AND date = ?');
    $checkStmt->bind_param('ss', $data['id'], $data['dataprice']);
    $checkStmt->execute();
    $count = 0;
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    if ($count > 0) {
        // Messaggio di errore per data duplicata
        echo json_encode(['success' => false, 'message' => 'Invalid date, already set this product']);
        exit;
    }

    //--------------------------------------------------------
    $checkStmt = $conn->prepare('SELECT price, date
FROM list_prices
WHERE prod_id = ?
  AND date <= NOW()
ORDER BY date DESC
LIMIT 1');
    $checkStmt->bind_param('s', $data['id']);
    $checkStmt->execute();
    $prevprice = 0;
    $maxd = 0;
    $checkStmt->bind_result($prevprice, $maxd);
    $checkStmt->fetch();
    $checkStmt->close();


    switch (true) {
        case $data['type_id'] == 1:
            unset($data['num_sample']);
            unset($data['num_track']);
            break;
        case $data['type_id'] == 2 || $data['type_id'] == 3:
            unset($data['bpm']);
            unset($data['tonality']);
            break;
        case $data['type_id'] == 4:
            unset($data['num_sample']);
            unset($data['num_track']);
            unset($data['bpm']);
            unset($data['tonality']);
        case $data['type_id'] == 5 || $data['type_id'] == 6:
            unset($data['bpm']);
            unset($data['tonality']);
            unset($data['num_sample']);
            break;
        default:
            break;
    }
    return $prevprice != $data['price'];
}
function newPid($type_id, $conn)
{
    $letter = chr(64 + $type_id); // 64 + 1 = 'A', 64 + 2 = 'B', etc.
    $query = "SELECT CAST(SUBSTRING(id, 2) AS UNSIGNED) AS numeric_part
          FROM product
          WHERE type_id = '{$type_id}'
          ORDER BY numeric_part DESC
          LIMIT 1";
    $id = 0;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    $max_number = $id ? $id + 1 : 1; // Incrementa se esiste, altrimenti parte da 1
    return $letter . $max_number;
}
