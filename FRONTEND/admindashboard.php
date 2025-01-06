<?php
include '../COMMON/utility.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:./login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>SLUKE Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/extra.css">
</head>

<body class="bg-dark text-light  mt-5 pt-5">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-primary bg-light fixed-top">
        <div class="container-fluid justify-content-center">
            <a class="navbar-brand" href="../index.php#home">
                <img src="../IMG/LOGO_NEW-crop.png" alt="" height="20">
            </a>
            <button class="navbar-toggler bg-light border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse col-lg-10" id="navbarSupportedContent">
                <ul class="nav nav-pills text-center me-auto flex-column flex-lg-row">
                    <li class="nav-item mt-2 mt-md-0">
                        <a class="nav-link text-primary" aria-current="page" href="../index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="../index.php#aboutus">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="../index.php#portfolio">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  text-primary" href="./products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="../index.php#social">Contacts</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php
                    switch (true) {
                        case isset($_SESSION['admin']):
                            echo '
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dashboards
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"> <a class="nav-link text-primary" href="./productdashboard.php"> Product Dashboard
                                </a>
                            </li>
                                <li class="nav-item"> <a class="nav-link text-primary" href="./admindashboard.php"> Admin Dashboard
                                </a>
                            </li>
                            </ul>
                            </li>
                            ';


                        case isset($_SESSION['username']):
                            echo '<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle text-primary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i>
                            ' . $_SESSION['username'] . '
                                        </a>
                                         <ul class="dropdown-menu">
                                    </li>
                                    <li class="nav-item"><a class="nav-link text-primary" href="../BACKEND/logout.php">Log out</a>
                                    </li>
                                    </ul>
                                    </li>
                                    
                                    
                                    
                                    ';
                            break;

                        default:
                            echo '
                                <li class="nav-item"> <a class="nav-link text-primary" href="./login.php">
                                        Log in
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link text-primary" href="./register.php">Register</a>
                                </li>';
                            break;
                    }

                    ?>
                </ul>
            </div>
        </div>
    </nav>




    <div class="container">
        <div class="table-responsive border border-primary rounded-3 p-3">
            <h1 class="mb-1">All Users</h1>
            <table class="table table-dark  table-striped table-hover rounded-3">
                <thead>
                    <tr>

                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Authorized</th>
                        <th scope="col">Blocked</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);

                    $stmt = $conn->prepare('SELECT * FROM user');

                    if ($stmt->execute()) {
                        $result = $stmt->get_result(); // Ottieni il risultato della query
                        $i = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                switch (true) {
                                    case $row['blocked']:
                                        echo '<tr class="table-dark">';
                                        break;
                                    case $row['admin']:
                                        echo '<tr class="table-primary">';
                                        break;
                                    case $row['authorized']:
                                        echo '<tr class="table-success">';
                                        break;


                                    default:
                                        echo '<tr class="table-danger">';
                                        break;
                                }

                                echo '  <th scope="row">@' . $row['username'] . '</th>
                                <td>' . $row['mail'] . '</td>
                                <td> <div class="form-check form-switch"><input class="form-check-input" name="adm' . $i . '" type="checkbox" disabled ';
                                if ($row['admin']) {
                                    echo 'checked';
                                }
                                // switch figo ma che non so far funzionare senza onclick <input data-toggle="toggle" data-size="sm" data-onstyle="outline-primary" data-onlabel="Yes" data-offlabel="No" data-offstyle="outline-secondary"
                                //<link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/css/bootstrap5-toggle.min.css" rel="stylesheet">
                                //<script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.ecmas.min.js"></script>
                                echo
                                '></div></td>
                                <td><div class="form-check form-switch">
                                <input class="form-check-input" name="all' . $i . '" type="checkbox" ';
                                if ($row['authorized']) {
                                    echo 'checked onclick="updateUserStatus(\'' . $row['username'] . '\', \'unauthorize\')"';
                                    if ($row['admin']) {
                                        echo ' disabled';
                                    }
                                } else {
                                    echo 'onclick="updateUserStatus(\'' . $row['username'] . '\', \'authorize\')"';
                                }
                                echo '></div></td>';
                                echo '<td><div class="form-check form-switch">
                                <input class="form-check-input" name="all' . $i . '" type="checkbox"';
                                if ($row['blocked']) {
                                    echo 'checked onclick="updateUserStatus(\'' . $row['username'] . '\', \'unblock\')"';
                                } else {
                                    echo 'onclick="updateUserStatus(\'' . $row['username'] . '\', \'block\')"';
                                    if ($row['admin']) {
                                        echo ' disabled';
                                    }
                                }
                                echo '></td><td>
                            <button class="btn text-danger btn-dark btn-sm" onclick="updateUserStatus(\'';
                                echo $row['username'] . '\', \'delete\')">Delete</button></td> </tr>';
                                $i++;
                            }
                        } else {
                            echo "You do not belong here!";
                            return false;
                        }
                    } else {
                        echo "Error in the connection phase";
                        return false;
                    }
                    ?>


                </tbody>
            </table>
            <div id="statusAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display:none;">
                <span id="statusMessage">Operazione completata con successo</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        </div>
    </div>


    <script>
        function updateUserStatus(userId, action) {
            console.log("Sending data:", {
                userId,
                action
            }); // Log per debug

            fetch('../BACKEND/updateUserStatus.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        userId,
                        action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response:", data); // Log per debug
                    if (data.success) {
                        sessionStorage.setItem('alertMessage', 'Operation completed');
                        sessionStorage.setItem('alertColor', 'success');
                        location.reload();
                    } else {
                        alertB("Errore: " + data.message, "danger");
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert("Errore nella risposta del server: " + error.message);
                });
        }
    </script>
    <div class="d-flex" style="height: 100px;">
        <div class="vr"></div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../JS/alertB.js"></script>
</body>

</html>