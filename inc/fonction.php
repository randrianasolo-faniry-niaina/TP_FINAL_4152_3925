
<?php
ini_set("display_errors", "1");
require('connection.php');

function inscription($nom, $email, $date_naissance, $genre, $ville, $mdp)
{

    $check_sql = "SELECT id_membre FROM emp_membre WHERE email = '$email'";
    $check_result = mysqli_query(dbconnect(), $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        return false;
    }


    $sql = "INSERT INTO emp_membre (nom, email, date_de_naissance, genre, ville, mdp, image_profil) 
            VALUES ('$nom', '$email', '$date_naissance', '$genre', '$ville', '$mdp', 'default.png')";

    $result = mysqli_query(dbconnect(), $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function login($email, $mdp)
{
    $sql = "SELECT id_membre, nom, email, mdp FROM emp_membre WHERE email = '$email'";
    $result = mysqli_query(dbconnect(), $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($mdp == $user['mdp']) {
            return $user;
        }
    }

    return false;
}
function getListObjet($categorie = "tous", $nom_objet = "", $disponible_seulement = false)
{
    // Requête pour récupérer les objets avec leur image principale uniquement
    $sql = "SELECT DISTINCT o.id_objet, o.nom_objet, o.id_categorie, o.id_membre,
                   c.nom_categorie, 
                   m.nom as nom_proprio, m.email as email_membre, m.ville as ville_membre,
                   COALESCE(i.nom_image, 'c.png') as nom_image
            FROM emp_objet o
            JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN emp_membre m ON o.id_membre = m.id_membre
            LEFT JOIN emp_image i ON o.id_objet = i.id_objet AND i.est_principale = 1
            WHERE 1=1";


    if ($categorie != "tous") {
        $sql .= " AND c.nom_categorie = '" . mysqli_real_escape_string(dbconnect(), $categorie) . "'";
    }


    if (!empty($nom_objet)) {
        $sql .= " AND o.nom_objet LIKE '%" . mysqli_real_escape_string(dbconnect(), $nom_objet) . "%'";
    }


    if ($disponible_seulement) {
        $sql .= " AND o.id_objet NOT IN (
            SELECT DISTINCT e.id_objet 
            FROM emp_emprunt e 
            WHERE e.date_retour IS NULL OR e.date_retour > CURDATE()
        )";
    }

    $sql .= " ORDER BY o.nom_objet ASC";

    $result = mysqli_query(dbconnect(), $sql);
    $listObjet = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $listObjet[] = $row;
    }
    return $listObjet;
}
function getEmprunt()
{
    $sql = "SELECT * FROM v_emp_emprunt_objet_membre";
    $result = mysqli_query(dbconnect(), $sql);
    $listObjet = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $listObjet[] = $row;
    }
    return $listObjet;
}


function verifEmprunt($emp, $id_objet)
{
    $dernierEmprunt = null;

    foreach ($emp as $emprunt) {
        if ($emprunt['id_objet'] == $id_objet) {
            if ($dernierEmprunt == null || $emprunt['date_emprunt'] > $dernierEmprunt['date_emprunt']) {
                $dernierEmprunt = $emprunt;
            }
        }
    }
    return $dernierEmprunt !== null ? $dernierEmprunt : false;
}
function isObjetEmprunte($emprunt)
{
    if ($emprunt == false || $emprunt['date_retour'] == null) {
        return false;
    }

    $dateRetour = new DateTime($emprunt['date_retour']);
    $aujourdhui = new DateTime();

    return $dateRetour > $aujourdhui;
}
function getObjById($id_objet)
{
    $sql = "SELECT o.id_objet, o.nom_objet, o.id_categorie, o.id_membre,
                   c.nom_categorie, 
                   m.nom as nom_proprio, m.email as email_membre, m.ville as ville_membre,
                   m.date_de_naissance, m.genre as genre_membre
            FROM emp_objet o
            JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN emp_membre m ON o.id_membre = m.id_membre
            WHERE o.id_objet = '$id_objet'";

    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}

function getAllImagesById($id_objet)
{
    $sql = "SELECT * FROM emp_image WHERE id_objet = '$id_objet' ORDER BY est_principale DESC, id_image ASC";
    $result = mysqli_query(dbconnect(), $sql);
    $images = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }
    return $images;
}

function getmembreByName($membreName)
{
    $sql = "SELECT * FROM emp_membre WHERE nom = '$membreName'";
    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}


