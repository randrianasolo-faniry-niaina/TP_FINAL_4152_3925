
<?php
require_once('../inc/fonction.php');
$membre=getmembre();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Membres</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Liste des Membres</h2>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date de Naissance</th>
                        <th>Genre</th>
                        <th>Ville</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($membre as $m) { ?>
                    <tr>
                        <td><?php echo $m['id_membre']; ?></td>
                        <td>
                            <a href="fiche_membre.php?membre=<?php echo urlencode($m['nom']); ?>" 
                               class="text-decoration-none">
                                <?php echo htmlspecialchars($m['nom']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($m['date_de_naissance'])); ?></td>
                        <td>
                            <?php 
                                if($m['genre'] == 'M') {
                                    echo '<span class="badge bg-primary">Masculin</span>';
                                } else if($m['genre'] == 'F') {
                                    echo '<span class="badge bg-info">Féminin</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">Non spécifié</span>';
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($m['ville']); ?></td>
                        <td>
                            <a href="fiche_membre.php?membre=<?php echo urlencode($m['nom']); ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Voir fiche
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>