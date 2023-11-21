<?php
if (isset($_POST["NewPassword"]) && isset($_POST["NewUsername"]) && !empty($_POST["NewUsername"]) && !empty($_POST["NewPassword"])) {
    $regex = "/^(?=.*[a-z])\S{8,16}$/";
    $createdPassword = $_POST["NewPassword"];
    $createdUsername = $_POST["NewUsername"];

    if (preg_match($regex, $createdPassword)) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=phpuserdatabase", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $checkQuery = $pdo->prepare("SELECT * FROM phpuserdatabase WHERE username = ?");
            $checkQuery->bindParam(1, $createdUsername, PDO::PARAM_STR);
            $checkQuery->execute();
            $existingUser = $checkQuery->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                die("Erreur d'inscription: L'username existe déjà.");
            }


            $hashedPassword = password_hash($createdPassword, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO phpuserdatabase (username, password) VALUES (?, ?)");
            $stmt->bindParam(1, $createdUsername, PDO::PARAM_STR);
            $stmt->bindParam(2, $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: connexion.php");
        } catch (PDOException $e) {
            die("Erreur d'inscription: " . $e->getMessage());
        }
    }
} else {
?>
    <p>Votre mot de passe doit contenir:
    <ul>
        <li>au moins UNE majuscule</li>
        <li>au moins UN chiffre</li>
        <li>au moins UN caractère spécial</li>
        <li>entre 8 et 16 caractères</li>
    </ul>
    </p>
<?php
}; ?>