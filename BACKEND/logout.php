<?php
session_start();
session_destroy();
echo "<script>sessionStorage.setItem('alertMessage', 'You have been logged out');
sessionStorage.setItem('alertColor', 'success');
window.location.href = '../index.php';
</script> ";


exit();
