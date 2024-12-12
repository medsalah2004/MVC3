<?php
include("../../controleur/clientcontroleur.php");

session_start();

// Check if user is logged in, if not redirect to signup
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header('Location: signup2.php');
    exit();
}
// Check if client is logged in
if (!isset($_SESSION['id'])) {
    header('Location: profile2.php');
    exit();
}

$clientController = new ClientControleur();
$error = "";
$success = "";

// Fetch client data
$client = $clientController->showclient($_SESSION['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $date_naissance = filter_input(INPUT_POST, 'date_naissance', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // We'll hash this later
    $password = $_POST['confirm_password']; // We'll hash this later
    // Perform validation
    if (!$email) {
        $error = "Adresse email invalide.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        try {
            $date_naissance = new DateTime($date_naissance);
        } catch (Exception $e) {
            $error = "La date de naissance est invalide : " . $e->getMessage();
        }
    }

    if (empty($error)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update client information
        $client = new client(
            $_SESSION['id'],
            $nom,
            $prenom,
            $email,
            $client['cin'], // Assuming CIN doesn't change
            $date_naissance,
            $_POST['password'],  // Hachage du mot de passe
            $_POST['confirm_password'],  // confirm_password is not needed here
            $client['role'] // Assuming role doesn't change
        );

        $clientController->updateclient($client, $_SESSION['id']);

        $success = "Vos informations ont été mises à jour avec succès!";
        
        // Refresh client data
        $client = $clientController->showclient($_SESSION['id']);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>DarkPan</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                    <?php if (isset($_SESSION['name'])): ?>
                         <a href="profile2.php" class="mb-0">Profil: <?php echo ($_SESSION['name']); ?></a>
                        <span>Admin</span>
                        <?php else: 
                            header('Location: http://localhost/MVC3/view/frontOffice2/signup.php');
                            ?>
                        <?php endif; ?>
                        <p style="padding-left: 20px;">  </p>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Elements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="button.html" class="dropdown-item">Buttons</a>
                            <a href="typography.html" class="dropdown-item">Typography</a>
                            <a href="element.html" class="dropdown-item">Other Elements</a>
                        </div>
                    </div>
                    <a href="signup2.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>gestion user</a>
                    <a href="widget.html" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets</a>
                    <a href="form.html" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
                    <a href="table.html" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables</a>
                    <a href="chart.html" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="signin.html" class="dropdown-item">Sign In</a>
                            <a href="signup.php" class="dropdown-item">Sign Up</a>
                            <a href="404.html" class="dropdown-item">404 Error</a>
                            <a href="blank.html" class="dropdown-item">Blank Page</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
<div class="container mt-5">
    <h1 class="h3 mb-4">Profil Admin</h1>
    
    <form id="profileForm" method="POST" class="needs-validation" novalidate>
        <div class="row mb-3">
            <label for="nom" class="col-sm-2 col-form-label">Nom :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" readonly>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="toggleEdit('nom')">Modifier</button>
            </div>
        </div>

        <div class="row mb-3">
            <label for="prenom" class="col-sm-2 col-form-label">Prénom :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" readonly>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="toggleEdit('prenom')">Modifier</button>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-sm-2 col-form-label">Mot de Passe :</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($client['password']); ?>" readonly>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="toggleEdit('password')">Modifier</button>
            </div>
        </div>
        
        <div class="row mb-3">
            <label for="confirm_password" class="col-sm-2 col-form-label">confirmer le Mot de Passe :</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?php echo htmlspecialchars($client['confirm_password']); ?>" readonly>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="toggleEdit('confirm_password')">Modifier</button>
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-sm-2 col-form-label">Email :</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" readonly>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="toggleEdit('email')">Modifier</button>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary btn-lg">Enregistrer les modifications</button>
            </div>
        </div>
    </form>
</div>
<script>
function toggleEdit(fieldId) {
    const field = document.getElementById(fieldId);
    const wasReadonly = field.readOnly;
    field.readOnly = !wasReadonly;
    
    if (!field.readOnly) {
        field.focus();
        if (fieldId === 'password') {
            field.value = ''; // Clear password field when editing
        }
    }
    
    // Toggle background color
    if (field.readOnly) {
        field.style.backgroundColor = '#f8f9fa';
    } else {
        field.style.backgroundColor = '#ffffff';
    }
}

document.getElementById('profileForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password');
    if (password.value === '********') {
        password.disabled = true; // Disable password field if not modified
    }
});
</script>
</body>
</html>