<?php

include('config.php');

$lat = $_POST['lat'];
$long = $_POST['long'];

  $distance = 0.5;
  $query_nearby="SELECT * FROM (
  SELECT *, 
      (
          (
              (
                  acos(
                      sin(( {$lat} * pi() / 180))
                      *
                      sin(( lat * pi() / 180)) + cos(( {$lat} * pi() /180 ))
                      *
                      cos(( lat * pi() / 180)) * cos((( {$long} - lng) * pi()/180)))
              ) * 180/pi()
          ) * 60 * 1.1515 * 1.609344
      )
  as distance FROM place) place WHERE distance < $distance";

  $result = mysqli_query($con, $query_nearby);

  $dbdata = array();

  while ( $row = mysqli_fetch_assoc($result))  {
	$dbdata[]=$row;
  }

 echo json_encode($dbdata);
 
?>