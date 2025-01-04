<?php
session_start();
session_destroy();
echo "<script>sessionStorage.setItem('alertMessage', 'You have been logged out');
sessionStorage.setItem('alertColor', 'success');</script>";


header("Location: ../index.php");
exit();
