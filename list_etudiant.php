<?php include 'autoloader.php';
session_start(); 
$pdo = ConnexionDB::getInstance();

try {
    $query = $pdo->query("SELECT E.*, S.designation AS section_nom FROM etudiant E JOIN section S ON E.section_id = S.id");
    $etudiants = $query->fetchAll();
} catch (Exception $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="node_modules/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="list_etudiant.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
  <div class="container"> 
      <a class="navbar-brand fw-bold" href="#">Student Management System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto"> 
          <a class="nav-link" href="home.php">Home</a>
          <a class="nav-link active" href="list_etudiant.php">Liste des étudiants</a>
          <a class="nav-link" href="list_section.php">Liste des sections</a>
          <a class="nav-link logout" href="logout.php">Logout</a>
        </div>
      </div>
  </div>
</nav>

<div class="container mt-4">
    <div class="p-2 mb-3 bg-light border rounded">
        <h3 class="text-muted m-0" style="font-size: 1.2rem;">Liste des étudiants</h3>
    </div>
    
    <div class="d-flex align-items-center mb-3">
        <input type="text" id="customSearch" class="form-control w-25 me-2" placeholder="Veuillez renseigner votre...">
        <button id="btnFiltrer" class="btn btn-danger me-2">Filtrer</button>
        <a href="add_etudiant.php" class="text-primary fs-3"><i class="fas fa-user-plus"></i></a>
    </div>

    <table id="tableEtudiants" class="table table-striped border">
        <thead>
            <tr>
                <th>id</th>
                <th>image</th>
                <th>name</th>
                <th>birthday</th>
                <th>section</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($etudiants as $etudiant) : ?>
                <tr>
                    <td><?php echo $etudiant['id'];?></td>
                    <td><img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo $etudiant['nom']; ?>" alt="avatar" class="rounded-circle" width="40" height="40" alt="Student"></td>
                    <td><?php echo $etudiant['nom'];?></td>
                    <td><?php echo $etudiant['date_naissance'];?></td>
                    <td><?php echo $etudiant['section_nom'];?></td>
                    <td class="text-center">
                        <a href="view_etudiant.php?id=<?php echo $etudiant['id'];?>" class="text-info me-2">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </a>
                        <a href="delete_etudiant.php?id=<?php echo $etudiant['id'];?>" class="text-primary me-2" onclick="return confirm('Supprimer ?');">
                            <i class="fas fa-eraser fa-lg"></i>
                        </a>
                        <a href="edit_etudiant.php?id=<?php echo $etudiant['id'];?>" class="text-primary">
                            <i class="fas fa-edit fa-lg"></i>
                        </a>
                    </td>
                </tr> 
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script src="node_modules/datatables.net/js/dataTables.min.js"></script>
    <script src="node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="node_modules/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>

    <script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            var table = $('#tableEtudiants').DataTable({
                dom: 'Brtip', 
                pageLength: 5,
                lengthChange: false,
                buttons: [
                    { extend: 'copy', className: 'btn btn-light border btn-sm' },
                    { extend: 'excel', className: 'btn btn-light border btn-sm' },
                    { extend: 'csv', className: 'btn btn-light border btn-sm' },
                    { extend: 'pdf', className: 'btn btn-light border btn-sm' }
                ]
            });
            $('#btnFiltrer').on('click', function() {
                table.search($('#customSearch').val()).draw();
            });
        }
    });
    </script>
</body>
</html>