
<?php
require_once('../inc/fonction.php');
$id_obj = $_GET['obj'] ?? 'default';
$objet = getObjById($id_obj);
$img = getAllImagesById($id_obj); 
$emprunt = getEmprunt();
$empruntHistorique = getObjEmpruntsHistorique($id_obj);

if (!$objet) {
    header('Location: list_objet.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche - <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="list_objet.php">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../inc/deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6">
                <!-- Carousel d'images - Affiche toutes les images -->
                <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($img as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="../assets/images/<?= htmlspecialchars($image['nom_image']) ?>" 
                                     class="d-block w-100" 
                                     alt="Image <?= $index + 1 ?>"
                                     style="height: 400px; object-fit: cover; border-radius: 10px;">
                                <?php if ($image['est_principale'] == 1): ?>
                                    <div class="carousel-caption d-none d-md-block">
                                        <span class="badge bg-primary fs-6">
                                            <i class="bi bi-star-fill"></i> Image principale
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($img) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100 shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="card-title mb-0">
                            <i class="bi bi-box"></i> <?= htmlspecialchars($objet['nom_objet']) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><i class="bi bi-tag"></i> Catégorie :</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-secondary fs-6">
                                    <?= htmlspecialchars($objet['nom_categorie']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><i class="bi bi-images"></i> Images :</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-info fs-6">
                                    <?= count($img) ?> image<?= count($img) > 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><i class="bi bi-person"></i> Propriétaire :</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="fiche_membre.php?membre=<?= urlencode($objet['nom_proprio']) ?>" 
                                   class="text-decoration-none">
                                    <?= htmlspecialchars($objet['nom_proprio']) ?>
                                    <i class="bi bi-person-lines-fill ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><i class="bi bi-envelope"></i> Email :</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="mailto:<?= htmlspecialchars($objet['email_membre']) ?>">
                                    <?= htmlspecialchars($objet['email_membre']) ?>
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong><i class="bi bi-geo-alt"></i> Ville :</strong>
                            </div>
                            <div class="col-sm-8">
                                <?= htmlspecialchars($objet['ville_membre']) ?>
                            </div>
                        </div>

                        <hr>

                        <h5><i class="bi bi-info-circle"></i> Statut de disponibilité</h5>

                        <?php if (verifEmprunt($emprunt, $objet['id_objet']) != false) {
                            $emp = verifEmprunt($emprunt, $objet['id_objet']);
                            $estEmprunte = isObjetEmprunte($emp);
                        ?>
                            <?php if ($estEmprunte): ?>
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <strong>Objet actuellement emprunté</strong><br>
                                    <div class="mt-2">
                                        <strong>Emprunteur :</strong> <?= htmlspecialchars($emp['nom_emprunteur']) ?><br>
                                        <strong>Date d'emprunt :</strong> <?= date('d/m/Y', strtotime($emp['date_emprunt'])) ?><br>
                                        <strong>Date de retour prévue :</strong> <?= date('d/m/Y', strtotime($emp['date_retour'])) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle"></i>
                                    <strong>Objet disponible pour emprunt</strong>
                                </div>
                            <?php endif; ?>

                            <div class="mt-3">
                                <?php if ($estEmprunte): ?>
                                    <button class="btn btn-secondary btn-lg w-100" disabled>
                                        <i class="bi bi-clock"></i> Non disponible
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-hand-thumbs-up"></i> Emprunter cet objet
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle"></i>
                                <strong>Objet disponible pour emprunt</strong>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-hand-thumbs-up"></i> Emprunter cet objet
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

       
        <?php if (count($img) > 1): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h4><i class="bi bi-images"></i> Toutes les images (<?= count($img) ?> image<?= count($img) > 1 ? 's' : '' ?>)</h4>
                    <div class="row">
                        <?php foreach ($img as $index => $image): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="position-relative">
                                    <img src="../assets/images/<?= htmlspecialchars($image['nom_image']) ?>" 
                                         class="img-thumbnail" 
                                         alt="Image <?= $index + 1 ?>"
                                         style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                         onclick="document.querySelector('#carouselImages').scrollIntoView(); 
                                                 bootstrap.Carousel.getInstance(document.querySelector('#carouselImages')).to(<?= $index ?>);">
                                    <?php if ($image['est_principale'] == 1): ?>
                                        <span class="position-absolute top-0 start-0 badge bg-primary m-2">
                                            <i class="bi bi-star-fill"></i> Principale
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>