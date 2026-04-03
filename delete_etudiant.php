<?php
include 'auth_check.php';
include 'autoloader.php';

// Réservé aux admins uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: list_etudiant.php');
    exit();
}

// Vérifier que l'id est passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_etudiant.php');
    exit();
}

$id  = (int) $_GET['id'];
$pdo = ConnexionDB::getInstance();

// Récupérer l'image avant suppression pour pouvoir effacer le fichier
$stmt = $pdo->prepare("SELECT image FROM etudiant WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if ($etudiant) {
    // Supprimer l'image du serveur si elle existe
    if (!empty($etudiant['image']) && file_exists('uploads/' . $etudiant['image'])) {
        unlink('uploads/' . $etudiant['image']);
    }

    // Supprimer l'étudiant de la base
    $stmt = $pdo->prepare("DELETE FROM etudiant WHERE id = ?");
    $stmt->execute([$id]);
    $stmt = $pdo->prepare("ALTER TABLE etudiant AUTO_INCREMENT = 1");
    $stmt->execute();
}

// Rediriger vers la liste mise à jour
header('Location: list_etudiant.php');
exit();