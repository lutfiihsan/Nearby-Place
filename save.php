<?php
    // Report all PHP errors (see changelog)
    error_reporting(E_ALL);

    // Report all PHP errors
    error_reporting(-1);

    // Same as error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);

    include('config.php');
    // if(isset($_POST['save'])){
        $lat = $_POST['lat'];
        $lng = $_POST['long'];
        $poi = $_POST['poi'];

        $sql = "INSERT INTO place (id, place, lat, lng) VALUES ('','$poi', '$lat', '$lng')";
        
        if (mysqli_query($con, $sql)) {
            header("location: nearby.php?save=success");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        mysqli_close($con);
?>