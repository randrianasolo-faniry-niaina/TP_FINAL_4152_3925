
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('../inc/fonction.php');
$categories = getCategories();
$selectedImages = isset($_SESSION['upload_images']) ? $_SESSION['upload_images'] : [];


$message = $_SESSION['message'] ?? '';
$messageType = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un objet</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Nouvel Objet</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
       
        <form action="traitement_upload.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Nom de l'objet</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Titre de votre objet" required>
            </div>
            
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="categorie" name="categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" name="action" value="create_objet" class="btn btn-primary">Publier l'objet</button>
        </form>
        
        <hr>
        
        
        <h4>Gestion des images</h4>
        
        
        <form action="traitement_upload.php" method="POST" enctype="multipart/form-data" class="mb-3">
            <div class="mb-3">
                <label for="fichier" class="form-label">Ajouter une image</label>
                <input type="file" class="form-control" id="fichier" name="fichier" accept="image/*" required>
            </div>
            <button type="submit" name="action" value="add_image" class="btn btn-success">Ajouter l'image</button>
        </form>
        
        
        <?php if (!empty($selectedImages)): ?>
            <div class="row">
                <h5>Images sélectionnées (<?= count($selectedImages) ?> image(s))</h5>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> La première image sera automatiquement définie comme image principale.
                </div>
                <?php foreach ($selectedImages as $index => $image): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            
                            <img src="../assets/images/<?= htmlspecialchars($image['nom_fichier']) ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted"><?= htmlspecialchars($image['nom_original']) ?></small>
                                <?php if ($index === 0): ?>
                                    <div class="badge bg-primary">Image principale</div>
                                <?php endif; ?>
                                <form method="POST" action="traitement_upload.php" class="mt-2">
                                    <input type="hidden" name="image_index" value="<?= $index ?>">
                                    <button type="submit" name="action" value="remove_image" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Aucune image sélectionnée. Une image par défaut sera utilisée.</p>
        <?php endif; ?>
        
        <a href="list_objet.php" class="btn btn-secondary">⬅ Retour</a>
    </div>
</body>

</html>