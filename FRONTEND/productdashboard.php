<?php
include '../BACKEND/utility.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:./login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>SLUKE Product Dashboard</title>
    <meta charset="utf-8">
    <link rel="icon" type="image" href="../IMG/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">

    <link rel="stylesheet" href="../CSS/extra.css">
    <script src="../JS/alertB.js"></script>
</head>

<body class="bg-dark text-light  mt-5 pt-5">
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
                                <li class="nav-item active"> <a class="nav-link text-primary" href="./productdashboard.php"> Product Dashboard
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



        <h1 class="mb-3">Products</h1>
        <div class="col"><!-- Button trigger modal -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#insertdetails">
                <i class="bi bi-zoom-in"></i> Insert new product
            </button>
        </div>
        <div class="modal fade" id="insertdetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog text-dark modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-4 " id="staticBackdropLabel">Insert</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body" id="modalForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input class="form-control" id="title_insert" type="text" placeholder="Example Title" aria-label="readonly input example">
                        </div>

                        <div class="mb-3">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <div class="input-group ">
                                        <label class="input-group-text" for="type">Type</label>
                                        <select id="type_insert" class="form-select">
                                            <?php


                                            $conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);

                                            $stmt = $conn->prepare('SELECT * FROM type');

                                            if ($stmt->execute()) {
                                                $result = $stmt->get_result(); // Ottieni il risultato della query
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</a></li>';
                                                    }
                                                }
                                            }
                                            $stmt->close();

                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">Genre/Plugin type</span>
                                        <input class="form-control" id="genre_insert" type="text" placeholder="Trap" aria-label="genre">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descr" class="form-label">Description</label>
                            <textarea class="form-control" style="resize:none" id="descr_insert" placeholder="Example description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-music-note"></i></span>
                                        <span class="input-group-text">BPM</span>
                                        <input type="number" id="bpm_insert" class="form-control" value="120" max="250" aria-label="bpm">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                        <span class="input-group-text">Key</span>
                                        <select id="tonality_insert" class="form-control" aria-label="mkey">
                                            <option value="C">C</option>
                                            <option value="Cm">Cm</option>
                                            <option value="C#">C#</option>
                                            <option value="C#m">C#m</option>
                                            <option value="D">D</option>
                                            <option value="Dm">Dm</option>
                                            <option value="D#">D#</option>
                                            <option value="D#m">D#m</option>
                                            <option value="E">E</option>
                                            <option value="Em">Em</option>
                                            <option value="F">F</option>
                                            <option value="Fm">Fm</option>
                                            <option value="F#">F#</option>
                                            <option value="F#m">F#m</option>
                                            <option value="G">G</option>
                                            <option value="Gm">Gm</option>
                                            <option value="G#">G#</option>
                                            <option value="G#m">G#m</option>
                                            <option value="A">A</option>
                                            <option value="Am">Am</option>
                                            <option value="A#">A#</option>
                                            <option value="A#m">A#m</option>
                                            <option value="B">B</option>
                                            <option value="Bm">Bm</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row gy-3">
                                <div class=" col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-music-note-list"></i></span>
                                        <span class="input-group-text">Samples number</span>
                                        <input type="number" min="0" id="num_samples_insert" class="form-control" aria-label="sam">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-cassette"></i></span>
                                        <span class="input-group-text">Max stems number</span>
                                        <input type="number" min="0" id="num_tracks_insert" class="form-control" aria-label="trk">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="mb-3">
                            <div class="row gy-3">
                                <div class=" col-md-6">
                                    <label for="preview_insert">Preview file:</label>
                                    <div class="input-group mt-2">
                                        <input type="file" class="form-control" accept="audio/*" id="preview_insert" aria-describedby="file" aria-label="Upload">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="file_insert">Product for download:</label>
                                    <div class="input-group mt-2">
                                        <input type="file" class="form-control" id="file_insert" aria-describedby="file" aria-label="Upload">
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="mb-3">
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <div class="input-group ">
                                        <span class="input-group-text">Price</span>
                                        <span class="input-group-text">€</span>
                                        <input type="number" id="price_insert" class="form-control" placeholder="19.99" step="0.01" aria-label="Dollar amount (with dot and two decimal places)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group ">
                                        <label class="input-group-text" for="listn">List Number</label>
                                        <select id="listnprice_insert" class="form-select">
                                            <?php


                                            $conn = dbConnect($_SESSION['username'], $_SESSION['pwd']);

                                            $stmt = $conn->prepare('SELECT * FROM list_head');

                                            if ($stmt->execute()) {
                                                $result = $stmt->get_result(); // Ottieni il risultato della query
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['id'] . '</a></li>';
                                                    }
                                                }
                                            }
                                            $stmt->close();

                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="dateprice">Datetime start price</label>
                            <input type="datetime-local" id="dateprice_insert" class="form-control" value=<?php echo '"' . date("Y-m-d H:i:s") . '"'; ?> aria-label="datetime">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="submitinsertButton" class="btn btn-primary" onclick="opProductf('insert', 'insert' )">Insert new product</button>
                    </div>
                </div>
            </div>
        </div>


        <?php //type
        $conn = dbConnect();

        $stmt1 = $conn->prepare('SELECT * FROM type');

        if ($stmt1->execute()) {
            $result1 = $stmt1->get_result(); // Ottieni il risultato della query
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) { // per ogni tipo stampa tutti i prodotti

                    $stmt2 = $conn->prepare('SELECT product.*, list_prices.price, list_prices.list_id, list_prices.date  FROM product JOIN type ON product.type_id = type.id JOIN list_prices ON product.id = list_prices.prod_id WHERE type.name = ? AND list_prices.date = (
    SELECT MAX(date) 
    FROM list_prices 
    WHERE list_prices.prod_id = product.id AND date <= NOW()
);');
                    $stmt2->bind_param('s', $row1['name']);
                    if ($stmt2->execute()) {
                        $result2 = $stmt2->get_result(); // Ottieni il risultato della query
                        if ($result2->num_rows > 0) {
                            echo ' <h2 class="my-4">' . $row1['name'] . '</h2>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5  g-4">';

                            while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {

                                spawnProd2($row2, $row1['id'], $conn);
                            }
                            echo ' </div>';
                        }
                    }
                    $stmt2->close();
                }
            } else {
                echo "No content!!";
                return false;
            }
        } else {
            echo "Error in the connection phase";
            return false;
        }
        $stmt1->close();

        ?>



    </div>
    <!-- Spinner Overlay -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.modal').forEach(modal => {
                if (modal.getAttribute('id') !== 'insertdetails') {
                    const inputs = modal.querySelectorAll('input, select, textarea');
                    const submitButton = modal.querySelector('#submitButton'); // Modifica il selettore se il pulsante ha classi diverse

                    // Disabilita il pulsante di invio inizialmente
                    submitButton.disabled = true;

                    inputs.forEach(input => {
                        input.addEventListener('input', () => {
                            // Abilita il pulsante di invio se uno degli input è stato modificato
                            submitButton.disabled = !Array.from(inputs).some(input => input.defaultValue !== input.value);
                        });
                    });
                }

            });
        });

        function showSpinner() {
            document.getElementById('spinner-overlay').classList.remove("d-none");
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').classList.add("d-none");
        }

        function opProductf(id, action) {

            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => modal.setAttribute('aria-hidden', 'true'));
            showSpinner();
            // Raccogli i dati in variabili
            const title = document.getElementById('title_' + id).value.trim();
            const type_id = document.getElementById('type_' + id).value;
            const descr = document.getElementById('descr_' + id).value.trim();
            const bpm = document.getElementById('bpm_' + id) ? document.getElementById('bpm_' + id).value : null;
            const tonality = document.getElementById('tonality_' + id) ? document.getElementById('tonality_' + id).value : null;
            const genre = document.getElementById('genre_' + id).value.trim();
            const num_sample = document.getElementById('num_samples_' + id) ? document.getElementById('num_samples_' + id).value : null;
            const num_tracks = document.getElementById('num_tracks_' + id) ? document.getElementById('num_tracks_' + id).value : null;

            const price = parseFloat(document.getElementById('price_' + id).value);
            const listnprice = document.getElementById('listnprice_' + id).value;
            const dateprice = document.getElementById('dateprice_' + id).value;
            const previewInput = document.getElementById('preview_' + id);
            const fileInput = document.getElementById('file_' + id);

            // Esegui i controlli necessari sui dati
            if (!title || title.trim() === "") {
                alertB("Insert a title.");
                document.getElementById('title_' + id).classList.add('is-invalid');
                hideSpinner();
                return;
            }

            if (!genre || genre.trim() === "") {
                alertB("Insert a genre or plugin type.");
                document.getElementById('genre_' + id).classList.add('is-invalid');
                hideSpinner();
                return;
            }

            if (isNaN(price)) {
                alertB("Insert a price.");
                document.getElementById('price_' + id).classList.add('is-invalid');
                hideSpinner();
                return;
            }

            if (type_id == 1 && bpm) {
                if (bpm < 60 || bpm > 250) {
                    alertB("Bpm must be between 60 and 250.");
                    document.getElementById('bpm_' + id).classList.add('is-invalid');
                    hideSpinner();
                    return;
                }
            }

            // Crea FormData e aggiungi i dati
            const formData = new FormData();
            formData.append('id', id);
            formData.append('title', title);
            formData.append('type_id', type_id);
            formData.append('descr', descr);
            formData.append('bpm', bpm);
            formData.append('tonality', tonality);
            formData.append('genre', genre);
            formData.append('num_sample', num_sample);
            formData.append('num_tracks', num_tracks);
            formData.append('price', price);
            formData.append('listnprice', listnprice);
            formData.append('dateprice', dateprice);
            formData.append('action', action);

            // Aggiungi il file a FormData, se presente
            if (action === 'insert') {
                if (fileInput && fileInput.files.length > 0) {
                    formData.append('productpath', fileInput.files[0]);
                } else {
                    alertB("Please select a file for the product.");
                    document.getElementById('file_' + id).classList.add('is-invalid');
                    hideSpinner();
                    return;
                }
                if (previewInput && previewInput.files.length > 0) {
                    formData.append('audiopath', previewInput.files[0]);

                } else {
                    alertB("Please select an audio file for the preview.");
                    document.getElementById('preview_' + id).classList.add('is-invalid');
                    hideSpinner();
                    return;
                }

            } else if (action === 'update') {
                if (fileInput && fileInput.files.length > 0) {
                    formData.append('productpath', fileInput.files[0]);
                }
                if (previewInput && previewInput.files.length > 0) {
                    formData.append('audiopath', previewInput.files[0]);

                }
            }

            // Invia la richiesta con fetch
            fetch('../BACKEND/opProduct.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response:", data);
                    if (data.success) {
                        hideSpinner();
                        sessionStorage.setItem('alertMessage', 'Operation Completed');
                        sessionStorage.setItem('alertColor', 'success');
                        location.reload();
                    } else {
                        hideSpinner();
                        alertB("Errore: " + data.message, "danger");
                    }
                })
                .catch(error => {
                    hideSpinner();
                    console.error('Errore nel fetch:', error);
                    alertB("Errore nella risposta del server: " + error.message);
                });
        }
    </script>


    <div class="d-flex" style="height: 100px;">
        <div class="vr"></div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>