<?php
include '../BACKEND/utility.php';
session_start();

// Controlla se esiste un URL di reindirizzamento
error_log(print_r($_SESSION, true));
$redirect_to = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : '../index.php';
error_log($redirect_to)
?>
<!DOCTYPE html>
<html>

<head>
    <title>SLUKE login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/logo transparent0000.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">

    <link rel="stylesheet" href="../CSS/extra.css">
    <script src="../JS/alertB.js"></script>
</head>

<body class="bg-dark text-light mt-5 pt-5">
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

                    <li class="nav-item"><a class="nav-link text-primary" href="./register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>





    <div class="container">

        <form class="form col-sm-8 col-md-6 col-lg-4 m-auto d-grid" action="" method="post">
            <h1 class="text-center text-light">Log in</h1>
            <div class="mb-3  ">
                <label class="form-label" for="specificSizeInputGroupUsername">Username</label>
                <div class="input-group">
                    <div class="input-group-text">@</div>
                    <input type="text" class="form-control" name="user" id="user" placeholder="Username" required>
                </div>
            </div>
            <div class="mb-3 py-3 ">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
            </div>
            <div class="my-2 d-grid">
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
            <div class=" mt-3 d-grid gap-2">
                <a href="./register.php" type="button" class="btn btn-outline-primary">Register</a>
                <a href="" class="text-primary">Forgot password?</a>

            </div>
            <?php


            // Controllare se il form è stato inviato
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Usare la funzione test_input per pulire i dati
                $username = test_input($_POST['user']);
                $password = trim($_POST['password']);
                // Creare una connessione al database 
                $conn = dbConnect();
                switch (login($conn, $username, $password)) {
                    case 'user':
                        echo '<script>
                        document.getElementById("user").classList.add("is-invalid");
                        document.getElementById("password").classList.add("is-invalid");
                        alertB("User not existent");
                        </script>';
                        break;

                    case 'pwd':
                        echo '<script>
                        document.getElementById("password").classList.add("is-invalid");
                        alertB("Invalid password");
                        </script>';
                        break;
                    case 'unauth':
                        echo '<script>
                        document.getElementById("user").classList.add("is-invalid");
                        alertB("User not authorized by the admin");
                        </script>';
                        break;
                    case 'blk':
                        echo '<script>
                        document.getElementById("user").classList.add("is-invalid");
                        alertB("User blocked");
                        </script>';
                        break;

                    case 'ok':
                        if (isAdmin($username)) {
                            $_SESSION['admin'] = 1;
                            $_SESSION['pwd'] = $password;
                        }
                        if (isset($redirect_to)) {
                            echo "<script>
                            sessionStorage.setItem('alertMessage', 'Logged in successfully');
                            sessionStorage.setItem('alertColor', 'success');
                            window.location.href = \"$redirect_to\";</script>";
                            exit(); // Assicurati di uscire dallo script PHP dopo il reindirizzamento
                        }

                        break;

                    default:
                        echo '<script>
                        document.getElementById("user").classList.add("is-invalid");
                        document.getElementById("password").classList.add("is-invalid");
                        </script>';
                        break;
                }
                // Controlla se esiste un URL di reindirizzamento

            }
            ?>
        </form>

    </div>

    <div class="d-flex" style="height: 140px;">
        <div class="vr"></div>
    </div>



    <footer id="social" class="footer bg-secondary mt-5 positionc-bottom">
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
                    <h1><i class="bi bi-soundwave "></i></h1>
                </a>
            </div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>