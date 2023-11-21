<?php


session_start();

if (isset($_SESSION['user']) && $_SESSION['user'] === 'admin') {
    header("Location: secret.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "sign.php";
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=phpuserdatabase", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("SELECT * FROM phpuserdatabase WHERE username = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = 'admin';
            header("Location: secret.php");
            exit();
        } else {
            $errorMSG = "Identifiants incorrects";
        }
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
</head>

<body>
    <h1>Page d'accueil</h1>

    <?php
    if (isset($errorMSG)) {
        echo "<p style='color: red;'>$errorMSG</p>";
    }
    ?>

    <form method="post" action="connexion.php">
        <label for="username">Identifiant :</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Se connecter</button>
    </form>

    <form action="signIn.phtml">
        <button type="submit">Je ne possède pas de compte</button>
    </form>
</body>

</html>