<?php
include 'auth_check.php';
include 'autoloader.php';

$pdo = ConnexionDB::getInstance();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: list_etudiant.php');
  exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT E.*, S.designation AS section_nom FROM etudiant E JOIN section S ON E.section_id = S.id WHERE E.id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
  header('Location: list_etudiant.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails de l'étudiant</title>
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
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Détails de l'étudiant</h4>
      </div>
      <div class="card-body">
        <div class="text-center mb-4">
          <?php if ($etudiant['image'] && file_exists('uploads/' . $etudiant['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($etudiant['image']) ?>" class="rounded-circle" width="150" height="150" alt="Photo">
          <?php else: ?>
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($etudiant['nom']) ?>" class="rounded-circle" width="150" height="150" alt="Avatar">
          <?php endif; ?>
        </div>

        <table class="table table-bordered">
          <tr>
            <th style="width: 40%">ID</th>
            <td><?= $etudiant['id'] ?></td>
          </tr>
          <tr>
            <th>Nom complet</th>
            <td><?= htmlspecialchars($etudiant['nom']) ?></td>
          </tr>
          <tr>
            <th>Date de naissance</th>
            <td><?= date('d/m/Y', strtotime($etudiant['date_naissance'])) ?></td>
          </tr>
          <tr>
            <th>Section</th>
            <td><?= htmlspecialchars($etudiant['section_nom']) ?></td>
          </tr>
        </table>

        <div class="text-center mt-3">
          <a href="list_etudiant.php" class="btn btn-secondary">Retour à la liste</a>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="edit_etudiant.php?id=<?= $etudiant['id'] ?>" class="btn btn-primary">Modifier</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
