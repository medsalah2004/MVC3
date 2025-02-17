<?php
session_start();
include("../../controleur/clientcontroleur.php");
$clientController = new ClientControleur();
$list = $clientController->listclient();
$userStats = $clientController->getUserRegistrationStats();
$totalUsers = $clientController->getTotalUsers();
$totalAdmins = $clientController->getTotalAdmins();
$totalClients = $clientController->getTotalClients();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DarkPan - Backoffice</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <style>
        /* Your existing styles here */
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Template Stylesheet -->
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/animate.css">
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
    
        <div class="content">
    <!-- Stats Cards -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <!-- Total Users -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <div class="stat-card-title">Total Users</div>
                        <div class="stat-card-value"><?php echo $totalUsers; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- New Users Today -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <div class="stat-card-title">New Users Today</div>
                        <div class="stat-card-value">
                            <?php echo end($userStats)['count'] ?? 0; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Clients -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <div class="stat-card-title">Total Clients</div>
                        <div class="stat-card-value"><?php echo $clientController->getTotalClients(); ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <div class="stat-card-title">Total Admins</div>
                        <div class="stat-card-value"><?php echo $clientController->getTotalAdmins(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

<!-- Users Table -->
        <div class="table-container">
            <div class="chart-header">
                <h4 class="chart-title">Liste des Utilisateurs</h4>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>CIN</th>
                        <th>Date Naissance</th>
                        <th>password</th>
                        <th>confirmer_password</th>
                        <th>Role</th>
                        <th>Date Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id']); ?></td>
                        <td><?= htmlspecialchars($client['nom']); ?></td>
                        <td><?= htmlspecialchars($client['prenom']); ?></td>
                        <td><?= htmlspecialchars($client['email']); ?></td>
                        <td><?= htmlspecialchars($client['cin']); ?></td>
                        <td><?= htmlspecialchars($client['date_naissance']); ?></td>
                        <td><?= htmlspecialchars($client['password']); ?></td>
                        <td><?= htmlspecialchars($client['confirm_password']); ?></td>
                        <td><?= htmlspecialchars($client['role']); ?></td>
                        <td><?= htmlspecialchars($client['date_inscription']); ?></td>
                        <td>
                            <form method="POST" action="modifierutulisateur.php" style="display: inline;">
                                <input type="hidden" value="<?= htmlspecialchars($client['id']); ?>" name="id">
                                <button type="submit" name="update" class="btn-action btn-update">Update</button>
                            </form>
                            <a href="deleteutulisateur.php?id=<?= htmlspecialchars($client['id']); ?>" class="btn-action btn-delete">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>