function getAllObjMembre($id_membre)
{
    $sql = "SELECT DISTINCT o.id_objet, o.nom_objet, o.id_categorie, o.id_membre,
                   c.nom_categorie, 
                   m.nom as nom_proprio, m.email as email_membre, m.ville as ville_membre,
                   COALESCE(i.nom_image, 'c.png') as nom_image
            FROM emp_objet o
            JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN emp_membre m ON o.id_membre = m.id_membre
            LEFT JOIN emp_image i ON o.id_objet = i.id_objet AND i.est_principale = 1
            WHERE o.id_membre = '$id_membre' 
            ORDER BY c.nom_categorie ASC, o.nom_objet ASC";

    $result = mysqli_query(dbconnect(), $sql);
    $listObjet = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $listObjet[] = $row;
    }
    return $listObjet;
}
function getCategories()
{
    $sql = "SELECT * FROM emp_categorie_objet ORDER BY nom_categorie ASC";
    $result = mysqli_query(dbconnect(), $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function createObjet($nom_objet, $id_categorie, $id_membre)
{
    $nom_objet = mysqli_real_escape_string(dbconnect(), $nom_objet);
    $id_categorie = mysqli_real_escape_string(dbconnect(), $id_categorie);
    $id_membre = mysqli_real_escape_string(dbconnect(), $id_membre);

    $sql = "INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) 
            VALUES ('$nom_objet', '$id_categorie', '$id_membre')";

    $result = mysqli_query(dbconnect(), $sql);

    if ($result) {
        return mysqli_insert_id(dbconnect());
    }

    return false;
}

function addImageToObjet($id_objet, $nom_fichier, $is_main = false)
{
    $id_objet = mysqli_real_escape_string(dbconnect(), $id_objet);
    $nom_fichier = mysqli_real_escape_string(dbconnect(), $nom_fichier);


    if ($is_main) {
        $sql_update = "UPDATE emp_image SET est_principale = 0 WHERE id_objet = '$id_objet'";
        mysqli_query(dbconnect(), $sql_update);
    }

    $is_main = $is_main ? 1 : 0;

    $sql = "INSERT INTO emp_image (id_objet, nom_image, est_principale) 
            VALUES ('$id_objet', '$nom_fichier', '$is_main')";

    return mysqli_query(dbconnect(), $sql);
}


function createUploadDirectory()
{
    $uploadDir = '../assets/images';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    return $uploadDir;
}

function handleImageUpload($file)
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Format d\'image non supporté.'];
    }

    // Vérifier la taille du fichier (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Fichier trop volumineux. Taille max: 5MB'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $extension;
    $uploadDir = createUploadDirectory();
    $finalPath = $uploadDir . '/' . $newFileName;

    // Vérifier si le dossier est accessible en écriture
    if (!is_writable($uploadDir)) {
        return ['success' => false, 'message' => 'Dossier d\'upload non accessible en écriture: ' . $uploadDir];
    }

    if (move_uploaded_file($file['tmp_name'], $finalPath)) {
        return [
            'success' => true,
            'data' => [
                'nom_fichier' => $newFileName,
                'nom_original' => $file['name'],
                'chemin_final' => $finalPath
            ]
        ];
    }

    return ['success' => false, 'message' => 'Erreur lors du téléchargement. Vérifiez les permissions du dossier.'];
}

function removeImageFromSession($imageIndex)
{
    if (isset($_SESSION['upload_images'][$imageIndex])) {
        $image = $_SESSION['upload_images'][$imageIndex];

        // Supprimer le fichier directement depuis assets/images
        if (file_exists($image['chemin_final'])) {
            unlink($image['chemin_final']);
        }

        // Supprimer de la session
        array_splice($_SESSION['upload_images'], $imageIndex, 1);
        return true;
    }

    return false;
}

function processObjetCreation($title, $categorie, $id_membre)
{
    if (empty($title) || empty($categorie)) {
        return ['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires.'];
    }

    // Créer l'objet
    $id_objet = createObjet($title, $categorie, $id_membre);

    if (!$id_objet) {
        return ['success' => false, 'message' => 'Erreur lors de la création de l\'objet.'];
    }

    // Traiter les images
    $images = $_SESSION['upload_images'] ?? [];

    if (empty($images)) {
        // Aucune image uploadée, utiliser l'image par défaut comme principale
        addImageToObjet($id_objet, 'c.png', true);
    } else {
        // Les images sont déjà dans assets/images, on les ajoute juste à la base de données
        foreach ($images as $index => $image) {
            // Seule la première image (index 0) est marquée comme principale
            $isMain = ($index === 0);
            addImageToObjet($id_objet, $image['nom_fichier'], $isMain);
        }
    }

    // Nettoyer la session
    unset($_SESSION['upload_images']);

    return ['success' => true, 'message' => 'Objet créé avec succès!'];
}

function getMainImageById($id_objet)
{
    $sql = "SELECT * FROM emp_image WHERE id_objet = '$id_objet' AND est_principale = 1 LIMIT 1";
    $result = mysqli_query(dbconnect(), $sql);
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    // Si aucune image principale trouvée, prendre la première image
    $sql_fallback = "SELECT * FROM emp_image WHERE id_objet = '$id_objet' ORDER BY id_image ASC LIMIT 1";
    $result_fallback = mysqli_query(dbconnect(), $sql_fallback);
    if (mysqli_num_rows($result_fallback) > 0) {
        return mysqli_fetch_assoc($result_fallback);
    }

    return false;
}

