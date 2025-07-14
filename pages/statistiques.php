
<?php
session_start();
require_once('../inc/fonction.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Récupérer les paramètres de filtre
$filtre = $_GET['filtre'] ?? 'tous';
$recherche = $_GET['recherche'] ?? '';

// Récupérer les données
$statistiques = getStatistiquesEmprunts();
$emprunts = getTousLesEmprunts($filtre, $recherche);
$statsParMembre = getStatistiquesParMembre();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des emprunts</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .emprunt-row {
            transition: background-color 0.2s;
        }
        .emprunt-row:hover {
            background-color: #f8f9fa;
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
                <span class="navbar-text me-3">
                    Bonjour <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                </span>
                <a class="nav-link" href="../inc/deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="bi bi-bar-chart-line"></i> Statistiques des emprunts
            </h1>
            <div>
                <span class="badge bg-secondary fs-6">
                    <?= count($emprunts) ?> résultat<?= count($emprunts) > 1 ? 's' : '' ?>
                </span>
            </div>
        </div>

        <!-- Statistiques générales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card text-center bg-primary text-white">
                    <div class="card-body">
                        <div class="stat-number"><?= $statistiques['total_emprunts'] ?></div>
                        <div>Total emprunts</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center bg-warning text-white">
                    <div class="card-body">
                        <div class="stat-number"><?= $statistiques['emprunts_en_cours'] ?></div>
                        <div>En cours</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center bg-success text-white">
                    <div class="card-body">
                        <div class="stat-number"><?= $statistiques['emprunts_termines'] ?></div>
                        <div>Terminés</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card text-center bg-info text-white">
                    <div class="card-body">
                        <div class="stat-number">
                            <?= $statistiques['total_emprunts'] > 0 ? 
                                round(($statistiques['emprunts_termines'] / $statistiques['total_emprunts']) * 100, 1) : 0 ?>%
                        </div>
                        <div>Taux de retour</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques par état -->
        <?php if (!empty($statistiques['par_etat'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-clipboard-check"></i> État des objets retournés
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($statistiques['par_etat'] as $etat => $count): ?>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <?php if ($etat == 'bon'): ?>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <span class="badge bg-success me-2"><?= $count ?></span>
                                            <?php elseif ($etat == 'abîmé'): ?>
                                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                                <span class="badge bg-warning me-2"><?= $count ?></span>
                                            <?php elseif ($etat == 'cassé'): ?>
                                                <i class="bi bi-x-circle text-danger me-2"></i>
                                                <span class="badge bg-danger me-2"><?= $count ?></span>
                                            <?php endif; ?>
                                            <span>État: <?= ucfirst($etat) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filtres et recherche -->
        <div class="filter-section">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="filtre" class="form-label">Filtrer par statut:</label>
                    <select name="filtre" id="filtre" class="form-select">
                        <option value="tous" <?= $filtre == 'tous' ? 'selected' : '' ?>>Tous les emprunts</option>
                        <option value="en_cours" <?= $filtre == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="termines" <?= $filtre == 'termines' ? 'selected' : '' ?>>Terminés</option>
                        <option value="bon" <?= $filtre == 'bon' ? 'selected' : '' ?>>Retournés en bon état</option>
                        <option value="abime" <?= $filtre == 'abime' ? 'selected' : '' ?>>Retournés abîmés</option>
                        <option value="casse" <?= $filtre == 'casse' ? 'selected' : '' ?>>Retournés cassés</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="recherche" class="form-label">Rechercher:</label>
                    <input type="text" name="recherche" id="recherche" class="form-control" 
                           value="<?= htmlspecialchars($recherche) ?>" 
                           placeholder="Nom d'objet, emprunteur ou propriétaire...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des emprunts -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul"></i> 
                    Liste des emprunts
                    <?php if ($filtre != 'tous' || !empty($recherche)): ?>
                        <small class="text-muted">(filtré)</small>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($emprunts)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox"></i>
                        <p>Aucun emprunt trouvé avec ces critères.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Objet</th>
                                    <th>Emprunteur</th>
                                    <th>Propriétaire</th>
                                    <th>Date emprunt</th>
                                    <th>Date retour</th>
                                    <th>État</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprunts as $emprunt): ?>
                                    <tr class="emprunt-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../assets/images/<?= htmlspecialchars($emprunt['nom_image']) ?>" 
                                                     alt="<?= htmlspecialchars($emprunt['nom_objet']) ?>"
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;"
                                                     class="me-2">
                                                <div>
                                                    <strong><?= htmlspecialchars($emprunt['nom_objet']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($emprunt['nom_categorie']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($emprunt['nom_emprunteur']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($emprunt['email_emprunteur']) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($emprunt['nom_proprietaire']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($emprunt['email_proprietaire']) ?></small>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($emprunt['date_emprunt'])) ?></td>
                                        <td>
                                            <?php if ($emprunt['date_retour']): ?>
                                                <?= date('d/m/Y', strtotime($emprunt['date_retour'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($emprunt['etat_objet']): ?>
                                                <?php if ($emprunt['etat_objet'] == 'bon'): ?>
                                                    <span class="badge bg-success">Bon</span>
                                                <?php elseif ($emprunt['etat_objet'] == 'abîmé'): ?>
                                                    <span class="badge bg-warning">Abîmé</span>
                                                <?php elseif ($emprunt['etat_objet'] == 'cassé'): ?>
                                                    <span class="badge bg-danger">Cassé</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$emprunt['date_retour']): ?>
                                                <span class="badge bg-warning">En cours</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Retourné</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistiques par membre -->
        <?php if (!empty($statsParMembre)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-people"></i> Top emprunteurs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Membre</th>
                                            <th>Total emprunts</th>
                                            <th>En cours</th>
                                            <th>Terminés</th>
                                            <th>Progression</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($statsParMembre, 0, 10) as $stat): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($stat['nom']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($stat['email']) ?></small>
                                                </td>
                                                <td><span class="badge bg-primary"><?= $stat['total_emprunts'] ?></span></td>
                                                <td><span class="badge bg-warning"><?= $stat['en_cours'] ?></span></td>
                                                <td><span class="badge bg-success"><?= $stat['termines'] ?></span></td>
                                                <td>
                                                    <?php 
                                                    $pourcentage = $stat['total_emprunts'] > 0 ? 
                                                        round(($stat['termines'] / $stat['total_emprunts']) * 100, 1) : 0;
                                                    ?>
                                                    <div class="progress" style="width: 100px; height: 20px;">
                                                        <div class="progress-bar" 
                                                             style="width: <?= $pourcentage ?>%">
                                                            <?= $pourcentage ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="mt-5 py-4 bg-light">
        <div class="container text-center">
            <p class="text-muted mb-0">
                <i class="bi bi-bar-chart-line"></i> 
                Statistiques des emprunts - <?= date('d/m/Y') ?>
            </p>
        </div>
    </footer>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>