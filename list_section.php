<?php
include 'auth_check.php';
include 'autoloader.php';
$pdo = ConnexionDB::getInstance();

try {
  $query = $pdo->query("SELECT * from section");
  $sections = $query->fetchAll();
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
      <h3 class="text-muted m-0" style="font-size: 1.2rem;">Liste des sections</h3>
    </div>

    <table id="tableSections" class="table table-striped border">
      <thead>
        <tr>
          <th>id</th>
          <th>designation</th>
          <th>description</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sections as $section) : ?>
          <tr>
            <td><?php echo htmlspecialchars($section['id']); ?></td>
            <td><?php echo htmlspecialchars($section['designation']); ?></td>
            <td><?php echo htmlspecialchars($section['description']); ?></td>
            <td class="text-center">
              <a href="list_etudiant.php?section_id=<?php echo $section['id']; ?>" class="text-primary">
    <i class="fa-solid fa-list-ol"></i>
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
        var table = $('#tableSections').DataTable({
          dom: 'Brtip',
          pageLength: 5,
          lengthChange: false,
          buttons: [{
              extend: 'copy',
              className: 'btn btn-light border btn-sm'
            },
            {
              extend: 'excel',
              className: 'btn btn-light border btn-sm'
            },
            {
              extend: 'csv',
              className: 'btn btn-light border btn-sm'
            },
            {
              extend: 'pdf',
              className: 'btn btn-light border btn-sm'
            }
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
