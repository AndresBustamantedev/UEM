<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Admin Panel</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../index.php">Ver Web</a></li>
                    <li><a href="users.php">Usuarios</a></li>
                    <li><a href="pisos.php">Pisos</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Bienvenido Administrador</h1>
        <div class="card">
            <h3>Gestión Rápida</h3>
            <p>Seleccione una opción del menú para administrar usuarios o pisos.</p>
            <a href="users.php" class="button">Gestionar Usuarios</a>
            <a href="pisos.php" class="button">Gestionar Pisos</a>
        </div>
    </div>
    
    <footer>
        <p>Inmobiliaria Admin &copy; <?php echo date("Y"); ?></p>
    </footer>
</body>
</html>
