<?php
include '../BACKEND/utility.php';
session_start();
if (!isset($_SESSION['username'])) {
    // Salva l'URL corrente
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: ./login.php");

    exit();
} else {
    //load cart
    $conn = dbConnect();
    if (!isset($_SESSION['cart'])) {
        loadCart($conn, 'load');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>SLUKE My orders</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/logo transparent0000.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/extra.css">
    <script src="../JS/alertB.js"></script>
    <script>
        let activeAudio = null;
    </script>
</head>



<body class="bg-dark text-light  mt-5 pt-4">
    <!-- Spinner Overlay -->
    <div id="spinner-overlay" class="d-flex d-none justify-content-center align-items-center position-fixed w-100 h-100 bg-dark bg-opacity-75" style="top: 0; left: 0;z-index: 2000;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
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
                        <a class="nav-link text-primary" href="./products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="#social">Contacts</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto text-center">
                    <?php
                    switch (true) {
                        case isset($_SESSION['admin']):
                            echo '
                            
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-primary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dashboards
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item ms-2 mb-2"> <a class="nav-link text-primary" href="./productdashboard.php"> Product Dashboard
                                </a>
                            </li>
                                <li class="nav-item ms-2"> <a class="nav-link text-primary" href="./admindashboard.php"> Admin Dashboard
                                </a>
                            </li>
                            </ul>
                            </li>
                            
                            ';


                        case isset($_SESSION['username']):
                            echo '<li class="nav-item dropdown"> <a class="nav-link  dropdown-toggle text-primary" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i>
                            ' . $_SESSION['username'] . '
                                        </a>
                                         <ul class="dropdown-menu">
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-primary" href="./fdl.php">Free Downloads!</a>
                                    </li>
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-secondary" href="">Your orders</a>
                                    </li>
                                    <li class="nav-item ms-2"><a class="nav-link text-primary" href="../BACKEND/logout.php">Log out</a>
                                    </li>
                                    </ul>
                                    </li>
                                    <li class="nav-item mt-2 mt-md-0"> 
                                        <button class="btn btn-outline-primary mx-1 d-none d-md-block" type="button" data-bs-toggle="offcanvas" data-bs-target="#cart" aria-controls="offcanvasScrolling">Cart <i class="bi bi-cart-dash"></i></button>
                                    </li>
                                    </ul>
                                </div>
                                <button class="btn btn-outline-primary mx-3 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#cart" aria-controls="offcanvasScrolling">Cart <i class="bi bi-cart-dash"></i></button>
                                    
                                    ';
                            break;

                        default:
                            echo '
                            
                                <li class="nav-item"> <a class="nav-link text-primary" href="./login.php">
                                        Log in
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link text-primary" href="./register.php">Register</a>
                                </li>
                                </ul>
                                </div>';
                            break;
                    }

                    ?>

    </nav>
    <div class="container">
        <h1 class="">Your orders</h1>


        <?php

        $orderQuery = 'SELECT * FROM order_head oh WHERE oh.username = ? ORDER BY oh.date DESC;';
        $stmt1 = $conn->prepare($orderQuery);
        $stmt1->bind_param('s', $_SESSION['username']);
        $empty = true;

        if ($stmt1->execute()) {
            $result1 = $stmt1->get_result();

            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) {
                    $stmt2 = $conn->prepare('SELECT * FROM order_detail WHERE order_id = ?');
                    $stmt2->bind_param('i', $row1['id']);
                    if ($stmt2->execute()) {
                        $result2 = $stmt2->get_result();

                        if ($result2->num_rows > 0 && $row1['confirmed']) {
                            echo '<div class="card mb-4 bg-secondary text-light">';
                            echo '<div class="card-header">';
                            echo '<h5 class="card-title">Order ID: ' . $row1['id'] . '</h5>';
                            echo $row1['confirmed'] ? '<span class="badge bg-success">Confirmed</span>' : '<span class="badge bg-warning">Pending</span>';
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';

                            $totalOrder = 0; // Inizializza il totale dell'ordine

                            while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {
                                $stmt3 = $conn->prepare('SELECT * FROM product WHERE id = ?');
                                $stmt3->bind_param('s', $row2['prod_id']);
                                if ($stmt3->execute()) {
                                    $result3 = $stmt3->get_result();

                                    if ($result3->num_rows > 0) {
                                        $row3 = $result3->fetch_array(MYSQLI_ASSOC);
                                        spawnProd4($row3, $row2['cur_price'], $row2['quantity']);
                                        $totalOrder += $row2['cur_price'] * $row2['quantity']; // Aggiorna il totale
                                    }
                                }
                            }

                            echo '</div>'; // Chiudi row
                            echo '<div class="mt-4 text-end">';
                            echo '<h4>Total: €' . number_format($totalOrder, 2) . '</h4>'; // Mostra il totale
                            echo '</div>';
                            echo '</div>'; // Chiudi card-body
                            echo '</div>'; // Chiudi card
                        }
                    }
                }
            } else {
                echo '<div class="alert alert-info">No orders found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Error fetching orders.</div>';
        }
        ?>

    </div>
    <div class="offcanvas offcanvas-end " data-bs-scroll="true" tabindex="-1" id="cart" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Shopping Cart <i class="bi bi-cart-dash"></i></h5>
            <button type="button" class="btn-close" style="color: var(--bs-light);" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php
            displayCart();
            ?>
        </div>
    </div>







    <footer id="social" class="footer bg-secondary mt-5">
        <div class="container p-3">
            <h3 class="text-primary fw-bold fs-1">Sluke</h3>
            <h5 class="my-3">Follow me on my socials to never miss any content!</h5>
            <p class="my-3 fs-5 "><i class="bi bi-envelope-at-fill text-primary"></i> :musicbysluke@gmail.com</p>
            <div class="social-links d-flex justify-content-around">
                <a href="https://www.youtube.com/@sluke1547" target="_blank" rel="noopener noreferrer">
                    <h1><i class="bi bi-youtube c"></i></h1>
                </a>
                <a href="https://open.spotify.com/intl-it/artist/4zTNDtXBjnewJ2qIWvdwEe?si=2f29d7a2c37d4520" target="_blank" rel="noopener noreferrer">
                    <h1><i class="bi bi-spotify"></i></h1>
                </a>
                <a href="https://www.instagram.com/musicbysluke" target="_blank" rel="noopener noreferrer">
                    <h1><i class="bi bi-instagram"></i></h1>
                </a>
                <a href="https://www.tiktok.com/@musicbysluke" target="_blank" rel="noopener noreferrer">
                    <h1><i class="bi bi-tiktok"></i></h1>
                </a>
                <a href="https://soundcloud.com/slukemusicc" target="_blank" rel="noopener noreferrer">
                    <h1><i class="bi bi-soundwave"></i></h1>
                </a>
            </div>

        </div>
    </footer>
    <script>
        // Mostra lo spinner al caricamento e lo nasconde alla fine
        function showSpinner() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').style.display = 'none';
        }

        // Funzione per mostrare un alert
        function addTocart(id, action) {
            showSpinner();
            console.log("Sending data:", {

                id,
                action
            }); // Log per debug

            fetch('../BACKEND/cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response:", data); // Log per debug
                    if (data.success) {
                        hideSpinner();
                        if (action === 'add') {
                            sessionStorage.setItem('alertMessage', 'Product added to cart');
                            sessionStorage.setItem('alertColor', 'success');
                            location.reload();
                        } else if (action === 'delete' || action === 'decrease') {
                            sessionStorage.setItem('alertMessage', 'Product removed from cart');
                            sessionStorage.setItem('alertColor', 'success');
                            location.reload();

                        } else if (action === 'checkout') {
                            sessionStorage.setItem('alertMessage', 'Checked out successfully');
                            sessionStorage.setItem('alertColor', 'success');
                            window.location.href = './orders.php';
                        } else {
                            alertB("Error: " + data.message, "danger");
                        }



                    } else {
                        hideSpinner();
                        alertB("Error: " + data.message, "danger");
                    }
                })
                .catch(error => {
                    hideSpinner();
                    console.error('Fetch error:', error);
                    alertB("Errore nella risposta del server: " + error.message);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>