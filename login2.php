<?php
session_start();

if (isset($_POST['submit'])) {
    $mail = $_POST['mail'];
    $pass = $_POST['mdp'];

    try {
        $db = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM user WHERE mail = :mail");
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe dans la base de données
        if ($user) {
            // Vérifier si le mot de passe correspond
            if ($pass === $user['mdp']) {
                // Authentification réussie, définir une variable de session pour marquer l'utilisateur comme connecté
                $_SESSION['mail'] = $mail;
                // Rediriger vers la page dashboard.php
                header("Location: dashboard.php");
                exit();
            } else {
                // Informer l'utilisateur que le mot de passe est incorrect
                echo '<script>alert("Mot de passe incorrect. Veuillez réessayer.");</script>';
            }
        } else {
            // Informer l'utilisateur que l'email n'est pas trouvé dans la base de données
            echo '<script>alert("Adresse email incorrecte. Veuillez réessayer.");</script>';
        }
    } catch (PDOException $e) {
        // Erreur de connexion à la base de données
        $error = "Erreur: " . $e->getMessage();
    }

    // Affichage des erreurs le cas échéant
    if (isset($error)) {
        echo $error;
    }
}
?>
