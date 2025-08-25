<?php
function db_connect() {
    static $connection;

    if(!isset($connection)) {
    $connection = mysqli_connect("localhost:3307", "javier", "MyN3wP4ssw0rd", "green_valley_db");
        }
    if($connection === false) {
    return mysqli_connect_error(); 
    }
    return $connection;
    echo("hello2");
}

function db_query($query) {
    $connection = db_connect();
    $result = mysqli_query($connection,$query);
    return $result;
    echo("hello1");

}

db_query("SELECT * FROM green_valley_db.cliente");
?>