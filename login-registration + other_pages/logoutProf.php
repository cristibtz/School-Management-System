<?php

session_start();
unset($_SESSION["user_prof"]);
header("Location: loginProf.php");

?>