<?php
require('../inc/fonction.php');

$page = $_GET['page'];
if (!file_exists($page . '.php')) {
    echo "Page not found.";
    exit;
}

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page; ?>| </title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.css">
</head>
<?php
include('../inc/navbar.php');
?>

<body class="">
    <?php include($page . '.php'); ?>
</body>

</html>