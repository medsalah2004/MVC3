<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
session_start(); // Start the session at the beginning
include("../../controleur/clientcontroleur.php");

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$clientController = new ClientControleur();
$error = "";
$success = "";
$requestMethod = php_sapi_name() === 'cli' ? 'CLI' : $_SERVER["REQUEST_METHOD"];

if ($requestMethod === 'POST') {
    // Vérification du CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch.');
    }

    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        if (strlen($_POST['password']) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            // Assuming you have a user ID in the session
            $userId = $_SESSION['id'] ?? null;
            if ($userId) {
                // Hachage du mot de passe
                $Password = $_POST['password'];
                $Password_confirm = $_POST['confirm_password'];
                // Update the user's password
                $clientController->updatePassword($userId, $Password);
                $clientController->updateconfirmPassword($userId, $Password_confirm);
                if (sendPasswordResetNotification($userId)) {
                $success = "Le mot de passe a été modifié avec succès!";
                
                // Redirect after successful password change
                header('Location: signup.php');
                exit;
                }
            } else {
                $error = "Session utilisateur invalide.";
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
function sendPasswordResetNotification($userId) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ammichemedsalah2004@gmail.com'; // Replace with your email
        $mail->Password   = 'mxsx wjjx xuhk tbjx'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'Travelo Support');
        $mail->addAddress('ammichemedsalah2004@gmail.com', 'Recipient Name');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Notification';
        $mail->Body    = "The user with ID <strong>$userId</strong> has successfully changed their password.";
        $mail->AltBody = "The user with ID $userId has successfully changed their password.";

        $mail->send();
        error_log("Password reset email sent successfully for user ID: $userId");
        return true;
    } catch (Exception $e) {
        error_log("Failed to send password reset email. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialisation du mot de passe - Travelo</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
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
            <div class="row justify-content-center">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Réinitialisation du mot de passe</h2>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return validateForm();">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe:</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                    <p id="passwordError" class="error-message"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le mot de passe:</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                    <p id="confirmPasswordError" class="error-message"></p>
                                </div>
                                <section id="buttonContainer">
                                    <center>
                                <button type="submit" class="button">Réinitialiser le mot de passe</button>
                            </center>
                            </section>
                            </form>
                            <footer class="mt-5">
                            <div class="container">
                            <p class="text-center">Travelo © 2024</p>
                            </div>
                            </footer>
                        </div>
                    </div>
                </div>
       
    </div>

   

    <script>
    function validateForm() {
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm_password");
        let isValid = true;

        // Reset error messages
        document.getElementById("passwordError").textContent = "";
        document.getElementById("confirmPasswordError").textContent = "";

        if (password.value.length < 6) {
            document.getElementById("passwordError").textContent = "Le mot de passe doit contenir au moins 6 caractères.";
            isValid = false;
        }

        if (password.value !== confirmPassword.value) {
            document.getElementById("confirmPasswordError").textContent = "Les mots de passe ne correspondent pas.";
            isValid = false;
        }

        return isValid;
    }
    </script>
</body>
</html>

