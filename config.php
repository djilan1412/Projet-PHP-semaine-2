<?php
try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=airbnb;charset=utf8',
        'root',
        ''
    );
} catch (PDOException $e){
    die($e->getMessage());
}
$query = $dbh->prepare("SELECT * FROM listings");
$query->execute();
$data = $query->fetchAll();