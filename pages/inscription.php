
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
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3><i class="bi bi-person-plus"></i> Inscription</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="traitement_inscription.php">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Votre nom</label>
                                <input type="text" name="nom" id="nom" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Votre email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="dtn" class="form-label">Date de naissance</label>
                                <input type="date" name="dtn" id="dtn" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="ville" class="form-label">Votre ville</label>
                                <input type="text" name="ville" id="ville" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Genre :</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="masculin" name="genre" value="M" required>
                                        <label class="form-check-label" for="masculin">
                                            <i class="bi bi-person"></i> Masculin
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="feminin" name="genre" value="F" required>
                                        <label class="form-check-label" for="feminin">
                                            <i class="bi bi-person-dress"></i> Féminin
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <input type="password" name="mdp" id="mdp" class="form-control" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> S'inscrire
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Déjà un compte ?</p>
                        <a href="login.php" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>