<?php
require_once('../inc/fonction.php');

$categorie = $_GET['cat'] ?? "tous";
$obj = getListObjet($categorie);
$emprunt = getEmprunt();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes Objets</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>
    
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">
                    <i class="bi bi-box-seam"></i> Liste des Objets
                </h1>
                
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <form method="get" action="list_objet.php">
                                <label for="categoryFilter" class="form-label">Filtrer par catégorie :</label>
                                <select class="form-select" id="categoryFilter" name="cat">
                                    <option value="tous">
                                        Toutes les catégories
                                    </option>
                                    <option value="esthétique">
                                        Esthétique
                                    </option>
                                    <option value="bricolage">
                                        Bricolage
                                    </option>
                                    <option value="mécanique">
                                        Mécanique
                                    </option>
                                    <option value="cuisine">
                                        Cuisine
                                    </option>
                                </select>

                                <input type="submit" value="Filtrer">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $objets_affiches = [];

                    foreach ($obj as $objet) {

                        if (in_array($objet['id_objet'], $objets_affiches)) {
                            continue;
                        }

                        $objets_affiches[] = $objet['id_objet'];
                    ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="../assets/images/<?= htmlspecialchars($objet['nom_image']) ?>"
                                    class="card-img-top"
                                    alt="<?= htmlspecialchars($objet['nom_objet']) ?>"
                                    style="height: 200px; object-fit: cover;">

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <i class="bi bi-box"></i> <?= htmlspecialchars($objet['nom_objet']) ?>
                                    </h5>

                                    <div class="mb-2">
                                        <span class="badge bg-secondary">
                                            Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?>
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i>
                                            Propriétaire: <?= htmlspecialchars($objet['nom_proprio']) ?>
                                        </small>
                                    </div>

                                    <?php if (verifEmprunt($emprunt, $objet['id_objet']) != false) {
                                        $emp = verifEmprunt($emprunt, $objet['id_objet']);
                                        $estEmprunte = isObjetEmprunte($emp);
                                    ?>
                                        <?php if ($estEmprunte): ?>
                                            <div class="alert alert-warning mb-2" role="alert">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                <strong>Emprunté</strong><br>
                                                <small>
                                                    Depuis le: <?= date('d/m/Y', strtotime($emp['date_emprunt'])) ?><br>
                                                    Par: <?= htmlspecialchars($emp['nom_emprunteur']) ?><br>
                                                    Retour prévu: <?= date('d/m/Y', strtotime($emp['date_retour'])) ?>
                                                </small>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-success mb-2" role="alert">
                                                <i class="bi bi-check-circle"></i>
                                                <strong>Disponible</strong>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-auto">
                                            <?php if ($estEmprunte): ?>
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="bi bi-clock"></i> Non disponible
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-primary">
                                                    <i class="bi bi-hand-thumbs-up"></i> Emprunter
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-success mb-2" role="alert">
                                            <i class="bi bi-check-circle"></i>
                                            <strong>Disponible</strong>
                                        </div>
                                        <div class="mt-auto">
                                            <button class="btn btn-primary">
                                                <i class="bi bi-hand-thumbs-up"></i> Emprunter
                                            </button>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</body>

</html>