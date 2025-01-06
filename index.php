<?php
include './COMMON/utility.php';

// Inizializza la sessione
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>SLUKE</title>
    <meta charset="utf-8">
    <link rel="icon" type="image" href="./IMG/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./CSS/style.css">
    <link rel="stylesheet" href="./CSS/extra.css">
</head>

<body class="bg-dark text-light mt-4 ">
    <!-- Spinner Overlay -->
    <div id="spinner-overlay" class="d-flex d-none justify-content-center align-items-center position-fixed w-100 h-100 bg-dark bg-opacity-75" style="top: 0; left: 0;z-index: 2000;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <nav id="navbar" class="navbar navbar-expand-lg navbar-primary bg-light fixed-top">
        <div class="container-fluid justify-content-center">
            <a class="navbar-brand" href="#home">
                <img src="./IMG/LOGO_NEW-crop.png" alt="" height="20">
            </a>
            <button class="navbar-toggler bg-light border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse col-lg-10" id="navbarSupportedContent">
                <ul class="nav nav-pills text-center me-auto flex-column flex-lg-row">
                    <li class="nav-item mt-2 mt-md-0">
                        <a class="nav-link active text-primary" aria-current="page" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="#aboutus">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="#portfolio">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="./FRONTEND/products.php">Products</a>
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
                                <li class="nav-item ms-2 mb-2"> <a class="nav-link text-primary" href="./FRONTEND/productdashboard.php"> Product Dashboard
                                </a>
                            </li>
                                <li class="nav-item ms-2"> <a class="nav-link text-primary" href="./FRONTEND/admindashboard.php"> Admin Dashboard
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
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-primary" href="./FRONTEND/fdl.php">Free Downloads!</a>
                                    </li>
                                    </li>
                                    <li class="nav-item ms-2 mb-2"><a class="nav-link text-primary" href="./FRONTEND/orders.php">Your orders</a>
                                    </li>
                                    <li class="nav-item ms-2"><a class="nav-link text-primary" href="./BACKEND/logout.php">Log out</a>
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
                            
                                <li class="nav-item"> <a class="nav-link text-primary" href="./FRONTEND/login.php">
                                        Log in
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link text-primary" href="./FRONTEND/register.php">Register</a>
                                </li>
                                </ul>
                                </div>';
                            break;
                    }

                    ?>

    </nav>
    <?php
    if (isset($_SESSION['username'])) {

        echo '<div class="offcanvas offcanvas-end " data-bs-scroll="true" tabindex="-1" id="cart" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Shopping Cart <i class="bi bi-cart-dash"></i></h5>
            <button type="button" class="btn-close" style="color: var(--bs-light);" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            ';
        displayCart();
        echo '
           
        </div>
    </div>';
    }

    ?>
    <div data-bs-spy="scroll" data-bs-target="#navbar">




        <section id="home" class="p-0">
            <div class="container-fluid">

                <!-- Carousel -->

                <div id="homeCarousel" class="carousel slide p-0" data-bs-ride="carousel" data-bs-interval="5000">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Music Slide"></button>
                        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Products Slide"></button>
                        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Newsletter Slide"></button>
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner">
                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                            <img src="./IMG/pexels-ann-h-45017-2573957.jpg" class="d-block w-100 img-fluid" alt="Music Production">
                            <div class="carousel-caption  d-block">
                                <h2 data-aos="fade-down" data-aos-duration="1000">Discover Our Music</h2>
                                <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">Explore our latest productions and remixes.</p>
                                <a href="#portfolio" class="btn btn-primary btn-lg" data-aos="zoom-in">Learn More</a>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="carousel-item">
                            <img src="./IMG/pexels-david-bartus-43782-690779.jpg" class="d-block w-100 img-fluid" alt="New Products">
                            <div class="carousel-caption d-block">
                                <h2 data-aos="fade-down">New Arrivals</h2>
                                <p data-aos="fade-up">Discover our latest exclusive products.</p>
                                <a href="./FRONTEND/products.php" class="btn btn-primary btn-lg" data-aos="zoom-in">Shop Now</a>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="carousel-item">
                            <img src="./IMG/pexels-dmitry-demidov-515774-3783471.jpg" class="d-block w-100 img-fluid" alt="Newsletter Sign Up">
                            <div class="carousel-caption d-block">
                                <h2 data-aos="fade-down">Subscribe to Newsletter</h2>
                                <p data-aos="fade-up">Stay updated with SLUKE's latest news.</p>
                                <a href="#newsletter" class="btn btn-primary btn-lg" data-aos="zoom-in">Subscribe</a>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            </div>


        </section>

        <!-- Onde come overlay -->


        <section id="aboutus" class=" bg-black text-light ">
            <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 20" preserveAspectRatio="none">
                <defs>
                    <path id="wave-path" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 58-18 88-18 58 18 88 18 v44h-552z"></path>
                </defs>
                <g class="wave1">
                    <use xlink:href="#wave-path" x="50" y="3"></use>
                    <use xlink:href="#wave-path" x="450" y="3"></use>
                </g>
                <g class="wave2">
                    <use xlink:href="#wave-path" x="50" y="0"></use>
                    <use xlink:href="#wave-path" x="450" y="0"></use>
                </g>
                <g class="wave3">
                    <use xlink:href="#wave-path" x="50" y="6"></use>
                    <use xlink:href="#wave-path" x="450" y="6"></use>
                </g>
            </svg>

            <div class="container" data-aos="fade-up">
                <h1 class="text-center mb-4">About Us</h1>
                <div class="row">
                    <div class="col-md-6">
                        <img src="./IMG/studio.png" class="img-fluid rounded" alt="Team SLUKE">
                    </div>
                    <div class="col-md-6">
                        <h2>Our Story</h2>
                        <p>SLUKE was born from a passion for music and a desire to create something unique. Learn more about our mission and our team.</p>
                        <a href="#testimonials" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </section>






        <section id="portfolio" style="background: linear-gradient(to bottom, #000000,rgb(32, 33, 32));">
            <div class="container pt-5" data-aos="fade-up">
                <h1>Portfolio</h1>
                <div class="row mt-2">
                    <h3 class="my-4">Remixes</h3>
                    <iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1937779388&color=%23e9363d&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>
                    <div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><a href="https://soundcloud.com/slukemusicc" title="Sluke" target="_blank" style="color: #cccccc; text-decoration: none;">Sluke</a> · <a href="https://soundcloud.com/slukemusicc/1momento-sluke-remix" title="1MOMENTO (SLUKE REMIX) [FREE DL]" target="_blank" style="color: #cccccc; text-decoration: none;">1MOMENTO (SLUKE REMIX) [FREE DL]</a></div>
                </div>
                <div class="row my-4">
                    <div class="col-lg-6">
                        <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1922693060&color=%23e9363d&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
                        <div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><a href="https://soundcloud.com/slukemusicc" title="Sluke" target="_blank" style="color: #cccccc; text-decoration: none;">Sluke</a> · <a href="https://soundcloud.com/slukemusicc/italian-club-weapons-pack-vol1" title="ITALIAN CLUB WEAPONS REMIX PACK VOL.1 [FREE DL] (alcune tracce modificate per copy)" target="_blank" style="color: #cccccc; text-decoration: none;">ITALIAN CLUB WEAPONS REMIX PACK VOL.1 [FREE DL] (alcune tracce modificate per copy)</a></div>
                    </div>
                    <div class="col-lg-6">
                        <iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/1845590025&color=%23e9363d&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>
                        <div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><a href="https://soundcloud.com/slukemusicc" title="Sluke" target="_blank" style="color: #cccccc; text-decoration: none;">Sluke</a> · <a href="https://soundcloud.com/slukemusicc/sesso-e-baile" title="SESSO E SAMBA (SLUKE FLIP) [FREE DL] *filtrato per copyright*" target="_blank" style="color: #cccccc; text-decoration: none;">SESSO E SAMBA (SLUKE FLIP) [FREE DL] *filtrato per copyright*</a></div>
                    </div>
                </div>


                <div class="row mb-2 " data-aos="fade-up">
                    <h3 class="my-4">Songs and productions</h3>
                    <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/7ysK5BELfwpjusuUT1QYpw?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                </div>
                <div class="row my-4" data-aos="fade-up">
                    <div class="col-lg-6">
                        <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/1oSxktMK4ESQMlCm4FazEM?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                    </div>
                    <div class="col-lg-6">
                        <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/7ik8zyFSiEPjdl296iu9yD?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="text-bg-light py-5">
            <div class="container" data-aos="fade-left">
                <h1 class="text-center mb-4">What They Say About Us</h1>
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <blockquote class="blockquote text-center">
                                <p class="mb-0">"SLUKE has transformed my music into something extraordinary!"</p>
                                <footer class="blockquote-footer mt-1">Giovanni, Musician</footer>
                            </blockquote>
                        </div>
                        <div class="carousel-item">
                            <blockquote class="blockquote text-center">
                                <p class="mb-0">"The best music production service I have ever tried."</p>
                                <footer class="blockquote-footer mt-1">Maria, DJ</footer>
                            </blockquote>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section id="newsletter" class="bg-secondary py-5" data-aos="fade-up">
            <div class="container">
                <h1 class="text-center text-light mb-4">Subscribe to Our Newsletter</h1>
                <form class="row g-3 justify-content-center">
                    <div class="col-md-6">
                        <input type="email" class="form-control" placeholder="Coming soon!" required disabled>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" disabled>Subscribe</button>
                    </div>
                </form>
            </div>
        </section>
        <footer id="social" class="footer bg-secondary pb-5">
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
    </div>
    <script>
        // Mostra lo spinner al caricamento e lo nasconde alla fine
        function showSpinner() {
            document.getElementById('spinner-overlay').style.display = 'flex';
        }

        function hideSpinner() {
            document.getElementById('spinner-overlay').style.display = 'none';
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        AOS.init({

            duration: window.innerWidth <= 767 ? 300 : 1000, // Durata più breve su mobile
            once: true, // Animazione eseguita una sola volta

        });
    </script>

    <script src="../JS/alertB.js"></script>
</body>

</html>