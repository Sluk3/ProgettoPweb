<?php
include '../COMMON/utility.php';
session_start();
if (!isset($_SESSION['username'])) {
    // Salva l'URL corrente
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: ./login.php"); // Reindirizza alla pagina di login

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
    <title>SLUKE Free Downloads</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/extra.css">
    <script>
        let activeAudio = null;
    </script>
</head>


<body class="bg-dark text-light  mt-5 pt-5">
    <!-- Spinner Overlay -->
    <?php include '../COMMON/spinner.php'; ?>
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
                            <a class="nav-link dropdown-toggle text-primary" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            echo '<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle text-primary" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i>
                            ' . $_SESSION['username'] . '
                                        </a>
                                         <ul class="dropdown-menu">
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-secondary" href="">Free Downloads!</a>
                                    </li>
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-primary" href="./orders.php">Your orders</a>
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
    <div class="container">
        <h1 class="">Products</h1>
        <?php //type


        $stmt1 = $conn->prepare('SELECT * FROM type');

        if ($stmt1->execute()) {
            $result1 = $stmt1->get_result(); // Ottieni il risultato della query

            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) {

                    $stmt2 = $conn->prepare('SELECT product.*, list_prices.price, list_prices.date, list_prices.list_id FROM product JOIN type ON product.type_id = type.id JOIN list_prices ON product.id = list_prices.prod_id WHERE list_prices.price = 0 AND type.name = \'' . $row1['name'] . '\' AND list_prices.date = (
    SELECT MAX(date) 
    FROM list_prices 
    WHERE list_prices.prod_id = product.id
);');
                    if ($stmt2->execute()) {
                        $result2 = $stmt2->get_result(); // Ottieni il risultato della query

                        if ($result2->num_rows > 0) {
                            echo ' <h2 class="my-4">' . $row1['name'] . '</h2>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5  g-4">';

                            while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {
                                spawnProd3($row2, $row1['id'], $conn);
                            }
                            echo ' </div>';
                        }
                    }
                }
            } else {
                echo "No content!!";
                return false;
            }
        } else {
            echo "Error in the connection phase";
            return false;
        }

        ?>

    </div>
    <?php include '../COMMON/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="../JS/alertB.js"></script>
    <script src="../JS/cart.js"></script>
    <script src="../JS/spinner.js"></script>
</body>

</html>