<?php 
session_start();
require_once('../inc/fonction.php');

$nom_membre = $_GET['membre'] ?? '';
$membre = getmembreByName($nom_membre);

if (!$membre) {
    header('Location: list_objet.php?error=membre_non_trouve');
    exit();
}

$objetsParCategorie = getObjetsParCategorie($membre['id_membre']);
$empruntsHistorique = getMembreEmpruntsHistorique($membre['id_membre']);
$empruntsNonRendus = getEmpruntsNonRendus($membre['id_membre']);
$emprunt = getEmprunt();

// Récupérer les messages de session
$success_message = $_SESSION['success'] ?? null;
$error_message = $_SESSION['error'] ?? null;

// Nettoyer les messages de session
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche - <?= htmlspecialchars($membre['nom']) ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
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
        .emprunt-card {
            border-left: 4px solid #ffc107;
            background-color: #fff9e6;
        }
        .retour-form {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        .retour-form.show {
            display: block;
        }
        .retour-form.hide {
            display: none;
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
        
        <!-- Messages de succès/erreur -->
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Section des emprunts en cours -->
        <?php if (!empty($empruntsNonRendus)): ?>
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="mb-4">
                        <i class="bi bi-clock text-warning"></i> Emprunts en cours
                        <span class="badge bg-warning"><?= count($empruntsNonRendus) ?></span>
                    </h2>
                    
                    <div class="row">
                        <?php foreach ($empruntsNonRendus as $emprunt_cours): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card emprunt-card h-100">
                                    <img src="../assets/images/<?= htmlspecialchars($emprunt_cours['nom_image']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($emprunt_cours['nom_objet']) ?>"
                                         style="height: 150px; object-fit: cover;">
                                    
                                    <div class="card-body">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-box"></i> <?= htmlspecialchars($emprunt_cours['nom_objet']) ?>
                                        </h6>
                                        <p class="card-text small">
                                            <i class="bi bi-tag"></i> <?= htmlspecialchars($emprunt_cours['nom_categorie']) ?><br>
                                            <i class="bi bi-person"></i> Propriétaire: <?= htmlspecialchars($emprunt_cours['nom_proprietaire']) ?><br>
                                            <i class="bi bi-calendar"></i> Emprunté le: <?= date('d/m/Y', strtotime($emprunt_cours['date_emprunt'])) ?>
                                        </p>
                                        
                                        <?php if ($emprunt_cours['date_retour'] && strtotime($emprunt_cours['date_retour']) > time()): ?>
                                            <div class="mb-2">
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock"></i> 
                                                    À rendre le <?= date('d/m/Y', strtotime($emprunt_cours['date_retour'])) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <!-- Bouton pour afficher le formulaire -->
                                        <a href="#retour-form-<?= $emprunt_cours['id_emprunt'] ?>" 
                                           class="btn btn-success btn-sm w-100 mb-2">
                                            <i class="bi bi-arrow-return-left"></i> Rendre cet objet
                                        </a>
                                        
                                        <!-- Formulaire de retour -->
                                        <div class="retour-form" id="retour-form-<?= $emprunt_cours['id_emprunt'] ?>">
                                            <h6 class="mb-3">
                                                <i class="bi bi-arrow-return-left"></i> 
                                                Rendre: <?= htmlspecialchars($emprunt_cours['nom_objet']) ?>
                                            </h6>
                                            
                                            <form action="traitement_retour.php" method="POST">
                                                <input type="hidden" name="id_emprunt" value="<?= $emprunt_cours['id_emprunt'] ?>">
                                                <input type="hidden" name="nom_membre" value="<?= htmlspecialchars($membre['nom']) ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">État de l'objet au retour:</label>
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="etat_objet" 
                                                               id="bon-<?= $emprunt_cours['id_emprunt'] ?>" value="bon" checked>
                                                        <label class="form-check-label text-success" for="bon-<?= $emprunt_cours['id_emprunt'] ?>">
                                                            <i class="bi bi-check-circle"></i> Bon état
                                                        </label>
                                                    </div>
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="etat_objet" 
                                                               id="abime-<?= $emprunt_cours['id_emprunt'] ?>" value="abîmé">
                                                        <label class="form-check-label text-warning" for="abime-<?= $emprunt_cours['id_emprunt'] ?>">
                                                            <i class="bi bi-exclamation-triangle"></i> Abîmé
                                                        </label>
                                                    </div>
                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="etat_objet" 
                                                               id="casse-<?= $emprunt_cours['id_emprunt'] ?>" value="cassé">
                                                        <label class="form-check-label text-danger" for="casse-<?= $emprunt_cours['id_emprunt'] ?>">
                                                            <i class="bi bi-x-circle"></i> Cassé
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="alert alert-info py-2">
                                                    <small>
                                                        <i class="bi bi-info-circle"></i>
                                                        En confirmant, vous rendez cet objet aujourd'hui.
                                                    </small>
                                                </div>
                                                
                                                <div class="d-grid gap-2">
                                                    <button type="submit" name="rendre_objet" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle"></i> Confirmer le retour
                                                    </button>
                                                    <a href="fiche_membre.php?membre=<?= urlencode($membre['nom']) ?>" 
                                                       class="btn btn-secondary btn-sm">
                                                        Annuler
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Section des objets par catégories -->
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
                                        <div class="card object-card h-100">
                                            <a href="fiche_obj.php?obj=<?= $objet['id_objet'] ?>" class="text-decoration-none">
                                                <img src="../assets/images/<?= htmlspecialchars($objet['nom_image']) ?>" 
                                                     class="card-img-top" 
                                                     alt="<?= htmlspecialchars($objet['nom_objet']) ?>"
                                                     style="height: 150px; object-fit: cover;">
                                                
                                                <div class="card-body p-3">
                                                    <h6 class="card-title mb-2 text-dark">
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
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Historique des emprunts -->
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
                                    <th>État retour</th>
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
                                            <?php if ($emprunt_hist['etat_objet']): ?>
                                                <?php if ($emprunt_hist['etat_objet'] == 'bon'): ?>
                                                    <span class="badge bg-success">Bon</span>
                                                <?php elseif ($emprunt_hist['etat_objet'] == 'abîmé'): ?>
                                                    <span class="badge bg-warning">Abîmé</span>
                                                <?php elseif ($emprunt_hist['etat_objet'] == 'cassé'): ?>
                                                    <span class="badge bg-danger">Cassé</span>
                                                <?php endif; ?>
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