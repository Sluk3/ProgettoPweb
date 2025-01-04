<?php

function test_input($data)
{
    $data = trim($data);            // Rimuove gli spazi bianchi all'inizio e alla fine
    $data = stripslashes($data);    // Rimuove gli slash (\)
    $data = htmlspecialchars($data); // Converte caratteri speciali in HTML entities
    return $data;
}


function searchInDB($conn, $query, $searchS)
{
    // Prepara la query
    $stmt = $conn->prepare($query);

    $stmt->bind_param("s", $searchS);

    // Esegue la query
    $stmt->execute();

    // Ottiene il risultato
    $result = $stmt->get_result();

    // Controlla se ci sono risultati
    $found = ($result->num_rows > 0) ? 1 : 0;

    // Rilascia la query
    $stmt->close();

    return $found;
}


function validatePassword($password)
{
    // Definisci una regex per consentire lettere, numeri e i simboli desiderati (! e altri)
    $len = '/^.{8,40}$/';
    $upper = '/[A-Z]/';
    $lower = '/[a-z]/';
    $num = '/[0-9]/';
    $spec = '/[!"#$%&()*+?@^_~]/';
    echo preg_match($len, $password);
    echo preg_match($upper, $password);
    echo preg_match($lower, $password);
    echo preg_match($num, $password);
    echo preg_match($spec, $password);
    // Verifica se la password soddisfa i criteri
    if (preg_match($len, $password) && preg_match($upper, $password) && preg_match($lower, $password) && preg_match($num, $password) && preg_match($spec, $password)) {
        return true;  // Password valida
    } else {
        return false; // Password non valida (contiene caratteri non permessi)
    }
}
function login($conn, $user, $pwd)
{
    $stmt = $conn->prepare('SELECT * FROM user WHERE username=?');

    $stmt->bind_param("s", $user);
    // Esegue la query
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row['blocked']) {
                return 'blk';
            }
            if (!$row['authorized']) {
                return 'unauth';
            }
            if (password_verify($pwd, $row['pwd'])) {

                $_SESSION['username'] = $user;
                return 'ok'; // Login riuscito
            } else {
                return 'pwd';
            }
        } else {
            return 'user';
        }
    } else {

        return false;
    }
}
function dbConnect($dbusername = null, $dbpassword = null)
{
    $servername = "localhost";
    $dbname = "prweb1";
    // Se non vengono fornite credenziali, usa root
    if ($dbusername === null || $dbpassword === null) {
        $dbusername = 'root';
        $dbpassword = '';
    }

    try {
        // Connessione al DB
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Controllo della connessione
        if ($conn->connect_errno) {
            throw new Exception("Errore di connessione: " . $conn->connect_error, $conn->connect_errno);
        }

        return $conn;
    } catch (Exception $e) {
        // Log dettagliato dell'errore
        error_log(sprintf(
            "Fallimento connessione DB: Codice %d - Messaggio: %s - Utente: %s - Server: %s - Database: %s",
            $e->getCode(),
            $e->getMessage(),
            $dbusername,
            $servername,
            $dbname
        ));

        // Risposta generica per l'utente finale
        die("Impossibile connettersi al database. Si prega di riprovare più tardi.");
    }
}
function isAdmin($user)
{
    $conn = dbConnect();

    $stmt = $conn->prepare('SELECT * FROM user WHERE username=? AND admin=1');

    $stmt->bind_param("s", $user);

    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows == 1) {
            return true;
        } else {
            echo "You do not belong here!";
            return false;
        }
    } else {
        echo "Error in the connection phase";
        return false;
    }
}
function spawnProd1($row, $type, $conn)
{
    echo '<div class="col">
    <div class="card h-130 h-lg-150">
                    <div class="thumbnail position-relative">
                        <img src="../IMG/studio.png" class="card-img-top img-thumbnail" alt="...">
                            <div class="position-absolute top-50 start-50 translate-middle d-flex gap-3">
                            <button type="button" onclick="rewind' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-backward-fill fs-4"></i>
                            </button>
                            <button type="button" id="' . $row['id'] . 'playButton" onclick="play' . $row['id'] . '()" class="btn btn-primary border-dark rounded-circle ">
                                <i class="bi bi-play-fill fs-3" id="' . $row['id'] . 'playIcon"></i>
                            </button>
                            <button type="button" onclick="forward' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-forward-fill fs-4"></i>
                            </button>
                        </div> 
                    </div>
                    <script>
                        function play' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            const playButton = document.getElementById("' . $row['id'] . 'playButton");
                            const playIcon = document.getElementById("' . $row['id'] . 'playIcon");

                            if (activeAudio && activeAudio !== audio) {
                                activeAudio.pause();
                                 const previousPlayIcon = activeAudio.closest(\'.card\').querySelector(".bi-pause-fill");
                                if (previousPlayIcon) {
                                    previousPlayIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                }
                            }

                            if (audio.paused) {
                                audio.play();
                                playIcon.classList.replace("bi-play-fill", "bi-pause-fill");
                                activeAudio = audio;  // aggiorna laudio attivo
                            } else {
                                audio.pause();
                                playIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                activeAudio = null; // resetta 
                            }
                        }

                        function forward' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            audio.currentTime += 10;
                        }

                        function rewind' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] .
        'audioPlayer");
                            audio.currentTime -= 10;
                        }
                    </script>
                    <div class="card-body">
                        <h5 class="text-primary card-title ">' . $row['title'] . '</h5>';
    switch (true) {
        case $type == 1:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i>BPM:' . $row['bpm'] . '  <i class="bi bi-vinyl-fill"></i> Key: ' . $row['tonality'] . '</p>
            ';

            break;
        case $type == 2 || $type == 3:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Sample number:' . $row['num_sample'] . '</p>
            ';
            break;
        case $type == 4:
            echo ' <p class="card-text fw-light fs-6"><i class="bi bi-boombox-fill"></i>' . $row['genre'] . '</p>
                    
            ';
            break;
        case $type == 5:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Max stems number: ' . $row['num_tracks'] . '</p>
            ';
            break;
        case $type == 6:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Genre: ' . $row['genre'] . '</p>
            ';
            break;
        default:
            # code...
            break;
    }

    echo '              <audio id="' . $row['id'] . 'audioPlayer" class="w-100">
                            <source src="' . $row['audiopath'] . '" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <h5 class="fw-bold text-end">€' . $row['price'] . '</h5>
                        <div class="d-grid"><!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#' . $row['id'] . 'details">
                            <i class="bi bi-zoom-in"></i> Details</button>
                            <button type="button" id="addToCart1" onclick="addTocart(\'' . $row['id'] . '\',\'add\')" class="btn btn-outline-primary">Add to cart <i class="bi bi-cart"></i></button>
                        </div>
                        
                        
                        
                        
                        <!-- Modal -->
                        <div class="modal fade" id="' . $row['id'] . 'details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg ">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">#' . $row['id'] . '</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body" id="modaldetails">
                            
                                <div class="input-group mb-3 justify-content-around border border-3 border-primary rounded-3 p-3 bg-dark">
                                    <h4 class="text-light mb-3">' . $row['title'] . '</h4>
                                    <!-- Pulsanti e Timeline nel Modal -->
                                    <div class="input-group mb-3 justify-content-around ">
                                        <button type="button" onclick="rewindModal' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                            <i class="bi bi-skip-backward-fill fs-4"></i>
                                        </button>
                                        <button type="button" id="' . $row['id'] . 'playButtonModal" onclick="playModal' . $row['id'] . '()" class="btn btn-primary border-dark rounded-circle ">
                                            <i class="bi bi-play-fill fs-3" id="' . $row['id'] . 'playIconModal"></i>
                                        </button>
                                        <button type="button" onclick="forwardModal' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                            <i class="bi bi-skip-forward-fill fs-4"></i>
                                        </button>
                                    </div>

                                    <!-- Timeline della canzone -->
                                    <input type="range" id="' . $row['id'] . 'timelineModal" class="form-range" value="0" min="0" step="0.1">



                                <script>
                                        const audioModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'audioPlayer");
                                        const playButtonModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'playButtonModal");
                                        const playIconModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'playIconModal");
                                        const timelineModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'timelineModal");

                                        function playModal' . $row['id'] . '() {
                                            if (audioModal' . $row['id'] . '.paused) {
                                                audioModal' . $row['id'] . '.play();
                                                playIconModal' . $row['id'] . '.classList.replace("bi-play-fill", "bi-pause-fill");
                                            } else {
                                                audioModal' . $row['id'] . '.pause();
                                                playIconModal' . $row['id'] . '.classList.replace("bi-pause-fill", "bi-play-fill");
                                            }
                                        }

                                        function forwardModal' . $row['id'] . '() {
                                            audioModal' . $row['id'] . '.currentTime += 10;
                                        }

                                        function rewindModal' . $row['id'] . '() {
                                            audioModal' . $row['id'] . '.currentTime -= 10;
                                        }

                                        // Aggiorna la timeline quando laudio si riproduce
                                        audioModal' . $row['id'] . '.addEventListener("timeupdate", () => {
                                            timelineModal' . $row['id'] . '.value = audioModal' . $row['id'] . '.currentTime;
                                        });

                                        // Sincronizza la timeline con la durata dellaudio
                                        audioModal' . $row['id'] . '.addEventListener("loadedmetadata", () => {
                                            timelineModal' . $row['id'] . '.max = audioModal' . $row['id'] . '.duration;
                                        });

                                        // Aggiorna la posizione dellaudio quando si cambia la timeline manualmente
                                        timelineModal' . $row['id'] . '.addEventListener("input", () => {
                                            audioModal' . $row['id'] . '.currentTime = timelineModal' . $row['id'] . '.value;
                                        });
                                </script>

     
                                </div>
                                <div class="mb-3">
                                    <div class="row gy-3">
                                     <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="type">Type</label>
                                            <select id="type_' . $row['id'] . '" class="form-select" disabled id="typeselect">';
    $stmt = $conn->prepare('SELECT * FROM type');

    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows > 0) {
            while ($row2 = $result->fetch_array(MYSQLI_ASSOC)) {
                echo '<option value="' . htmlspecialchars($row2['id']) . '"';
                // Imposta selected se $type corrisponde al nome della voce corrente
                if ($row2['id'] == $type) {
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
    echo '                   </select>
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group"> 
                                        <span class="input-group-text">';
    if ($row['type_id'] != 4) {
        echo 'Genre';
    } else {
        echo 'Plugin type';
    }
    echo '</span>
                                        <input class="form-control" id="genre_' . $row['id'] . '" type="text" disabled value="' . $row['genre'] . '"  aria-label="genre">
                                        </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descr" class="form-label">Description</label>
                                    <textarea class="form-control" style="resize:none" disabled id="descr_' . $row['id'] . '" rows="3">' . $row['descr'] . '</textarea>
                                </div>';
    if ($row['type_id'] == 1) {
        echo '
                                <div class="mb-3">
                                    <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note"></i></span>
                                            <span class="input-group-text">BPM</span>
                                            <input type="number" id="bpm_' . $row['id'] . '" disabled class="form-control" value="' . $row['bpm'] . '" max="250" aria-label="bpm">
                                        </div>
                                        
                                    </div>
                                    <div class=" col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                            <span class="input-group-text">Key</span>
                                            <select id="tonality_' . $row['id'] . '" class="form-control" disabled aria-label="mkey">
                                                <option value="C"' . ($row['tonality'] == 'C' ? ' selected' : '') . '>C</option>
                                                <option value="Cm"' . ($row['tonality'] == 'Cm' ? ' selected' : '') . '>Cm</option>
                                                <option value="C#"' . ($row['tonality'] == 'C#' ? ' selected' : '') . '>C#</option>
                                                <option value="C#m"' . ($row['tonality'] == 'C#m' ? ' selected' : '') . '>C#m</option>
                                                <option value="D"' . ($row['tonality'] == 'D' ? ' selected' : '') . '>D</option>
                                                <option value="Dm"' . ($row['tonality'] == 'Dm' ? ' selected' : '') . '>Dm</option>
                                                <option value="D#"' . ($row['tonality'] == 'D#' ? ' selected' : '') . '>D#</option>
                                                <option value="D#m"' . ($row['tonality'] == 'D#m' ? ' selected' : '') . '>D#m</option>
                                                <option value="E"' . ($row['tonality'] == 'E' ? ' selected' : '') . '>E</option>
                                                <option value="Em"' . ($row['tonality'] == 'Em' ? ' selected' : '') . '>Em</option>
                                                <option value="F"' . ($row['tonality'] == 'F' ? ' selected' : '') . '>F</option>
                                                <option value="Fm"' . ($row['tonality'] == 'Fm' ? ' selected' : '') . '>Fm</option>
                                                <option value="F#"' . ($row['tonality'] == 'F#' ? ' selected' : '') . '>F#</option>
                                                <option value="F#m"' . ($row['tonality'] == 'F#m' ? ' selected' : '') . '>F#m</option>
                                                <option value="G"' . ($row['tonality'] == 'G' ? ' selected' : '') . '>G</option>
                                                <option value="Gm"' . ($row['tonality'] == 'Gm' ? ' selected' : '') . '>Gm</option>
                                                <option value="G#"' . ($row['tonality'] == 'G#' ? ' selected' : '') . '>G#</option>
                                                <option value="G#m"' . ($row['tonality'] == 'G#m' ? ' selected' : '') . '>G#m</option>
                                                <option value="A"' . ($row['tonality'] == 'A' ? ' selected' : '') . '>A</option>
                                                <option value="Am"' . ($row['tonality'] == 'Am' ? ' selected' : '') . '>Am</option>
                                                <option value="A#"' . ($row['tonality'] == 'A#' ? ' selected' : '') . '>A#</option>
                                                <option value="A#m"' . ($row['tonality'] == 'A#m' ? ' selected' : '') . '>A#m</option>
                                                <option value="B"' . ($row['tonality'] == 'B' ? ' selected' : '') . '>B</option>
                                                <option value="Bm"' . ($row['tonality'] == 'Bm' ? ' selected' : '') . '>Bm</option>
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } else if ($row['type_id'] == 2 || $row['type_id'] == 3) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3">
                                    
                                    <div class=" col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note-list"></i></span>
                                            <span class="input-group-text">Samples number</span>
                                            <input type="number" id="num_samples_' . $row['id'] . '" class="form-control" disabled value="' . $row['num_sample'] . '"  aria-label="sam">
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } elseif ($row['type_id'] == 5) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-cassette"></i></span>
                                            <span class="input-group-text">Max stems number</span>
                                            <input type="number" id="num_tracks_' . $row['id'] . '" class="form-control" disabled value="' . $row['num_tracks'] . '"  aria-label="trk">
                                    
                                        </div>
                                        </div>
                                    </div>
                                </div>';
    }

    echo '
                                        <div class="text-end ">
                                            <h2 class="tracking-widest"> € ' . $row['price'] . '</h2>
                                        </div>
                            <div class="modal-footer">
                                    <button type="button" id="addToCart2" onclick="addTocart(\'' . $row['id'] . '\',\'add\')" class="btn btn-outline-primary">Add to cart <i class="bi bi-cart"></i></button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
}
function spawnProd2($row, $type, $conn)
{
    echo '<div class="col">
    <div class="card h-100 xl-h-130">
                    <img src="../IMG/studio.png" class="card-img-top img-thumbnail" alt="...">
                    
                    <div class="card-body">
                        <h5 class="text-primary card-title ">' . $row['title'] . '</h5>';
    echo '<div class="d-flex justify-content-end"><span class="badge ' . ($row['active'] ? 'bg-success' : 'bg-danger') . '">' . ($row['active'] ? 'Active' : 'Hidden') . '</span></div>';
    switch (true) {
        case $type == 1:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i>BPM:' . $row['bpm'] . '  <i class="bi bi-key-fill"></i> Key: ' . $row['tonality'] . '</p>
            ';

            break;
        case $type == 2 || $type == 3:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Samples number:' . $row['num_sample'] . '</p>
            ';
            break;
        case $type == 4:
            echo ' <p class="card-text fw-light fs-6"><i class="bi bi-boombox-fill"></i> Type: ' . $row['genre'] . '</p>
                    
            ';
            break;
        case $type == 5:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Max stems number: ' . $row['num_tracks'] . '</p>
            ';
            break;
        case $type == 6:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Genre: ' . $row['genre'] . '</p>
            ';
            break;
        default:
            # code...
            break;
    }

    echo '
                       
                        <h5 class="fw-bold text-end">€' . $row['price'] . '</h5>
                        <div class="d-grid"><!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $row['id'] . 'details">
                        <i class="bi bi-zoom-in"></i> Modify
                        </button></div>
                    </div>

                        <!-- Modal -->
                        <div class="modal fade" id="' . $row['id'] . 'details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg ">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">#' . $row['id'] . '</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body" id="modalForm">
                            
                                <div class="mb-3"> 
                                    <label for="title" class="form-label">Title</label>
                                    <input class="form-control" id="title_' . $row['id'] . '" type="text" value="' . $row['title'] . '" aria-label="readonly input example">
                                </div>
                                <div class="mb-3">
                                    <div class="row gy-3">
                                     <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="type">Type</label>
                                            <select id="type_' . $row['id'] . '" class="form-select" id="typeselect">';
    $stmt = $conn->prepare('SELECT * FROM type');

    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows > 0) {
            while ($row2 = $result->fetch_array(MYSQLI_ASSOC)) {
                echo '<option value="' . htmlspecialchars($row2['id']) . '"';
                // Imposta selected se $type corrisponde al nome della voce corrente
                if ($row2['id'] == $type) {
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
    echo '                   </select>
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group"> 
                                        <span class="input-group-text">';
    if ($row['type_id'] != 4) {
        echo 'Genre';
    } else {
        echo 'Plugin type';
    }
    echo '</span>
                                        <input class="form-control" id="genre_' . $row['id'] . '" type="text" value="' . $row['genre'] . '"  aria-label="genre">
                                        </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descr" class="form-label">Description</label>
                                    <textarea class="form-control" style="resize:none" id="descr_' . $row['id'] . '" rows="3">' . $row['descr'] . '</textarea>
                                </div>';
    if ($row['type_id'] == 1) {
        echo '
                                <div class="mb-3">
                                    <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note"></i></span>
                                            <span class="input-group-text">BPM</span>
                                            <input type="number" id="bpm_' . $row['id'] . '" class="form-control" value="' . $row['bpm'] . '" max="250" aria-label="bpm">
                                        </div>
                                        
                                    </div>
                                    <div class=" col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                            <span class="input-group-text">Key</span>
                                            <select id="tonality_' . $row['id'] . '" class="form-control" aria-label="mkey">
                                                <option value="C"' . ($row['tonality'] == 'C' ? ' selected' : '') . '>C</option>
                                                <option value="Cm"' . ($row['tonality'] == 'Cm' ? ' selected' : '') . '>Cm</option>
                                                <option value="C#"' . ($row['tonality'] == 'C#' ? ' selected' : '') . '>C#</option>
                                                <option value="C#m"' . ($row['tonality'] == 'C#m' ? ' selected' : '') . '>C#m</option>
                                                <option value="D"' . ($row['tonality'] == 'D' ? ' selected' : '') . '>D</option>
                                                <option value="Dm"' . ($row['tonality'] == 'Dm' ? ' selected' : '') . '>Dm</option>
                                                <option value="D#"' . ($row['tonality'] == 'D#' ? ' selected' : '') . '>D#</option>
                                                <option value="D#m"' . ($row['tonality'] == 'D#m' ? ' selected' : '') . '>D#m</option>
                                                <option value="E"' . ($row['tonality'] == 'E' ? ' selected' : '') . '>E</option>
                                                <option value="Em"' . ($row['tonality'] == 'Em' ? ' selected' : '') . '>Em</option>
                                                <option value="F"' . ($row['tonality'] == 'F' ? ' selected' : '') . '>F</option>
                                                <option value="Fm"' . ($row['tonality'] == 'Fm' ? ' selected' : '') . '>Fm</option>
                                                <option value="F#"' . ($row['tonality'] == 'F#' ? ' selected' : '') . '>F#</option>
                                                <option value="F#m"' . ($row['tonality'] == 'F#m' ? ' selected' : '') . '>F#m</option>
                                                <option value="G"' . ($row['tonality'] == 'G' ? ' selected' : '') . '>G</option>
                                                <option value="Gm"' . ($row['tonality'] == 'Gm' ? ' selected' : '') . '>Gm</option>
                                                <option value="G#"' . ($row['tonality'] == 'G#' ? ' selected' : '') . '>G#</option>
                                                <option value="G#m"' . ($row['tonality'] == 'G#m' ? ' selected' : '') . '>G#m</option>
                                                <option value="A"' . ($row['tonality'] == 'A' ? ' selected' : '') . '>A</option>
                                                <option value="Am"' . ($row['tonality'] == 'Am' ? ' selected' : '') . '>Am</option>
                                                <option value="A#"' . ($row['tonality'] == 'A#' ? ' selected' : '') . '>A#</option>
                                                <option value="A#m"' . ($row['tonality'] == 'A#m' ? ' selected' : '') . '>A#m</option>
                                                <option value="B"' . ($row['tonality'] == 'B' ? ' selected' : '') . '>B</option>
                                                <option value="Bm"' . ($row['tonality'] == 'Bm' ? ' selected' : '') . '>Bm</option>
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } else if ($row['type_id'] == 2 || $row['type_id'] == 3) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3">
                                    
                                    <div class=" col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note-list"></i></span>
                                            <span class="input-group-text">Samples number</span>
                                            <input type="number" id="num_samples_' . $row['id'] . '" class="form-control" value="' . $row['num_sample'] . '"  aria-label="sam">
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } elseif ($row['type_id'] == 5) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-cassette"></i></span>
                                            <span class="input-group-text">Max stems number</span>
                                            <input type="number" id="num_tracks_' . $row['id'] . '" class="form-control" value="' . $row['num_tracks'] . '"  aria-label="trk">
                                    
                                        </div>
                                        </div>
                                    </div>
                                </div>';
    }
    $audiopath = explode("/", $row['audiopath']);
    $file = "No file selected!";
    if ($audiopath[0]) {
        $file = $audiopath[2];
    }
    echo '                      <div class="mb-3">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label for="filep" class="form-label">Upload a new preview file:</label>
                                            <div class="input-group mb-3">
                                            
                                                <input type="file" class="form-control form-control-lg" value="' . $row['audiopath'] . '" id="preview_' . $row['id'] . '" accept="audio/*" aria-describedby="file" aria-label="Upload">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="filep" class="form-label">Current preview: ' . $file . '</label>
                                         <audio controls src="' . $row['audiopath'] . '"></audio>
                                        </div>
                                    </div>
                                </div>
                                ';

    $productpath = explode("/", $row['productpath']);
    $file = "No file selected!";
    if ($productpath[0]) {
        $file = $productpath[2];
    }
    echo '                      <div class="mb-3">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label for="filep" class="form-label">Upload a new product file:</label>
                                            <div class="input-group mb-3">
                                            
                                                <input type="file" class="form-control form-control-lg" value="' . $row['productpath'] . '" id="file_' . $row['id'] . '"  aria-describedby="file" aria-label="Upload">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <label for="filep" class="form-label">Current product: ' . $file . '</label>
                                         <audio controls src="' . $row['productpath'] . '"></audio>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Price</span>
                                            <span class="input-group-text">€</span>
                                            <input type="number" id="price_' . $row['id'] . '" min="0" class="form-control" value="' . $row['price'] . '" step="0.01" aria-label="Dollar amount (with dot and two decimal places)">
                                        </div>
                                        
                                    </div>
                                    <div class=" col-md-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="listn">List Number</label>
                                                <select id="listnprice_' . $row['id'] . '" class="form-select">';
    $stmt = $conn->prepare('SELECT * FROM list_head');

    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows > 0) {
            while ($row2 = $result->fetch_array(MYSQLI_ASSOC)) {
                echo '<option value="' . htmlspecialchars($row2['id']) . '"';
                // Imposta selected se $row['list_id'] corrisponde a $row2['id']
                if ($row2['id'] == $row['list_id']) {
                    echo ' selected';
                }
                echo '>' . htmlspecialchars($row2['id']) . '</option>';
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
    echo '</select>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>
                                <div class="input-group mb-3">
                                            <label class="input-group-text" for="dateprice">Datetime start price</label>
                                            <input type="datetime-local" id="dateprice_' . $row['id'] . '" class="form-control" value="' . date("Y-m-d H:i:s") . '"  aria-label="datetime">
                                            <script>
                                             
                                        document.getElementById("dateprice_' . $row['id'] . '").addEventListener("input", function() {
                                            const selectedDate = new Date(this.value);
                                            const now = new Date();
                                            now.setHours(0, 0, 0, 0);
                                            
                                            if (selectedDate <= now) {
                                                alert("La data deve essere nel futuro.");
                                                this.value = \'' . date("Y-m-d H:i:s") . '\'; // Resetta il campo
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex justify-content-start">
                                    <button type="button" id="toggleButton" class="btn ' .  ($row['active'] ? 'btn-dark' : 'btn-success') . ' " onclick="opProductf(\'' . $row['id'] . '\', \'toggleActive\')">
                                        ' . ($row['active'] ? 'Hide product' : 'Show product') . '
                                    </button>
                                    <button type="button" id="toggleButton" class="btn btn-dark ms-2" onclick="opProductf(\'' . $row['id'] . '\', \'delete\')">
                                        Delete product
                                    </button>
                                </div>
                                <div class="d-flex ms-auto">
                                    <button type="button" id="submitButton" class="btn btn-primary" onclick="opProductf(\'' . $row['id'] . '\', \'update\')">Confirm changes</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
                  ';
}
function spawnProd3($row, $type, $conn)
{
    echo '<div class="col">
    <div class="card h-130 h-lg-150">
                    <div class="thumbnail position-relative">
                        <img src="../IMG/studio.png" class="card-img-top img-thumbnail" alt="...">
                            <div class="position-absolute top-50 start-50 translate-middle d-flex gap-3">
                            <button type="button" onclick="rewind' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-backward-fill fs-4"></i>
                            </button>
                            <button type="button" id="' . $row['id'] . 'playButton" onclick="play' . $row['id'] . '()" class="btn btn-primary border-dark rounded-circle ">
                                <i class="bi bi-play-fill fs-3" id="' . $row['id'] . 'playIcon"></i>
                            </button>
                            <button type="button" onclick="forward' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-forward-fill fs-4"></i>
                            </button>
                        </div> 
                    </div>
                    <script>
                        function play' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            const playButton = document.getElementById("' . $row['id'] . 'playButton");
                            const playIcon = document.getElementById("' . $row['id'] . 'playIcon");

                            if (activeAudio && activeAudio !== audio) {
                                activeAudio.pause();
                                 const previousPlayIcon = activeAudio.closest(\'.card\').querySelector(".bi-pause-fill");
                                if (previousPlayIcon) {
                                    previousPlayIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                }
                            }

                            if (audio.paused) {
                                audio.play();
                                playIcon.classList.replace("bi-play-fill", "bi-pause-fill");
                                activeAudio = audio;  // aggiorna laudio attivo
                            } else {
                                audio.pause();
                                playIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                activeAudio = null; // resetta 
                            }
                        }

                        function forward' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            audio.currentTime += 10;
                        }

                        function rewind' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] .
        'audioPlayer");
                            audio.currentTime -= 10;
                        }
                    </script>
                    <div class="card-body">
                        <h5 class="text-primary card-title ">' . $row['title'] . '</h5>';
    switch (true) {
        case $type == 1:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i>BPM:' . $row['bpm'] . '  <i class="bi bi-vinyl-fill"></i> Key: ' . $row['tonality'] . '</p>
            ';

            break;
        case $type == 2 || $type == 3:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Sample number:' . $row['num_sample'] . '</p>
            ';
            break;
        case $type == 4:
            echo ' <p class="card-text fw-light fs-6"><i class="bi bi-boombox-fill"></i>' . $row['genre'] . '</p>
                    
            ';
            break;
        case $type == 5:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Max stems number: ' . $row['num_tracks'] . '</p>
            ';
            break;
        case $type == 6:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Genre: ' . $row['genre'] . '</p>
            ';
            break;
        default:
            # code...
            break;
    }

    echo '              <audio id="' . $row['id'] . 'audioPlayer" class="w-100">
                            <source src="' . $row['audiopath'] . '" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <h5 class="fw-bold text-end">€' . $row['price'] . '</h5>
                        <div class="d-grid"><!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#' . $row['id'] . 'details">
                            <i class="bi bi-zoom-in"></i> Details</button>
                            <a type="button" download href="' . $row['productpath'] . '" class="btn btn-outline-primary">Download <i class="bi bi-download"></i></a>
                        </div>
                        
                        
                        
                        
                        <!-- Modal -->
                        <div class="modal fade" id="' . $row['id'] . 'details" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg ">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">#' . $row['id'] . '</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <div class="modal-body" id="modaldetails">
                            
                                <div class="input-group mb-3 justify-content-around border border-3 border-primary rounded-3 p-3 bg-dark">
                                    <h4 class="text-light mb-3">' . $row['title'] . '</h4>
                                    <!-- Pulsanti e Timeline nel Modal -->
                                    <div class="input-group mb-3 justify-content-around ">
                                        <button type="button" onclick="rewindModal' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                            <i class="bi bi-skip-backward-fill fs-4"></i>
                                        </button>
                                        <button type="button" id="' . $row['id'] . 'playButtonModal" onclick="playModal' . $row['id'] . '()" class="btn btn-primary border-dark rounded-circle ">
                                            <i class="bi bi-play-fill fs-3" id="' . $row['id'] . 'playIconModal"></i>
                                        </button>
                                        <button type="button" onclick="forwardModal' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                            <i class="bi bi-skip-forward-fill fs-4"></i>
                                        </button>
                                    </div>

                                    <!-- Timeline della canzone -->
                                    <input type="range" id="' . $row['id'] . 'timelineModal" class="form-range" value="0" min="0" step="0.1">



                                <script>
                                        const audioModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'audioPlayer");
                                        const playButtonModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'playButtonModal");
                                        const playIconModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'playIconModal");
                                        const timelineModal' . $row['id'] . ' = document.getElementById("' . $row['id'] . 'timelineModal");

                                        function playModal' . $row['id'] . '() {
                                            if (audioModal' . $row['id'] . '.paused) {
                                                audioModal' . $row['id'] . '.play();
                                                playIconModal' . $row['id'] . '.classList.replace("bi-play-fill", "bi-pause-fill");
                                            } else {
                                                audioModal' . $row['id'] . '.pause();
                                                playIconModal' . $row['id'] . '.classList.replace("bi-pause-fill", "bi-play-fill");
                                            }
                                        }

                                        function forwardModal' . $row['id'] . '() {
                                            audioModal' . $row['id'] . '.currentTime += 10;
                                        }

                                        function rewindModal' . $row['id'] . '() {
                                            audioModal' . $row['id'] . '.currentTime -= 10;
                                        }

                                        // Aggiorna la timeline quando laudio si riproduce
                                        audioModal' . $row['id'] . '.addEventListener("timeupdate", () => {
                                            timelineModal' . $row['id'] . '.value = audioModal' . $row['id'] . '.currentTime;
                                        });

                                        // Sincronizza la timeline con la durata dellaudio
                                        audioModal' . $row['id'] . '.addEventListener("loadedmetadata", () => {
                                            timelineModal' . $row['id'] . '.max = audioModal' . $row['id'] . '.duration;
                                        });

                                        // Aggiorna la posizione dellaudio quando si cambia la timeline manualmente
                                        timelineModal' . $row['id'] . '.addEventListener("input", () => {
                                            audioModal' . $row['id'] . '.currentTime = timelineModal' . $row['id'] . '.value;
                                        });
                                </script>

     
                                </div>
                                <div class="mb-3">
                                    <div class="row gy-3">
                                     <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="type">Type</label>
                                            <select id="type_' . $row['id'] . '" class="form-select" disabled id="typeselect">';
    $stmt = $conn->prepare('SELECT * FROM type');

    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Ottieni il risultato della query

        if ($result->num_rows > 0) {
            while ($row2 = $result->fetch_array(MYSQLI_ASSOC)) {
                echo '<option value="' . htmlspecialchars($row2['id']) . '"';
                // Imposta selected se $type corrisponde al nome della voce corrente
                if ($row2['id'] == $type) {
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
    echo '                   </select>
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group"> 
                                        <span class="input-group-text">';
    if ($row['type_id'] != 4) {
        echo 'Genre';
    } else {
        echo 'Plugin type';
    }
    echo '</span>
                                        <input class="form-control" id="genre_' . $row['id'] . '" type="text" disabled value="' . $row['genre'] . '"  aria-label="genre">
                                        </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descr" class="form-label">Description</label>
                                    <textarea class="form-control" style="resize:none" disabled id="descr_' . $row['id'] . '" rows="3">' . $row['descr'] . '</textarea>
                                </div>';
    if ($row['type_id'] == 1) {
        echo '
                                <div class="mb-3">
                                    <div class="row gy-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note"></i></span>
                                            <span class="input-group-text">BPM</span>
                                            <input type="number" id="bpm_' . $row['id'] . '" disabled class="form-control" value="' . $row['bpm'] . '" max="250" aria-label="bpm">
                                        </div>
                                        
                                    </div>
                                    <div class=" col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                            <span class="input-group-text">Key</span>
                                            <select id="tonality_' . $row['id'] . '" class="form-control" disabled aria-label="mkey">
                                                <option value="C"' . ($row['tonality'] == 'C' ? ' selected' : '') . '>C</option>
                                                <option value="Cm"' . ($row['tonality'] == 'Cm' ? ' selected' : '') . '>Cm</option>
                                                <option value="C#"' . ($row['tonality'] == 'C#' ? ' selected' : '') . '>C#</option>
                                                <option value="C#m"' . ($row['tonality'] == 'C#m' ? ' selected' : '') . '>C#m</option>
                                                <option value="D"' . ($row['tonality'] == 'D' ? ' selected' : '') . '>D</option>
                                                <option value="Dm"' . ($row['tonality'] == 'Dm' ? ' selected' : '') . '>Dm</option>
                                                <option value="D#"' . ($row['tonality'] == 'D#' ? ' selected' : '') . '>D#</option>
                                                <option value="D#m"' . ($row['tonality'] == 'D#m' ? ' selected' : '') . '>D#m</option>
                                                <option value="E"' . ($row['tonality'] == 'E' ? ' selected' : '') . '>E</option>
                                                <option value="Em"' . ($row['tonality'] == 'Em' ? ' selected' : '') . '>Em</option>
                                                <option value="F"' . ($row['tonality'] == 'F' ? ' selected' : '') . '>F</option>
                                                <option value="Fm"' . ($row['tonality'] == 'Fm' ? ' selected' : '') . '>Fm</option>
                                                <option value="F#"' . ($row['tonality'] == 'F#' ? ' selected' : '') . '>F#</option>
                                                <option value="F#m"' . ($row['tonality'] == 'F#m' ? ' selected' : '') . '>F#m</option>
                                                <option value="G"' . ($row['tonality'] == 'G' ? ' selected' : '') . '>G</option>
                                                <option value="Gm"' . ($row['tonality'] == 'Gm' ? ' selected' : '') . '>Gm</option>
                                                <option value="G#"' . ($row['tonality'] == 'G#' ? ' selected' : '') . '>G#</option>
                                                <option value="G#m"' . ($row['tonality'] == 'G#m' ? ' selected' : '') . '>G#m</option>
                                                <option value="A"' . ($row['tonality'] == 'A' ? ' selected' : '') . '>A</option>
                                                <option value="Am"' . ($row['tonality'] == 'Am' ? ' selected' : '') . '>Am</option>
                                                <option value="A#"' . ($row['tonality'] == 'A#' ? ' selected' : '') . '>A#</option>
                                                <option value="A#m"' . ($row['tonality'] == 'A#m' ? ' selected' : '') . '>A#m</option>
                                                <option value="B"' . ($row['tonality'] == 'B' ? ' selected' : '') . '>B</option>
                                                <option value="Bm"' . ($row['tonality'] == 'Bm' ? ' selected' : '') . '>Bm</option>
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } else if ($row['type_id'] == 2 || $row['type_id'] == 3) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3">
                                    
                                    <div class=" col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-music-note-list"></i></span>
                                            <span class="input-group-text">Samples number</span>
                                            <input type="number" id="num_samples_' . $row['id'] . '" class="form-control" disabled value="' . $row['num_sample'] . '"  aria-label="sam">
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>';
    } elseif ($row['type_id'] == 5) {
        echo ' <div class="mb-3">
                                    <div class="row gy-3 mb-3">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-cassette"></i></span>
                                            <span class="input-group-text">Max stems number</span>
                                            <input type="number" id="num_tracks_' . $row['id'] . '" class="form-control" disabled value="' . $row['num_tracks'] . '"  aria-label="trk">
                                    
                                        </div>
                                        </div>
                                    </div>
                                </div>';
    }

    echo '
                                        <div class="text-end ">
                                            <h2 class="tracking-widest"> € ' . $row['price'] . '</h2>
                                        </div>
                            <div class="modal-footer">
                                    <a type="button"  download href="' . $row['productpath'] . '"  class="btn btn-outline-primary">Download <i class="bi bi-download"></i></a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
}
function spawnProd4($row, $cur_price, $quantity)
{
    echo '<div class="col">
    <div class="card h-130 h-lg-150">
                    <div class="thumbnail position-relative">
                        <img src="../IMG/studio.png" class="card-img-top img-thumbnail" alt="...">
                            <div class="position-absolute top-50 start-50 translate-middle d-flex gap-3">
                            <button type="button" onclick="rewind' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-backward-fill fs-4"></i>
                            </button>
                            <button type="button" id="' . $row['id'] . 'playButton" onclick="play' . $row['id'] . '()" class="btn btn-primary border-dark rounded-circle ">
                                <i class="bi bi-play-fill fs-3" id="' . $row['id'] . 'playIcon"></i>
                            </button>
                            <button type="button" onclick="forward' . $row['id'] . '()" class="btn btn-primary border-primary rounded-circle ">
                                <i class="bi bi-skip-forward-fill fs-4"></i>
                            </button>
                        </div> 
                    </div>
                    <script>
                        function play' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            const playButton = document.getElementById("' . $row['id'] . 'playButton");
                            const playIcon = document.getElementById("' . $row['id'] . 'playIcon");

                            if (activeAudio && activeAudio !== audio) {
                                activeAudio.pause();
                                 const previousPlayIcon = activeAudio.closest(\'.card\').querySelector(".bi-pause-fill");
                                if (previousPlayIcon) {
                                    previousPlayIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                }
                            }

                            if (audio.paused) {
                                audio.play();
                                playIcon.classList.replace("bi-play-fill", "bi-pause-fill");
                                activeAudio = audio;  // aggiorna laudio attivo
                            } else {
                                audio.pause();
                                playIcon.classList.replace("bi-pause-fill", "bi-play-fill");
                                activeAudio = null; // resetta 
                            }
                        }

                        function forward' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] . 'audioPlayer");
                            audio.currentTime += 10;
                        }

                        function rewind' . $row['id'] . '() {
                            const audio = document.getElementById("' . $row['id'] .
        'audioPlayer");
                            audio.currentTime -= 10;
                        }
                    </script>
                    <div class="card-body">
                        <h5 class="text-primary card-title ">' . $row['title'] . '</h5>';

    switch (true) {
        case $row['type_id'] == 1:
            echo ' 
                    <p class="card-text fw-light fs-6">Product type: Beat/Instrumental</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i>BPM:' . $row['bpm'] . '  <i class="bi bi-vinyl-fill"></i> Key: ' . $row['tonality'] . '</p>
            ';

            break;
        case $row['type_id'] == 2:
            echo ' <p class="card-text fw-light fs-6">Product type: Drum Kit</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Sample number:' . $row['num_sample'] . '</p>
            ';
            break;
        case $row['type_id'] == 3:
            echo ' <p class="card-text fw-light fs-6">Product type: Sample Pack</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Sample number:' . $row['num_sample'] . '</p>
            ';
            break;
        case $row['type_id'] == 4:
            echo '  <p class="card-text fw-light fs-6">Product type: Plugin</p>
            <p class="card-text fw-light fs-6"><i class="bi bi-boombox-fill"></i>' . $row['genre'] . '</p>
                    
            ';
            break;
        case $row['type_id'] == 5:
            echo ' <p class="card-text fw-light fs-6">Product type: Mix&Master</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Max stems number: ' . $row['num_tracks'] . '</p>
            ';
            break;
        case $row['type_id'] == 6:
            echo ' <p class="card-text fw-light fs-6">Product type: Ghost production</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Genre: ' . $row['genre'] . '</p>
            ';
            break;
        default:
            # code...
            break;
    }

    echo '              <audio id="' . $row['id'] . 'audioPlayer" class="w-100">
                            <source src="' . $row['audiopath'] . '" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <h5 class="fw-bold text-end">€' . $cur_price . ' X ' . $quantity . '</h5>
                        <div class="d-grid">
                            <button type="button" id="addToCart1" onclick="addTocart(\'' . $row['id'] . '\',\'add\')" class="btn btn-outline-primary mb-2">Buy again! <i class="bi bi-cart"></i></button>
                            <a type="button"  download href="' . $row['productpath'] . '"  class="btn btn-primary">Download <i class="bi bi-download"></i></a>
                        </div>
                        
                
            
        </div>
    </div>
</div>';
}
function loadCart($conn, $action)
{
    if ((isset($_SESSION['cart']) && $action == 'refresh') || (!isset($_SESSION['cart']) && $action == 'load')) {
        $stmt = $conn->prepare("SELECT id, confirmed 
FROM order_head 
WHERE username = '{$_SESSION['username']}'
ORDER BY date DESC 
LIMIT 1;
");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $result = $result->fetch_array(MYSQLI_ASSOC);
            $orId = $result['id'];
            $_SESSION['orid'] = $orId;
            $conf = $result['confirmed'];

            $stmt->close();

            if (!$conf) {
                $stmt = $conn->prepare("SELECT p.*, lp.price, d.quantity, h.id AS order_id, t.name
    FROM order_detail AS d
    JOIN order_head AS h ON d.order_id = h.id 
    JOIN product AS p ON d.prod_id=p.id
    JOIN list_prices AS lp ON lp.prod_id = p.id
    JOIN type as t on p.type_id=t.id
    WHERE d.order_id = '{$orId}'AND lp.date = (
        SELECT MAX(date)
        FROM list_prices AS sub_lp
        WHERE sub_lp.prod_id = lp.prod_id
    );
    ");
                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    // Salva il risultato come array in sessione
                    if ($result->num_rows > 0) {
                        $_SESSION['cart'] = $result->fetch_all(MYSQLI_ASSOC);
                    }
                }
                $stmt->close();
            }
        }
    } else {
        error_log('cart already loaded');
    }
}
function displayCart()
{

    $totprice = 0;
    if (isset($_SESSION['cart'])) {
        if (count($_SESSION['cart']) > 0) {
            echo '<div class="container">';
            foreach ($_SESSION['cart'] as $row) {
                cartItem($row);
                $totprice += $row['price'] * $row['quantity'];
            }
            echo '
                            <hr>
                            <div class="container d-flex justify-content-end">
                                <h2>Total price: €' . number_format($totprice, 2) . '</h2>
            
                            </div>
                            <div class="container d-grid mt-2">
                            <button type="button" id="pay" class="btn btn-primary" onclick="addTocart(\'\',\'checkout\')">Place Order</button>
                            </div>
                        </div>';
        } else {
            echo '<div class="container text-center">
            <h1 class="text-center text-primary">Your cart is empty!</h1>
            <h3 class="text-center">Let\'s go to the <a class="text-secondary" href="./FRONTEND/products.php">shop</a> to add some products!</h3>
        </div>';
        }
    } else {
        echo '<div class="container text-center">
            <h1 class="text-center text-primary">Your cart is empty!</h1>
            <h3 class="text-center">Let\'s go to the <a class="text-secondary" href="./FRONTEND/products.php">shop</a> to add some products!</h3>
        </div>';
    }
}
function cartItem($row)
{
    echo '
                <div class="card mb-3" style="max-height: 130px, overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-4 d-flex  align-items-start">
                            <img src="../IMG/studio.jpg" class="m-2 img-fluid rounded-start " alt="...">
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <div class=" d-flex justify-content-between text-truncate">
                                <h5 class="card-title text-truncate">' . $row['title'] . '</h5>
                                <a class="btn icon-link" onclick="addTocart(\'' . $row['id'] . '\',\'delete\')"><i class="bi bi-trash3"></i>
                                </a> 
                                </div>
                                <div class="row d-flex" >
                                <div class="col card-text fw-light fs-6 text-truncate">
                                <p class="">' . $row['name'] . '</p>
                                ';

    /* switch (true) {
        case $row['type_id'] == 1:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '<br>
                    <i class="bi bi-music-note"></i>BPM:' . $row['bpm'] . '  <i class="bi bi-key-fill"></i> Key: ' . $row['tonality'] . '<br>
            ';

            break;
        case $row['type_id'] == 2 || $row['type_id'] == 3:
            echo ' <p class="card-text fw-light fs-6">' . $row['genre'] . '</p>
                    <p class="card-text fw-light fs-6"><i class="bi bi-music-note"></i> Samples number:' . $row['num_sample'] . '<br>
            ';
            break;
        case $row['type_id'] == 4:
            echo ' <p class="card-text fw-light fs-6"><i class="bi bi-boombox-fill"></i> Type: ' . $row['genre'] . '<br>
                    
            ';
            break;
        case $row['type_id'] == 5:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Max stems number: ' . $row['num_tracks'] . '<br>
            ';
            break;
        case $row['type_id'] == 6:
            echo ' 
                    <p class="card-text fw-light fs-6"><i class="bi bi-cassette"></i> Tracks: ' . $row['num_tracks'] . '<br>
            ';
            break;
        default:
            # code...
            break;
    } */


    echo '
                                
                                    <div class="input-group" style="max-width: 200px;">
                                        <button class="btn btn-outline-primary" type="button" ' . ($row['quantity'] <= 1 ?  'disabled' : '') . ' onclick="addTocart(\'' . $row['id'] . '\',\'decrease\')">-</button>
                                        <input type="number" class="form-control text-center" disabled value="' . $row['quantity'] . '" min="1" id="quantity">
                                        <button class="btn btn-outline-primary" type="button" onclick="addTocart(\'' . $row['id'] . '\',\'add\')">+</button>
                                    </div>

                                
                                </div>
                                <div class="col-auto d-flex justify-content-end align-items-end">
                                <p class="card-text ">€' . number_format($row['price'], 2) . '</p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
}
// Funzione helper per il debug
function debug($var)
{
    echo '<pre style="background-color: #f5f5f5; padding: 15px; margin: 10px; border: 1px solid #ddd;">';
    var_dump($var);
    echo '</pre>';
}
// Funzione per ottenere i file referenziati nel database
function getReferencedFiles($conn, $table, $column)
{
    $referencedFiles = [];
    $query = "SELECT $column FROM $table";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $referencedFiles[] = $row[$column];
        }
    }

    return $referencedFiles;
}

// Funzione per cancellare i file inutilizzati in una cartella
function deleteUnusedFiles($directory, $referencedFiles)
{
    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $directory .  $file;

            // Se il file non è referenziato nel database, cancellalo
            if (!in_array($filePath, $referencedFiles)) {
                if (is_file($filePath)) {
                    error_log('Deleting: ' . $filePath);
                    if (unlink($filePath)) {
                        echo "<script>console.log('Deleted: $filePath<br>');</script>";
                    } else {
                        echo "<script>console.log('Error deleting: $filePath<br>');</script>";
                    }
                }
            }
        }
    }
}
function cleanupUnusedFiles($conn)
{
    // Ottieni i file referenziati nel database
    $audioFiles = getReferencedFiles($conn, 'product', 'audiopath');
    $productFiles = getReferencedFiles($conn, 'product', 'productpath');
    // Cancella i file inutilizzati nelle cartelle audio e products
    deleteUnusedFiles('../AUDIO/', $audioFiles); // Sostituisci con il percorso corretto
    deleteUnusedFiles('../PRODUCTS/', $productFiles); // Sostituisci con il percorso corretto 
}
