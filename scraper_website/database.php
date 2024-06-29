<?php

    $db_server = "localhost";
    $db_user = "waffle";
    $db_pass = "Wafflemaker6*";
    $db_name = "flightdb";
    $conn = "";

    try {

    
    $conn = mysqli_connect($db_server,
                           $db_user, 
                           $db_pass, 
                           $db_name);

    }
    catch(mysqli_sql_exception){
        echo "error, could not connect ! <br>";
    }

    
?>
