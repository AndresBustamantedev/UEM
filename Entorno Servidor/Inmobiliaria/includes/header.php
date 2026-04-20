<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determinar la ruta base relativa
$base_path = '';
$current_dir = basename(getcwd());
if ($current_dir == 'admin' || $current_dir == 'user') {
    $base_path = '../';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inmobiliaria</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">Inmo</span>biliaria</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo $base_path; ?>index.php">Inicio</a></li>
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <?php if($_SESSION['tipo_usuario'] == 'admin'): ?>
                            <li><a href="<?php echo $base_path; ?>admin/users.php">Usuarios</a></li>
                            <li><a href="<?php echo $base_path; ?>admin/pisos.php">Pisos</a></li>
                        <?php elseif($_SESSION['tipo_usuario'] == 'vendedor'): ?>
                            <li><a href="<?php echo $base_path; ?>user/create_piso.php">Publicar Piso</a></li>
                            <li><a href="<?php echo $base_path; ?>user/my_pisos.php">Mis Pisos</a></li>
                        <?php elseif($_SESSION['tipo_usuario'] == 'comprador'): ?>
                            <!-- Opciones para comprador -->
                        <?php endif; ?>
                        <li><a href="<?php echo $base_path; ?>logout.php">Logout (<?php echo $_SESSION['nombres']; ?>)</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $base_path; ?>login.php">Login</a></li>
                        <li><a href="<?php echo $base_path; ?>register.php">Registro</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