function setMainImage($id_objet, $id_image)
{
    $id_objet = mysqli_real_escape_string(dbconnect(), $id_objet);
    $id_image = mysqli_real_escape_string(dbconnect(), $id_image);

    // D'abord, désactiver toutes les images principales pour cet objet
    $sql_reset = "UPDATE emp_image SET est_principale = 0 WHERE id_objet = '$id_objet'";
    mysqli_query(dbconnect(), $sql_reset);

    // Ensuite, marquer l'image spécifiée comme principale
    $sql_set = "UPDATE emp_image SET est_principale = 1 WHERE id_image = '$id_image' AND id_objet = '$id_objet'";
    return mysqli_query(dbconnect(), $sql_set);
}

function ensureMainImage($id_objet)
{
    $id_objet = mysqli_real_escape_string(dbconnect(), $id_objet);


    $sql_check = "SELECT COUNT(*) as count FROM emp_image WHERE id_objet = '$id_objet' AND est_principale = 1";
    $result = mysqli_query(dbconnect(), $sql_check);
    $count = mysqli_fetch_assoc($result)['count'];


    if ($count == 0) {
        $sql_first = "SELECT id_image FROM emp_image WHERE id_objet = '$id_objet' ORDER BY id_image ASC LIMIT 1";
        $result_first = mysqli_query(dbconnect(), $sql_first);
        if (mysqli_num_rows($result_first) > 0) {
            $first_image = mysqli_fetch_assoc($result_first);
            $sql_update = "UPDATE emp_image SET est_principale = 1 WHERE id_image = '" . $first_image['id_image'] . "'";
            return mysqli_query(dbconnect(), $sql_update);
        }
    }


    if ($count > 1) {

        $sql_reset = "UPDATE emp_image SET est_principale = 0 WHERE id_objet = '$id_objet'";
        mysqli_query(dbconnect(), $sql_reset);


        $sql_first = "SELECT id_image FROM emp_image WHERE id_objet = '$id_objet' ORDER BY id_image ASC LIMIT 1";
        $result_first = mysqli_query(dbconnect(), $sql_first);
        if (mysqli_num_rows($result_first) > 0) {
            $first_image = mysqli_fetch_assoc($result_first);
            $sql_update = "UPDATE emp_image SET est_principale = 1 WHERE id_image = '" . $first_image['id_image'] . "'";
            return mysqli_query(dbconnect(), $sql_update);
        }
    }

    return true;
}







function getMembreEmpruntsHistorique($id_membre)
{
    $sql = "SELECT e.*, o.nom_objet, c.nom_categorie, m.nom as nom_proprietaire,
                   COALESCE(i.nom_image, 'c.png') as nom_image
            FROM emp_emprunt e
            JOIN emp_objet o ON e.id_objet = o.id_objet
            JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN emp_membre m ON o.id_membre = m.id_membre
            LEFT JOIN emp_image i ON o.id_objet = i.id_objet AND i.est_principale = 1
            WHERE e.id_membre = '$id_membre'
            ORDER BY e.date_emprunt DESC
            LIMIT 10";

    $result = mysqli_query(dbconnect(), $sql);
    $emprunts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emprunts[] = $row;
    }
    return $emprunts;
}

function getObjetsParCategorie($id_membre)
{
    $objets = getAllObjMembre($id_membre);
    $categories = [];

    foreach ($objets as $objet) {
        $categorie = $objet['nom_categorie'];
        if (!isset($categories[$categorie])) {
            $categories[$categorie] = [];
        }
        $categories[$categorie][] = $objet;
    }

    return $categories;
}

function getObjEmpruntsHistorique($id_objet)
{
    $sql = "SELECT e.*, m.nom as nom_emprunteur, m.email as email_emprunteur, m.ville as ville_emprunteur
            FROM emp_emprunt e
            JOIN emp_membre m ON e.id_membre = m.id_membre
            WHERE e.id_objet = '$id_objet'
            ORDER BY e.date_emprunt DESC";

    $result = mysqli_query(dbconnect(), $sql);
    $emprunts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emprunts[] = $row;
    }
    return $emprunts;
}
function getMembre(){
    $sql="SELECT * FROM emp_membre";
    $result = mysqli_query(dbconnect(), $sql);
    $membre = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $membre[] = $row;
    }
    return $membre;
};

function insererEmprunt($id_objet, $id_membre, $date_emprunt, $date_retour)
{

    $sql = "INSERT INTO emp_emprunt (id_objet, id_membre, date_emprunt, date_retour) 
            VALUES ('$id_objet', '$id_membre', '$date_emprunt', '$date_retour')";

    return mysqli_query(dbconnect(), $sql);
}