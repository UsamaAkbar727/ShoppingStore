<?php

setcookie("email", "", - 1 ,"/");
setcookie("token", "", - 1 ,"/");
setcookie("user_id", "",  - 1, "/");
 
header("location: login.php");
?>