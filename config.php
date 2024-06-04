<?php
define('DB_SERVER', 'tuananhdb.cvufoposxbbs.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'Nguyentuananh03');
define('DB_NAME', 'clouddb');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
