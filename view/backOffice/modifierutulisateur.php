<?php
include("../../controleur/clientcontroleur.php");

// CSRF Token

$client = null;
$clientController = new ClientControleur();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification du CSRF Token
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['cin'])
        && !empty($_POST['date_naissance']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['role'])) {
        
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "Adresse email invalide.";
        } elseif (strlen($_POST['cin']) != 8 || !ctype_digit($_POST['cin'])) {
            $error = "Le CIN doit être composé de 8 chiffres.";
        } elseif (strlen($_POST['password']) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        // Vérification que les mots de passe correspondent
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Les mots de passe ne correspondent pas.";
        }

        try {
            $date_naissance = new DateTime($_POST['date_naissance']);
        } catch (Exception $e) {
            $error = "La date de naissance est invalide : " . $e->getMessage();
        }

        if (empty($error)) { // Vérifie qu'il n'y a pas d'erreurs avant de continuer
            // Hachage du mot de passe
            // Enregistrer le client
            $client = new client(
                null, // L'ID peut être nul car c'est auto-incrémenté en base
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['cin'],
                new DateTime($_POST['date_naissance']),
                $_POST['password'],  // Hachage du mot de passe
                $_POST['confirm_password'], 
                $_POST['role']
            );

            // Mise à jour du client avec l'ID
            $clientController->updateclient($client, $_POST['id']);

            // Message de succès
            $success = "Le client a été modifié avec succès!";
            // Redirection après ajout
            header('Location:signup2.php');
            exit; // Assurez-vous d'arrêter l'exécution après la redirection
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <nom>Backoffice - Gestion Utilisateurs</nom>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="role.js"></script>
    <script src="modifylink.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- CSS ici -->
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
                    <h6 class="mb-0">mohamed_salah_ammiche </h6>
                    <span>Admin</span>
                </div>
            </div>
            <div class="navbar-nav w-100">
                <a href="index.html" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="signup2.php" class="nav-item nav-link active"><i class="fa fa-laptop me-2"></i>Gestion utilisateurs</a>
                <a href="#" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i><p>Paramètre</p></a>
                <a href="ajoutuser.html" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Ajouter un utilisateur</a>
                <a href="signup.php" class="nav-item nav-link"><i class="far fa-file-alt me-2"></i>Déconnexion</a>
            </div>
        </nav>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Backoffice</a>
        </nav>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Modification du client avec ID = <?php echo $_POST['id'] ?> </h1>
        </div>
        
        <?php
        if (isset($_POST['id'])) {
            $client = $clientController->showclient($_POST['id']);
        ?>
        <form id="createaccount-form" action="" method="POST">
            <label for="id">ID du client:</label><br>
            <input class="form-control form-control-user" type="text" id="id" name="id" readonly value="<?php echo $_POST['id'] ?>">
            
            <label for="nom">Nom:</label><br>
            <input class="form-control form-control-user" type="text" id="nom" name="nom" value="<?php echo $client['nom'] ?>" >
            <span id="nom_error"></span><br>
            
            <label for="prenom">Prénom:</label><br>
            <input class="form-control form-control-user" type="text" id="prenom" name="prenom" value="<?php echo $client['prenom'] ?>" >
            <span id="prenom_error"></span><br>

            <label for="email">Email:</label><br>
            <input class="form-control form-control-user" type="text" id="email" name="email" value="<?php echo $client['email'] ?>" >
            <span id="email_error"></span><br>

            <label for="cin">CIN:</label><br>
            <input class="form-control form-control-user" type="text" id="cin" name="cin" value="<?php echo $client['cin'] ?>">
            <span id="cin_error"></span><br>

            <label for="date_naissance">Date de naissance:</label><br>
            <input class="form-control form-control-user" type="date" id="date_naissance" name="date_naissance" value="<?php echo $client['date_naissance'] ?>">
            <span id="date_naissance_error"></span><br>

            <label for="password">Mot de passe:</label><br>
            <input class="form-control form-control-user" type="text" id="password" name="password" value="<?php echo $client['password'] ?>">
            <span id="password_error"></span><br>

            <label for="confirm_password">Confirmation du mot de passe:</label><br>
            <input class="form-control form-control-user" type="text" id="confirm_password" name="confirm_password" value="<?php echo $client['confirm_password'] ?>">
            <span id="confirm_password_error"></span><br>

            <label for="role">Rôle:</label><br>
            <select class="form-control form-control-user" id="role" name="role">
                <option value="client" <?php echo ($client['role'] == 'client') ? 'selected' : ''; ?>>Client</option>
                <option value="admin" <?php echo ($client['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="guide" <?php echo ($client['role'] == 'guide') ? 'selected' : ''; ?>>Guide</option>
            </select><br>

            <input class="btn btn-primary btn-user btn-block" type="submit" name="submit" value="Modifier">
        </form>
        <?php } ?>
    </div>
</body>
</html>
