
<?php
session_start();
require('../inc/fonction.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['upload_images'])) {
    $_SESSION['upload_images'] = [];
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_image':
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $result = handleImageUpload($_FILES['fichier']);
            
            if ($result['success']) {
                $_SESSION['upload_images'][] = $result['data'];
                $_SESSION['message'] = "Image ajoutée avec succès!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = $result['message'];
                $_SESSION['message_type'] = "error";
            }
        }
        header("Location: upload.php");
        break;
        
    case 'remove_image':
        $imageIndex = $_POST['image_index'] ?? -1;
        
        if (removeImageFromSession($imageIndex)) {
            $_SESSION['message'] = "Image supprimée.";
            $_SESSION['message_type'] = "success";
        }
        header("Location: upload.php");
        break;
        
    case 'create_objet':
        $title = $_POST['title'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $id_membre = $_SESSION['user_id'];
        
        $result = processObjetCreation($title, $categorie, $id_membre);
        
        $_SESSION['message'] = $result['message'];
        $_SESSION['message_type'] = $result['success'] ? "success" : "error";
        
        if ($result['success']) {
            header("Location: list_objet.php");
        } else {
            header("Location: upload.php");
        }
        break;
        
    default:
        header("Location: upload.php");
        break;
}

exit();
?>