<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
     <link rel="stylesheet" href="../assets/styles.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <form method="POST" action="traitement_inscription.php">
        <label for="nom">Votre nom</label>
        <input type="text" name="nom" id="nom"><br>

        <label for="email">Votre email</label>
        <input type="email" name="email" id="email"><br>

        <label for="dtn">Insérer votre date de naissance</label>
        <input type="date" name="dtn" id="dtn"><br>

        <label for="ville">Entrer votre ville</label>
        <input type="text" name="ville" id="ville"><br>

        <p>Genre :</p>
        <input type="radio" id="masculin" name="genre" value="M">
        <label for="masculin">Masculin</label><br>

        <input type="radio" id="feminin" name="genre" value="F">
        <label for="feminin">Féminin</label><br>

        

        <label for="mdp">Insérer votre mot de passe</label>
        <input type="password" name="mdp" id="mdp"><br>

        <br><input type="submit" value="Envoyer">

    </form>


    <a href="login.php" class="btn btn-secondary">Login</a>
</body>
</html>