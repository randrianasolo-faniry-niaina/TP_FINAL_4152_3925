<?php
$id = $_GET['id_objet'] ?? 0;
$id_membre = $_GET['id_membre'] ?? 0;
require_once('../inc/fonction.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprunt</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-book me-2"></i>
                            Emprunter l'objet
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="traitement_emprunt.php" method="POST">
                            <div class="mb-3">
                                <label for="nb_jour" class="form-label">
                                    <i class="bi bi-calendar-date me-1"></i>
                                    Nombre de jours
                                </label>
                                <input type="number"
                                    class="form-control"
                                    name="nb_jour"
                                    placeholder="Entrez le nombre de jours"
                                    required
                                    min="1">
                            </div>

                            <input type="hidden" name="id_objet" value="<?= $id ?>">
                            <input type="hidden" name="id_membre" value="<?= $id_membre ?>">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Valider l'emprunt
                                </button>
                                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>