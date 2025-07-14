<?php
session_start();
require_once('../inc/fonction.php');

$categorie = $_GET['cat'] ?? "tous";
$nom_objet = $_GET['nom'] ?? "";
$disponible_seulement = isset($_GET['disponible']) ? true : false;

$obj = getListObjet($categorie, $nom_objet, $disponible_seulement);
$emprunt = getEmprunt();
$categories = getCategories();
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
    <style>
        .card-hover {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .card-hover:hover .card-title {
            color: #0d6efd !important;
        }

        .search-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
        }

        .search-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <p><i class="bi bi-box-seam"></i> Liste des Objets</p>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../inc/deconnexion.php">Deconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="position-relative">
                    <h1 class="text-start mb-4">
                        <i class="bi bi-box-seam"></i> Liste des Objets
                    </h1>
                    <div class="position-absolute" style="top: 0; right: 0;">
                        <a href="membre.php" class="btn btn-info me-2">
                            <i class="bi bi-people"></i> Membres
                        </a>
                        <a href="upload.php" class="btn btn-success">
                            <i class="bi bi-cloud-upload"></i> Upload
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card shadow-sm search-card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-funnel"></i> Critères de recherche
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="get" action="list_objet.php" class="row g-3">
                                <div class="col-md-4">
                                    <label for="categoryFilter" class="form-label">
                                        <i class="bi bi-tag"></i> Catégorie :
                                    </label>
                                    <select class="form-select" id="categoryFilter" name="cat">
                                        <option value="tous" <?= $categorie === "tous" ? "selected" : "" ?>>
                                            Toutes les catégories
                                        </option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat['nom_categorie']) ?>"
                                                <?= $categorie === $cat['nom_categorie'] ? "selected" : "" ?>>
                                                <?= htmlspecialchars(ucfirst($cat['nom_categorie'])) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="nomObjet" class="form-label">
                                        <i class="bi bi-search"></i> Nom de l'objet :
                                    </label>
                                    <input type="text"
                                        class="form-control"
                                        id="nomObjet"
                                        name="nom"
                                        placeholder="Rechercher un objet..."
                                        value="<?= htmlspecialchars($nom_objet) ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label d-block">
                                        <i class="bi bi-check-circle"></i> Disponibilité :
                                    </label>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            id="disponible"
                                            name="disponible"
                                            value="1"
                                            <?= $disponible_seulement ? "checked" : "" ?>>
                                        <label class="form-check-label" for="disponible">
                                            Objets disponibles seulement
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Rechercher
                                        </button>
                                        <a href="list_objet.php" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                                        </a>
                                        <div class="ms-auto">
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-list-ul"></i>
                                                <?= count($obj) ?> objet<?= count($obj) > 1 ? 's' : '' ?> trouvé<?= count($obj) > 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if ($categorie !== "tous" || !empty($nom_objet) || $disponible_seulement): ?>
                    <div class="alert alert-info mb-4">
                        <h6 class="mb-2">
                            <i class="bi bi-filter"></i> Recherche active :
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if ($categorie !== "tous"): ?>
                                <span class="badge bg-primary">
                                    <i class="bi bi-tag"></i> Catégorie: <?= htmlspecialchars(ucfirst($categorie)) ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($nom_objet)): ?>
                                <span class="badge bg-primary">
                                    <i class="bi bi-search"></i> Nom: "<?= htmlspecialchars($nom_objet) ?>"
                                </span>
                            <?php endif; ?>

                            <?php if ($disponible_seulement): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Disponibles seulement
                                </span>
                            <?php endif; ?>

                            <a href="list_objet.php" class="badge bg-warning text-dark text-decoration-none">
                                <i class="bi bi-x-circle"></i> Effacer les filtres
                            </a>
                        </div>
                    </div>
                <?php endif; ?>


                <div class="row">
                    <?php if (empty($obj)): ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center py-5">
                                <i class="bi bi-search" style="font-size: 3rem;"></i>
                                <h4 class="mt-3">Aucun objet trouvé</h4>
                                <p class="mb-3">Aucun objet ne correspond à vos critères de recherche.</p>
                                <a href="list_objet.php" class="btn btn-primary">
                                    <i class="bi bi-arrow-clockwise"></i> Voir tous les objets
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($obj as $objet): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="fiche_obj.php?obj=<?= $objet['id_objet'] ?>" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm card-hover">
                                        <img src="../assets/images/<?= htmlspecialchars($objet['nom_image']) ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($objet['nom_objet']) ?>"
                                            style="height: 200px; object-fit: cover;">

                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title text-dark">
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
                                                    <div class="alert alert-success mb-2" role="alert">
                                                        <i class="bi bi-check-circle"></i>
                                                        <strong>Disponible le </strong>
                                                        <?= $emp['date_retour']; ?>
                                                    </div>
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
                                                        <a href="emprunt.php?id_objet=<?= $objet['id_objet']; ?>&id_membre=<?= $_SESSION['user_id']; ?>" class="btn btn-outline-secondary"><i class="bi bi-hand-thumbs-up"></i> Emprunter cet objet</a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php } else { ?>
                                                <div class="alert alert-success mb-2" role="alert">
                                                    <i class="bi bi-check-circle"></i>
                                                    <strong>Disponible</strong>
                                                    <a href="emprunt.php?id_objet=<?= $objet['id_objet']; ?>&id_membre=<?= $_SESSION['user_id']; ?>" class="btn btn-outline-secondary"><i class="bi bi-hand-thumbs-up"></i> Emprunter cet objet</a>
                                                </div>
                                            <?php } ?>

                                            <div class="mt-auto">
                                                <div class="text-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-eye"></i> Cliquez pour voir la fiche
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>