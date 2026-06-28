<?php 
    session_start();
    session_unset(); 
    session_destroy(); 


    if (isset($_GET['origem']) && $_GET['origem'] == 'admin') {
    header("Location: loginAdmin.php");
} else {
    header("Location: index.php");
}

exit(); 
?>
