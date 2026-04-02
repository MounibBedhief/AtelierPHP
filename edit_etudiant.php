<?php
include 'auth_check.php';
include 'autoloader.php';

// Réservé aux admins uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: list_etudiant.php');
    exit();
}

$pdo = ConnexionDB::getInstance();
$errors = [];

// Vérifier que l'id est passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_etudiant.php');
    exit();
}

$id = (int) $_GET['id'];

// Récupérer l'étudiant existant
$stmt = $pdo->prepare("SELECT * FROM etudiant WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    header('Location: list_etudiant.php');
    exit();
}

// Récupérer les sections pour le dropdown
$sections = $pdo->query("SELECT * FROM section")->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom            = trim($_POST['nom'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';
    $section_id     = $_POST['section_id'] ?? '';
    $image_name     = $etudiant['image']; // Garder l'ancienne image par défaut

    // Validation
    if (empty($nom))            $errors[] = "Le nom est obligatoire.";
    if (empty($date_naissance)) $errors[] = "La date de naissance est obligatoire.";
    if (empty($section_id))     $errors[] = "La section est obligatoire.";

    // Gestion de la nouvelle image (si fournie)
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $extension   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image   = uniqid('etudiant_') . '.' . $extension;
        $destination = $upload_dir . $new_image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            // Supprimer l'ancienne image si elle existe
            if ($etudiant['image'] && file_exists('uploads/' . $etudiant['image'])) {
                unlink('uploads/' . $etudiant['image']);
            }
            $image_name = $new_image;
        } else {
            $errors[] = "Erreur lors de l'upload de l'image.";
        }
    }

    // Mise à jour en base si pas d'erreurs
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE etudiant SET nom = ?, image = ?, date_naissance = ?, section_id = ? WHERE id = ?");
        $stmt->execute([$nom, $image_name, $date_naissance, $section_id, $id]);
        header('Location: list_etudiant.php');
        exit();
    }

    // En cas d'erreur, mettre à jour les valeurs pour le formulaire
    $etudiant['nom']            = $nom;
    $etudiant['date_naissance'] = $date_naissance;
    $etudiant['section_id']     = $section_id;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="list_etudiant.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Student Management System</a>
    <div class="collapse navbar-collapse">
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="home.php">Home</a>
        <a class="nav-link active" href="list_etudiant.php">Liste des étudiants</a>
        <a class="nav-link" href="list_section.php">Liste des sections</a>
        <a class="nav-link logout" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<div class="container mt-4" style="max-width: 600px;">
    <h4 class="text-muted mb-4">Mettre à jour un étudiant</h4>

    <?php if (!empty($errors)): ?>
        <div id="errorAlert" class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" onclick="document.getElementById('errorAlert').style.display='none'"></button>
            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">name</label>
            <input type="text" name="nom" class="form-control"
                   value="<?= htmlspecialchars($etudiant['nom']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Date de naissance</label>
            <input type="date" name="date_naissance" class="form-control"
                   value="<?= htmlspecialchars($etudiant['date_naissance']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Image <small class="text-muted">(laisser vide pour garder l'actuelle)</small></label>
            <?php if ($etudiant['image'] && file_exists('uploads/' . $etudiant['image'])): ?>
                <div class="mb-2">
                    <img src="uploads/<?= htmlspecialchars($etudiant['image']) ?>"
                         class="rounded-circle" width="50" height="50" alt="Image actuelle">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Section</label>
            <select name="section_id" class="form-select">
                <option value="">Votre section</option>
                <?php foreach ($sections as $section): ?>
                    <option value="<?= $section['id'] ?>"
                        <?= ($etudiant['section_id'] == $section['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($section['designation']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="list_etudiant.php" class="btn btn-secondary ms-2">Annuler</a>
    </form>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>