<?php
include("../../controleur/clientcontroleur.php");
session_start();

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$client = null;
$clientController = new ClientControleur();
$error = "";
$success = "";

// reCAPTCHA secret key
$recaptcha_site_key = '6Le5ho8qAAAAAEoMoNNyxWMTepTSpzWVeJGgii3O';
$recaptcha_secret_key = '6Le5ho8qAAAAAM6hR-3PmkVdeK8IwxvOsgTlvl86';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du CSRF Token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch.');
    }

    // Vérification du reCAPTCHA
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptcha_secret_key . '&response=' . $recaptcha_response);
    $response_data = json_decode($verify_response);

    if (!$response_data->success) {
        $error = "La vérification reCAPTCHA a échoué. Veuillez réessayer.";
    } else if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['cin'])
        && !empty($_POST['date_naissance']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['role'])) {
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "Adresse email invalide.";
        } elseif (strlen($_POST['cin']) != 8 || !ctype_digit($_POST['cin'])) {
            $error = "Le CIN doit être composé de 8 chiffres.";
        } elseif (strlen($_POST['password']) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        try {
            $date_naissance = new DateTime($_POST['date_naissance']);
        } catch (Exception $e) {
            $error = "La date de naissance est invalide : " . $e->getMessage();
        }

        if (empty($error)) {
            $client = new client(
                null,
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['cin'],
                new DateTime($_POST['date_naissance']),
                $_POST['password'],
                $_POST['confirm_password'], 
                $_POST['role']
            );

            $clientController->addclient($client);

            $success = "Client ajouté avec succès!";
            switch ($_POST['role']) {
                case "client":
                    header('Location: index.php');
                    break;
                case "admin":
                    header('Location: admin.html'); 
                    break;
                case "guide":
                    header('Location: guide.html');
                    break;
            }
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Travelo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="role.js"></script>
    <script src="modifylink.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">

    <!-- CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/gijgo.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slicknav.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Add reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
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
                                        <li><a href="travel_destination.html">Destination</a></li>
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
                                    <p> <i class="fa fa-phone"></i> (+216)28-770-340</p>
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

<div class="main-content">
    <div class="image-container">
        <img src="téléchargé.jfif" alt="Tunisian Monument">
    </div>

    <div class="form-container">
        <center>
            <section id="signup" class="title">
                <h2 class="title">Create Account</h2>
            </section>
            <form id="createaccount-form" method="POST" action="">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" >
                <p id="nomError" class="error-message"></p>

                <label for="prenom">Prénom:</label>
                <input type="text" name="prenom" id="prenom" >
                <p id="prenomError" class="error-message"></p>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" >
                <p id="emailError" class="error-message"></p>

                <label for="cin">CIN:</label>
                <input type="text" name="cin" id="cin" >
                <p id="cinError" class="error-message"></p>

                <label for="mdp1">Password:</label>
                <input type="password" name="password" id="mdp1" >
                <p id="passwordError" class="error-message"></p>

                <label for="mdp2">Confirm Password:</label>
                <input type="password" name="confirm_password" id="mdp2" >
                <p id="confirmPasswordError" class="error-message"></p>

                <label for="DateN">Date de naissance:</label>
                <input type="date" name="date_naissance" id="DateN" >
                <p id="dateNaissanceError" class="error-message"></p>

                <label for="roleSelect">Sélectionnez votre rôle:</label>
                <select name="role" id="roleSelect" >
                    <option value="" disabled selected>-- Sélectionner un rôle --</option>
                    <option value="client">Client</option>
                    <option value="admin">Admin</option>
                    <option value="guide">Guide</option>
                </select>
                <p id="roleError" class="error-message"></p>

                <!-- Add reCAPTCHA widget -->
                <div class="g-recaptcha" data-sitekey="6Le5ho8qAAAAAEoMoNNyxWMTepTSpzWVeJGgii3O"></div>
                <p id="recaptchaError" class="error-message"></p>

                <section id="buttonContainer">
                    <button type="submit" class="button">Créer un compte</button>
                </section>
            </form>
        </center>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createaccount-form');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        clearErrors();
        
        let isValid = true;
        
        // Validate Nom
        const nom = document.getElementById('nom');
        if (!nom.value.trim()) {
            showError('nomError', "Le nom est obligatoire.");
            isValid = false;
        }
        
        // Validate Prénom
        const prenom = document.getElementById('prenom');
        if (!prenom.value.trim()) {
            showError('prenomError', "Le prénom est obligatoire.");
            isValid = false;
        }
        
        // Validate Email
        const email = document.getElementById('email');
        if (!isValidEmail(email.value)) {
            showError('emailError', "Adresse email invalide.");
            isValid = false;
        }
        
        // Validate CIN
        const cin = document.getElementById('cin');
        if (cin.value.length !== 8 || !/^\d+$/.test(cin.value)) {
            showError('cinError', "Le CIN doit contenir exactement 8 chiffres.");
            isValid = false;
        }
        
        // Validate Password
        const password = document.getElementById('mdp1');
        if (password.value.length < 6) {
            showError('passwordError', "Le mot de passe doit contenir au moins 6 caractères.");
            isValid = false;
        }
        
        // Validate Confirm Password
        const confirmPassword = document.getElementById('mdp2');
        if (password.value !== confirmPassword.value) {
            showError('confirmPasswordError', "Les mots de passe ne correspondent pas.");
            isValid = false;
        }
        
        // Validate Date de naissance
        const dateNaissance = document.getElementById('DateN');
        if (!dateNaissance.value) {
            showError('dateNaissanceError', "La date de naissance est obligatoire.");
            isValid = false;
        }
        
        // Validate Role
        const role = document.getElementById('roleSelect');
        if (!role.value) {
            showError('roleError', "Veuillez sélectionner un rôle.");
            isValid = false;
        }
        
        // Check reCAPTCHA
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            showError('recaptchaError', "Veuillez compléter le reCAPTCHA.");
            isValid = false;
        }
        
        if (isValid) {
            form.submit();
        }
    });
    
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
    }
    
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(function(error) {
            error.textContent = '';
        });
    }
    
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email.trim());
    }
});
</script>
</body>
</html>