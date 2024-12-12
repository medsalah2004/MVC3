<?php
include '../../config.php';

// Démarrage de la session
session_start();

// Connexion à la base de données
$pdo = config::getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $_SESSION['error_message'] = 'Tous les champs doivent être remplis.';
        header('Location: forgetpassword.php');
        exit();
    }
    try {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$client) {
            $_SESSION['error_message'] = 'Email incorrect';
            header('Location: forgetpassword.php');
            exit();
        }
        $_SESSION['id'] = $client['id'];
        $_SESSION['email'] = $client['email'];
        if ($client['role'] === 'Admin'||$client['role'] === 'Client') {
            header('Location: forgetpassword2.php');
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la connexion : " . $e->getMessage());
        $_SESSION['error_message'] = 'Erreur lors de la connexion. Veuillez réessayer plus tard.';
        header('Location: forgetpassword.php');
        exit();
    }
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Travelo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <div class="main-content">
        <div class="image-container">
            <img src="téléchargé.jfif" alt="Tunisian Monument">
        </div>
        <div class="form-container">
            <section id="signup" class="signup-section">
                <center>
                    <h2>mot de passe oubliée</h2>
                </center>
                </section>
                <form method="POST" onsubmit="return validerFormulaire();">
                <label>Email :</label><br>
                <input type="email" id="email" name="email" placeholder="contact@exemple.com"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>"><br><br>
                <p id="id2"></p>
                <section id="buttonContainer">
                <button type="submit" class="button">Sign In</button>
                </section>
                <?php if (!empty($_SESSION['error_message'])): ?>
                    <p style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
                <?php endif; ?>
            </form>
            <footer>
        <p>Travelo © 2024</p>
    </footer>
        </div>
       
    </div>
    <script>
        function validerFormulaire() {
            const email = document.getElementById("email").value.trim();

            if (email === "") {
                alert("l'email doit être remplis.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
