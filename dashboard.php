<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['mail'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Traitement de la déconnexion
if (isset($_POST['logout'])) {
    // Détruire la session et rediriger vers la page d'accueil
    session_destroy();
    header("Location: index.php");
    exit();
}



// Traitement de la suppression de profil
if (isset($_POST['delete_profile'])) {
    try {
        // Connectez-vous à la base de données
        $pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprimer le profil de l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM user WHERE mail = :mail");
        $stmt->bindParam(':mail', $_SESSION['mail']);
        $stmt->execute();

        // Déconnecter l'utilisateur
        session_unset();
        session_destroy();

        // Message d'alerte après la suppression
        echo "<script>alert('Votre profil a été supprimé avec succès.'); window.location.href = 'connexion.php';</script>";
        exit();
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données
        echo "Erreur : " . $e->getMessage();
    }
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['mail'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    // Afficher le message d'erreur
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    // Arrêter l'exécution du script
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index2.css">
    <title>Dashboard</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0 auto;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: 100px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
}

.dashboard-content p {
    text-align: center;
    margin-top: 20px;
}

.dashboard-actions {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.dashboard-actions form {
    margin: 0 10px;
}

.dashboard-actions input[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: 1px solid #007bff;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

.dashboard-actions input[type="submit"]:hover {
    background-color: #0056b3;
    color: #fff;
}

    </style>
</head>

<body>
<header class="tete">
    <nav>
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="connexion.php">Connexion</a></li>
          <li><a href="inscription.php">Inscription</a></li>
          <li><a href="dashboard.php">profil</a></li>
        </ul>
    </nav>
  </header>
    <div class="container">
        <h1>Bienvenue sur votre tableau de bord</h1>
        <div class="dashboard-content">
        <p>Vous êtes connecté en tant que <?php echo $_SESSION['mail']; ?>. Vous avez la possibilité d'apporter des modifications à votre profil ou de le supprimer.</p>
        </div>
        <div class="dashboard-actions">
            <!-- Bouton pour supprimer le profil -->
            <form action="" method="POST">
                <input type="submit" name="delete_profile" value="Supprimer le profil">
            </form>
    
            <!-- Bouton pour modifier le profil -->
            <form action="modify_profile.php" method="POST">
                <input type="submit" name="modify_profile" value="Modifier le profil">
            </form>
    
            <!-- Bouton pour se déconnecter -->
            <form action="" method="POST">
                <input type="submit" name="logout" value="Déconnexion">
            </form>
        </div>
    </div>
    
</body>
</html>
