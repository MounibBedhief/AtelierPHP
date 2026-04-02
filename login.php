<?php
include 'autoloader.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: list_etudiant.php');
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $pdo = ConnexionDB::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: list_etudiant.php');
                exit();
            } else {
                $error = "Identifiants incorrects. Veuillez réessayer.";
            }
        } catch (Exception $e) {
            $error = "Une erreur est survenue lors de la connexion.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Student Management System</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';">
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-card">
    <div class="card-header">
        <h2>Login</h2>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </div>
        </form>
    </div>
</div>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';"></script>
</body>
</html>
