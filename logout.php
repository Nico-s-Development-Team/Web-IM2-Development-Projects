<?php
session_start();
session_unset(); 
session_destroy(); // Destroy session
header("Location: home.html"); // goes to landing page
exit();