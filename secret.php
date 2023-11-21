<?php
session_start(); 


if(!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: index.php"); 
    exit();
}


$Code = "php rocks";


if(isset($_GET['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page secrète</title>
</head>
<body>
    <h1>Page secrète</h1>

    <p>Code secret : <?php echo $Code; ?></p>

    <a href="secret.php?logout=true">Se déconnecter</a>
</body>
</html>
