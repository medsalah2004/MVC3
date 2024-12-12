<?php
include("../../controleur/clientcontroleur.php");

session_start();

// Check if user is logged in, if not redirect to signup
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header('Location: signup.php');
    exit();
}
// Check if client is logged in
if (!isset($_SESSION['id'])) {
    header('Location: profile.php');
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
<header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="index.html">
                                        <img src="img/logo.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a class="active" href="index.php">home</a></li>
                                            <li><a href="about.html">About</a></li>
                                            <li><a class="" href="travel_destination.html">Destination</a></li>
                                            <li><a href="#">pages <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
                                                    <li><a href="destination_details.html">Destinations details</a></li>
                                                    <li><a href="elements.html">elements</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">blog <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
                                                    <li><a href="blog.html">blog</a></li>
                                                    <li><a href="single-blog.html">single-blog</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="contact.html">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 d-none d-lg-block">
                                <div class="social_wrap d-flex align-items-center justify-content-end">
                                    <div class="number">
                                        <p> <i class="fa fa-phone"></i> 10(256)-928 256</p>
                                    </div>
                                    <div class="social_links d-none d-xl-block">
                                        <ul>
                                            <li><a href="#"> <i class="fa fa-instagram"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-linkedin"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-facebook"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-google-plus"></i> </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="seach_icon">
                                <a data-toggle="modal" data-target="#exampleModalCenter" href="#">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
<div class="container mt-5">
    <h1 class="h3 mb-4">Profil Utilisateur</h1>
    
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
                <button type="submit" class="btn btn-success btn-lg">Enregistrer les modifications</button>
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