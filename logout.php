<?php  
session_start();
session_unset();
session_destroy();

// redirect the browser back to the clean entrance login interface
header("Location: index.php");
exit();
?>