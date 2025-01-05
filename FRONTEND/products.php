<?php
include '../COMMON/utility.php';
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
    cleanupUnusedFiles($conn);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['filtered'])) {
        $_SESSION['filterfields'] = $_POST;
        if ($_POST['filtered']) {
            $_SESSION['filter'] = " AND";
            if (!empty($_POST['genrefilter'])) {
                $_SESSION['filter'] .= " product.genre = '" . $_POST['genrefilter'] . "' AND ";
            }
            if ($_POST['typefilter'] != '*') {
                $_SESSION['typefilter'] = $_POST['typefilter'];
                if ($_POST['typefilter'] == 1) {
                    if ($_POST['tonalityfilter'] != '*') {
                        $_SESSION['filter'] .= " product.tonality = '" . $_POST['tonalityfilter'] . "' AND ";
                    }
                    $_SESSION['filter'] .= " product.bpm BETWEEN " . $_POST['bpm1'] . " AND " . $_POST['bpm2'] . " AND";
                } else {
                    unset($_SESSION['filterfields']['bpm1'], $_SESSION['filterfields']['bpm2'], $_SESSION['filterfields']['tonalityfilter']);
                }
            } elseif (isset($_SESSION['typefilter'])) {
                unset($_SESSION['typefilter']);
            }

            $_SESSION['filter'] .= " list_prices.price BETWEEN " . $_POST['price1'] . " AND " . $_POST['price2'];
        } else {
            unset($_SESSION['filter']);
            unset($_SESSION['typefilter']);
            unset($_SESSION['filterfields']);
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>SLUKE PRODUCTS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="../IMG/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/extra.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="../JS/alertB.js"></script>
    <script>
        $(function() {
            $("#price-range").slider({
                range: true,
                min: 0,
                max: 1000,
                values: [0, 1000],
                slide: function(event, ui) {
                    $("#pricelab").val("€" + ui.values[0] + " - €" + ui.values[1]);
                }
            });
            $("#pricelab").val("€" + $("#price-range").slider("values", 0) +
                " - €" + $("#price-range").slider("values", 1));
        });
        $(function() {
            $("#bpm-range").slider({
                range: true,
                min: 60,
                max: 200,
                values: [60, 200],
                slide: function(event, ui) {
                    $("#bpmlab").val(ui.values[0] + " BPM - " + ui.values[1] + " BPM");
                }
            });
            $("#bpmlab").val($("#bpm-range").slider("values", 0) + " BPM - " +
                $("#bpm-range").slider("values", 1) + " BPM");
        });
        /* var values = $(".selector").slider("option", "values"); */
    </script>
    <script>
        let activeAudio = null;
    </script>
</head>

<body class="bg-dark text-light  mt-5 pt-5">
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
                        <a class="nav-link active text-primary" href="">Products</a>
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
                            echo '<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle text-primary" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i>
                            ' . $_SESSION['username'] . '
                                        </a>
                                         <ul class="dropdown-menu">
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-primary" href="./fdl.php">Free Downloads!</a>
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
        <button type="button" class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="offcanvasScrolling">
            <i class="bi bi-funnel"></i> Filter
        </button>

        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="filter" aria-labelledby="offcanvasScrollingLabel">
            <div class="offcanvas-header">
                <h3 class="offcanvas-title" id="offcanvasScrollingLabel">Filters <i class="bi bi-funnel"></i></h3>
                <button type="button" class="btn-close" style="color: var(--bs-light);" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="formfilter" action="" method="post">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="type">Type</label>
                        <select name="typefilter" class="form-select" id="typeFilter">

                            <?php
                            echo '<option value="*" ' . (!isset($_SESSION['filterfields']) ? 'selected' : '') . '>All</option>';
                            $stmt = $conn->prepare('SELECT * FROM type');

                            if ($stmt->execute()) {
                                $result = $stmt->get_result(); // Ottieni il risultato della query

                                if ($result->num_rows > 0) {
                                    while ($row2 = $result->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($row2['id']) . '"';
                                        if (isset($_SESSION['filterfields']['typefilter']) && $_SESSION['filterfields']['typefilter'] == $row2['id']) {
                                            echo ' selected';
                                        }
                                        echo '>' . htmlspecialchars($row2['name']) . '</option>';
                                    }
                                } else {
                                    echo
                                    '<option>not working</option>';
                                }
                            } else {
                                echo
                                '<option>"Error in the connection phase</option>';
                            }
                            $stmt->close();

                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="input-group" id="genrefilter">
                            <span class="input-group-text">Genre/plugin type</span>
                            <input class="form-control" name="genrefilter" type="text" value="<?php echo (isset($_SESSION['filterfields']['genrefilter']) ? $_SESSION['filterfields']['genrefilter'] : "")  ?>" placeholder=" Any" aria-label="genre">
                        </div>
                    </div>
                    <div class="mb-3">
                        <p>
                            <label for="bpmlab">BPM range:</label>
                            <input type="text" id="bpmlab" readonly="" style="border:0; color:var(--bs-primary); font-weight:bold;">
                        </p>
                        <div id="bpm-range"></div>
                        <?php
                        // Using null coalescing operator for clean default values
                        $bpm1 = intval($_SESSION['filterfields']['bpm1'] ?? 60);
                        $bpm2 = intval($_SESSION['filterfields']['bpm2'] ?? 200);

                        echo '<script>
                            $(document).ready(function() {
                                if ($("#bpm-range").length) {
                                    $("#bpm-range").slider("values", [' . $bpm1 . ', ' . $bpm2 . ']);
                                    $("#bpmlab").val("' . $bpm1 . ' BPM - ' . $bpm2 . ' BPM");
                                }
                            });
                            </script>';
                        ?>

                    </div>
                    <div class="mb-3">

                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                            <span class="input-group-text">Key</span>
                            <select name="tonalityfilter" class="form-control" aria-label="mkey">
                                <?php
                                // Array of all tonalities
                                $tonalities = [
                                    'Any',
                                    'C',
                                    'C#m',
                                    'D',
                                    'Dm',
                                    'D#',
                                    'D#m',
                                    'E',
                                    'Em',
                                    'F',
                                    'Fm',
                                    'F#',
                                    'F#m',
                                    'G',
                                    'Gm',
                                    'G#',
                                    'G#m',
                                    'A',
                                    'Am',
                                    'A#',
                                    'A#m',
                                    'B',
                                    'Bm'
                                ];

                                // Complete check for session and filterfields
                                $selectedTonality = '';
                                if (
                                    isset($_SESSION['filterfields']) && is_array($_SESSION['filterfields']) && isset($_SESSION['filterfields']['tonalityfilter'])
                                ) {
                                    $selectedTonality = $_SESSION['filterfields']['tonalityfilter'] ?? 'Any';
                                }

                                // Generate options
                                foreach ($tonalities as $tonality) {
                                    $value = ($tonality === 'Any') ? '*' : $tonality;
                                    $selected = ($selectedTonality === $value) ? ' selected' : '';
                                    echo "<option value=\"$value\"$selected>$tonality</option>";
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <?php
                    // Using null coalescing operator for clean default values
                    $price1 = intval($_SESSION['filterfields']['price1'] ?? 0);
                    $price2 = intval($_SESSION['filterfields']['price2'] ?? 1000);

                    echo '<script>
                    $(document).ready(function() {
                        if ($("#price-range").length) {
                            $("#price-range").slider("values", [' . $price1 . ', ' . $price2 . ']);
                            $("#pricelab").val("€' . $price1 . ' - €' . $price2 . '");
                        }
                    });
                    </script>';
                    ?>
                    <div class="mb-3">
                        <p>
                            <label for="pricelab">Price range:</label>
                            <input type="text" id="pricelab" readonly="" style="border:0; color:var(--bs-primary); font-weight:bold;">
                        </p>
                        <div id="price-range"></div>
                    </div>
                    <input type="hidden" name="filtered" id="filtered" value="">
                    <input type="hidden" name="bpm1" id="bpm1" value="">
                    <input type="hidden" name="bpm2" id="bpm2" value="">
                    <input type="hidden" name="price1" id="price1" value="">
                    <input type="hidden" name="price2" id="price2" value="">

                    <div class="container d-grid mt-2 ">
                        <button type="button" id="filter" class="btn btn-primary my-3" onclick="setFilter('apply')"><i class="bi bi-funnel"></i> Apply filters</button>
                        <button type="button" id="resetfilter" class="btn btn-outline-primary" onclick="setFilter('reset')"><i class="bi bi-funnel"></i> Reset filters</button>
                    </div>
                </form>
            </div>

        </div>
        <?php //type

        $typequery = 'SELECT * FROM type';
        if (isset($_SESSION['typefilter'])) {
            $typequery .= ' WHERE id=' . $_SESSION['typefilter'] . ';';
        } else {
            $typequery .= ';';
        }
        $stmt1 = $conn->prepare($typequery);
        $empty = true;

        if ($stmt1->execute()) {
            $result1 = $stmt1->get_result(); // Ottieni il risultato della query

            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_array(MYSQLI_ASSOC)) {
                    $stmt2 = $conn->prepare('SELECT product.*, list_prices.price, list_prices.date, list_prices.list_id FROM product JOIN type ON product.type_id = type.id JOIN list_prices ON product.id = list_prices.prod_id WHERE list_prices.price > 0 AND type.name = \'' . $row1['name'] . '\' AND list_prices.date = (
    SELECT MAX(date) 
    FROM list_prices 
    WHERE list_prices.prod_id = product.id) ' .  (isset($_SESSION['filter']) ? $_SESSION['filter'] : '') . ';');
                    if ($stmt2->execute()) {
                        $result2 = $stmt2->get_result(); // Ottieni il risultato della query

                        if ($result2->num_rows > 0) {
                            echo ' <h2 class="my-4">' . $row1['name'] . '</h2>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5  g-4">';

                            while ($row2 = $result2->fetch_array(MYSQLI_ASSOC)) {
                                if ($row2['active']) {
                                    spawnProd1($row2, $row1['id'], $conn);
                                    $empty = false;
                                }
                            }
                            echo ' </div>';
                        }
                    }
                }
            } else {
                echo '<h5 class="my-3">type not found</h5>';
                return false;
            }

            echo ($empty ?  '<h2 class="my-3 text-center ">No content for this search!</h2>' : '');
        } else {
            echo "Error in the connection phase";
            return false;
        }

        ?>

    </div>




    <script>
        // Mostra lo spinner al caricamento e lo nasconde alla fine
        function showSpinner() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').style.display = 'none';
        }

        function setFilter(action) {
            if (action === 'apply') {

                const bpm = $("#bpm-range").slider("option", "values");
                const price = $("#price-range").slider("option", "values");
                document.getElementById('filtered').value = 1;
                document.getElementById('bpm1').value = bpm[0];
                document.getElementById('bpm2').value = bpm[1];
                document.getElementById('price1').value = price[0];
                document.getElementById('price2').value = price[1];
                const form = document.getElementById('formfilter');
                form.submit();
            } else if (action === 'reset') {
                const form = document.getElementById('formfilter');
                document.getElementsByName('filtered').value = 0;
                form.submit();
                form.reset();
            }

        }

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
        $(document).ready(function() {
            // Function to handle enabling/disabling of BPM and key filters
            function handleFilterFields() {
                const $typeSelect = $('#typeFilter');
                const $genrefilter = $('#genrefilter');
                const $bpmRange = $('#bpm-range');
                const $bpmLabel = $('#bpmlab');
                const $tonalitySelect = $('select[name="tonalityfilter"]');

                // Get the parent elements to disable the entire sections
                const $bpmSection = $bpmRange.closest('.mb-3');
                const $keySection = $tonalitySelect.closest('.mb-3');

                // Function to set disabled state
                function setDisabledState(isDisabled) {
                    // Disable/Enable BPM range slider
                    if ($bpmRange.hasClass('ui-slider')) {
                        $bpmRange.slider(isDisabled ? 'disable' : 'enable');
                    }
                    $bpmLabel.prop('disabled', isDisabled);

                    // Disable/Enable tonality select
                    $tonalitySelect.prop('disabled', isDisabled);

                    // Add/remove opacity to show disabled state visually
                    $bpmSection.css('opacity', isDisabled ? '0.5' : '1');
                    $keySection.css('opacity', isDisabled ? '0.5' : '1');

                    if (isDisabled) {
                        // Reset values when disabled
                        if ($bpmRange.hasClass('ui-slider')) {
                            $bpmRange.slider('values', [60, 200]);
                            $('#bpmlab').val('60 BPM - 200 BPM');
                        }
                        $tonalitySelect.val('*');
                    }
                }

                // Initial state and change handler
                function updateFieldsState() {
                    const isTypeOne = $typeSelect.val() === '1';
                    setDisabledState(!isTypeOne);
                }

                // Add change event listener to type select
                $typeSelect.on('change', updateFieldsState);

                // Set initial state
                updateFieldsState();
            }

            // Initialize filter handling
            handleFilterFields();
        });
    </script>




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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>