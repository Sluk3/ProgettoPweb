<?php
session_start();
session_destroy();
header("Location: ../FRONTEND/index.php");
exit();
