<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index2.css">
    <title>Modification de profil</title>
    <style>
         body {
            margin:0 auto ; /* Ajoute une marge supérieure au corps de la page */
        }
        .container {
            width: 400px;
            margin: 0 auto; /* Centre le conteneur horizontalement et ajoute une marge supérieure */
            padding: 20px;
            background-color: #f9f9f9;
            border: 2px solid #ccc; /* Cadre */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style des étiquettes */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Style des champs de saisie */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        /* Style du bouton */
        input[type="submit"] {
            width: 40%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Style pour les messages d'erreur ou de succès */
        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 3px;
        }

        .message.success {
            background-color: #4caf50;
            color: #fff;
        }

        .message.error {
            background-color: #f44336;
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
<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['mail'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Traitement de la modification de profil
if (isset($_POST['modify_profile'])) {
    try {
        // Connectez-vous à la base de données
        $pdo = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si les champs sont définis dans $_POST
        if (isset($_POST['new_alias'], $_POST['new_nom'], $_POST['new_prenom'], $_POST['new_email'], $_POST['new_mdp'])) {
            // Effectuez les opérations de mise à jour du profil ici
            $new_alias = $_POST['new_alias'];
            $new_nom = $_POST['new_nom'];
            $new_prenom = $_POST['new_prenom'];
            $new_email = $_POST['new_email'];
            $new_mdp = $_POST['new_mdp'];

            // Préparez la requête SQL pour mettre à jour le profil
            $stmt = $pdo->prepare("UPDATE user SET alias = :alias, nom = :nom, prenom = :prenom, mail = :mail, mdp = :mdp WHERE mail = :old_mail");
            $stmt->bindParam(':alias', $new_alias);
            $stmt->bindParam(':nom', $new_nom);
            $stmt->bindParam(':prenom', $new_prenom);
            $stmt->bindParam(':mail', $new_email);
            $stmt->bindParam(':mdp', $new_mdp);
            $stmt->bindParam(':old_mail', $_SESSION['mail']);

            // Exécutez la requête
            $stmt->execute();

            // Affichez un message de succès
            echo "Votre profil a été mis à jour avec succès.";

            // Incluez le script JavaScript pour afficher l'alerte et rediriger
            echo "<script>
                    if (confirm(\"Votre profil a été mis à jour avec succès. Cliquez sur OK pour continuer.\")) {
                        window.location.href = \"dashboard.php\";
                    } else {
                        // Si l'utilisateur annule, ne redirigez pas
                        // Vous pouvez ajouter du code supplémentaire ici si nécessaire
                    }
                  </script>";
        } else {
            // Affichez un message d'erreur si les champs ne sont pas définis
          // echo "Tous les champs doivent être remplis.";
        }
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<div class="container">
        <h2>Modification de profil</h2>
        <p>Veuillez remplir le formulaire ci-dessous pour effectuer des modifications sur votre profil :</p>
        <!-- Formulaire pour modifier le profil -->
        <form action="" method="POST">
            <label for="new_alias">Nouvel alias :</label>
            <input type="text" id="new_alias" name="new_alias" required><br>

            <label for="new_nom">Nouveau nom :</label>
            <input type="text" id="new_nom" name="new_nom" required><br>

            <label for="new_prenom">Nouveau prénom :</label>
            <input type="text" id="new_prenom" name="new_prenom" required><br>

            <label for="new_email">Nouvelle adresse e-mail :</label>
            <input type="email" id="new_email" name="new_email" required><br>

            <label for="new_mdp">Nouveau mot de passe :</label>
            <input type="password" id="new_mdp" name="new_mdp" required><br>

            <input type="submit" name="modify_profile" value="Modifier le profil">
        </form>
  </div>
</body>
</html>