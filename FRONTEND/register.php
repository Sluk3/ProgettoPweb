<!DOCTYPE html>
<html>

<head>
    <title>SLUKE Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/extra.css">
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
                        <a class="nav-link text-primary" href="#social">Contacts</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item"><a class="nav-link text-primary" href="./login.php">Log in</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="">

        <form class="form col-sm-8 col-md-6 col-lg-4 col-xl-4 m-auto d-grid" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1 class="text-center text-light">Register</h1>
            <div class="mb-3 p-2 ">
                <label class="form-label" for="mail">Email</label>
                <div class="input-group">
                    <div class="input-group-text">M</div>
                    <input type="email" class="form-control" name="mail" id="mail" placeholder="name@example.com" required>
                </div>
            </div>

            <div class="mb-3 p-2 ">
                <label class="form-label" for="username">Username</label>
                <div class="input-group">
                    <div class="input-group-text">@</div>
                    <input type="text" class="form-control" name="user" id="username" placeholder="Username" autocomplete="username" required>
                </div>
            </div>
            <div class="mb-3 p-2 ">
                <label for="password" class="form-label">Password</label>
                <input type="password" minlength="8" class="form-control" name="password" id="password" placeholder="********" required>
                <div id="passwordHelpBlock" class="form-text text-light">
                    Your password must be 8-20 characters long, contain letters, numbers and special characters, and must not contain spaces or emoji.
                </div>
            </div>
            <div class="p-2 d-grid">
                <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#regModal">Register</button>
            </div>
            <div class="m-3">
                <p>Already a user? <a class="nav-link text-primary" href="./login.php">Log in</a></p>
            </div>
            <div>






            </div>
        </form>

    </div>






    <?php include '../COMMON/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="../JS/alertB.js"></script>
    <?php
    include '../COMMON/utility.php';

    // Controllare se il form è stato inviato
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Usare la funzione test_input per pulire i dati
        $username = test_input($_POST['user']);
        $mail = test_input($_POST['mail']);
        $password = trim($_POST['password']);
        // Creare una connessione al database (modifica con i tuoi dati)
        $conn = dbConnect();
        if (searchInDB($conn, "SELECT mail FROM user WHERE mail= ?", $mail)) {
            echo '<script>
                        document.getElementById("mail").classList.add("is-invalid");
                        sessionStorage.setItem(\'alertMessage\', \'Email already in use!\');
                        sessionStorage.setItem(\'alertColor\', \'danger\');
                        </script>';
            exit();
        } elseif (searchInDB($conn, "SELECT username FROM user WHERE username= ? ", $username)) {
            echo '<script>
                        document.getElementById("username").classList.add("is-invalid");
                        sessionStorage.setItem(\'alertMessage\', \'Username already in use!\');
                        sessionStorage.setItem(\'alertColor\', \'danger\');
                        </script>';
            exit();
        } elseif (!validatePassword($password)) {
            echo '<script>
                        document.getElementById("password").classList.add("is-invalid");
                        sessionStorage.setItem(\'alertMessage\', \'Password should be at least 8 characters, must contain letters, numbers and ! @ _ symbols\');
                        sessionStorage.setItem(\'alertColor\', \'danger\');
                        </script>';
            exit();
        } else {

            // Preparare l'istruzione SQL per inserire i dati
            $sql = "INSERT INTO user (username, mail, pwd) VALUES ('$username', '$mail', '" . password_hash($password, PASSWORD_BCRYPT) . "')";

            // Eseguire l'istruzione
            if ($conn->query($sql) === TRUE) {

                login($conn, $username, $password);

                echo '<script>sessionStorage.setItem(\'alertMessage\', \'Registration successfull!\');
                        sessionStorage.setItem(\'alertColor\', \'success\');
                        window.location.href = "../index.php";
                        </script>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Chiudere la connessione
            $conn->close();
        }
    }
    ?>
</body>

</html>