<?php 
require_once('../inc/fonction.php');

$nom_membre = $_GET['membre'] ?? '';
$membre = getmembreByName($nom_membre);

if (!$membre) {
    header('Location: list_objet.php?error=membre_non_trouve');
    exit();
}

$objetsParCategorie = getObjetsParCategorie($membre['id_membre']);

$empruntsHistorique = getMembreEmpruntsHistorique($membre['id_membre']);
$emprunt = getEmprunt();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche - <?= htmlspecialchars($membre['nom']) ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .category-section {
            margin-bottom: 2rem;
        }
        .object-card {
            transition: all 0.2s;
            cursor: pointer;
        }
        .object-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
 
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="list_objet.php">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../inc/deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="../assets/images/<?= htmlspecialchars($membre['image_profil']) ?>" 
                         alt="Photo de profil" 
                         class="profile-avatar"
                         onerror="this.src='../assets/images/default.png'">
                </div>
                <div class="col-md-9">
                    <h1 class="mb-2">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($membre['nom']) ?>
                    </h1>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <i class="bi bi-envelope"></i> 
                                <a href="mailto:<?= htmlspecialchars($membre['email']) ?>" class="text-white">
                                    <?= htmlspecialchars($membre['email']) ?>
                                </a>
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($membre['ville']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1">
                                <i class="bi bi-calendar"></i> 
                                Né(e) le <?= date('d/m/Y', strtotime($membre['date_de_naissance'])) ?>
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-person"></i> 
                                <?= $membre['genre'] === 'M' ? 'Homme' : 'Femme' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">

        
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="bi bi-grid-3x3-gap"></i> Objets par catégories
                </h2>

                <?php if (empty($objetsParCategorie)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Ce membre n'a encore ajouté aucun objet.
                    </div>
                <?php else: ?>
                    <?php foreach ($objetsParCategorie as $categorie => $objets): ?>
                        <div class="category-section">
                            <h3 class="mb-3">
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($categorie) ?> 
                                    (<?= count($objets) ?> objet<?= count($objets) > 1 ? 's' : '' ?>)
                                </span>
                            </h3>
                            
                            <div class="row">
                                <?php 
                                $objets_affiches = [];
                                foreach ($objets as $objet): 
                                    
                                    if (in_array($objet['id_objet'], $objets_affiches)) {
                                        continue;
                                    }
                                    $objets_affiches[] = $objet['id_objet'];
                                ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                                        <div class="card object-card h-100" onclick="window.location.href='fiche_obj.php?obj=<?= $objet['id_objet'] ?>'">
                                            <img src="../assets/images/<?= htmlspecialchars($objet['nom_image']) ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($objet['nom_objet']) ?>"
                                                 style="height: 150px; object-fit: cover;">
                                            
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">
                                                    <?= htmlspecialchars($objet['nom_objet']) ?>
                                                </h6>
                                                
                                                <?php 
                                               
                                                $emp = verifEmprunt($emprunt, $objet['id_objet']);
                                                $estEmprunte = $emp ? isObjetEmprunte($emp) : false;
                                                ?>
                                                
                                                <?php if ($estEmprunte): ?>
                                                    <small class="text-warning">
                                                        <i class="bi bi-exclamation-triangle"></i> Emprunté
                                                    </small>
                                                <?php else: ?>
                                                    <small class="text-success">
                                                        <i class="bi bi-check-circle"></i> Disponible
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

      
        <?php if (!empty($empruntsHistorique)): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">
                        <i class="bi bi-clock-history"></i> Historique des emprunts récents
                    </h3>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Objet</th>
                                    <th>Propriétaire</th>
                                    <th>Date d'emprunt</th>
                                    <th>Date de retour</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($empruntsHistorique as $emprunt_hist): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/<?= htmlspecialchars($emprunt_hist['nom_image']) ?>" 
                                                     alt="<?= htmlspecialchars($emprunt_hist['nom_objet']) ?>"
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;"
                                                     class="me-2">
                                                <div>
                                                    <strong><?= htmlspecialchars($emprunt_hist['nom_objet']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($emprunt_hist['nom_categorie']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($emprunt_hist['nom_proprietaire']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($emprunt_hist['date_emprunt'])) ?></td>
                                        <td>
                                            <?php if ($emprunt_hist['date_retour']): ?>
                                                <?= date('d/m/Y', strtotime($emprunt_hist['date_retour'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $maintenant = new DateTime();
                                            $dateRetour = $emprunt_hist['date_retour'] ? new DateTime($emprunt_hist['date_retour']) : null;
                                            
                                            if (!$dateRetour): ?>
                                                <span class="badge bg-warning">En cours</span>
                                            <?php elseif ($dateRetour > $maintenant): ?>
                                                <span class="badge bg-info">Emprunté</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Retourné</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="text-muted mb-0">
                <i class="bi bi-person-badge"></i> 
                Fiche membre - <?= htmlspecialchars($membre['nom']) ?>
            </p>
        </div>
    </footer>
</body>
</html>