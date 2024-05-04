<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="css/inscription.css">  
    <link rel="stylesheet" href="css/index2.css">  
    <title>Inscription</title>
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

    <h1>Inscription</h1>
    <div class="formule">
        <form action="inscription.php" method="post">

            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" id="prenom" required><br>

            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" required><br>

            <label for="alias">Alias :</label>
            <input type="text" id="alias" name="alias" required><br><br>

            <label for="mail">Adresse e-mail :</label>
            <input type="email" id="mail" name="mail" required><br><br>

            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required><br><br>

            <input type="submit" name="inscription" value="S'inscrire">
        </form>
    </div>

    <div id="alert" class="alert"></div>

    <?php
    session_start();  // Démarrez la session

    try {
        $mysqlConnection = new PDO("mysql:host=localhost;dbname=projet;charset=utf8", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        // En cas d'erreur, affichez un message et arrêtez l'exécution
        die('Erreur : ' . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
        // Récupération des données du formulaire d'inscription
        $alias = $_POST['alias'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['mail'];
        $mdp = $_POST['mdp']; // Utilisez $mdp au lieu de $password

        // Utilisez des requêtes préparées pour éviter les injections SQL
        $query = $mysqlConnection->prepare("SELECT * FROM user WHERE alias = :alias OR mail = :mail");
        $query->bindParam(':alias', $alias);
        $query->bindParam(':mail', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Afficher l'alerte d'échec d'inscription
            echo "<script>document.getElementById('alert').innerHTML = 'L\'alias ou l\'adresse e-mail est déjà utilisé. Veuillez en choisir un autre.'; document.getElementById('alert').style.backgroundColor = '#f44336';</script>";
        } else {
            // Insertion du nouvel utilisateur dans la base de données
            $insert_query = $mysqlConnection->prepare("INSERT INTO user (alias, nom, prenom, mail, mdp) VALUES (:alias, :nom, :prenom, :mail, :mdp)");
            $insert_query->bindParam(':alias', $alias);
            $insert_query->bindParam(':nom', $nom);
            $insert_query->bindParam(':prenom', $prenom);
            $insert_query->bindParam(':mail', $email);
            $insert_query->bindParam(':mdp', $mdp);

            if ($insert_query->execute()) {
                // Afficher l'alerte d'inscription réussie
                echo "<script>document.getElementById('alert').innerHTML = 'Inscription réussie !'; document.getElementById('alert').style.backgroundColor = '#4CAF50';</script>";
            } else {
                // Afficher l'alerte d'échec d'inscription
                echo "<script>document.getElementById('alert').innerHTML = 'Erreur lors de l\'inscription. Veuillez réessayer plus tard.'; document.getElementById('alert').style.backgroundColor = '#f44336';</script>";
            }
        }
    }

    // Fermez la connexion à la base de données
    $mysqlConnection = null;
    ?>
</body>
</html>
