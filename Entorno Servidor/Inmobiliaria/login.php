<?php
require_once 'config/db.php';
$message = '';

if (isset($_GET['registered'])) {
    $message = "Registro exitoso. Por favor inicie sesión.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $clave = $_POST['clave'];

    if (empty($correo) || empty($clave)) {
        $message = "Por favor ingrese correo y contraseña.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($clave, $user['clave'])) {
            session_start();
            $_SESSION['usuario_id'] = $user['usuario_id'];
            $_SESSION['nombres'] = $user['nombres'];
            $_SESSION['correo'] = $user['correo'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

            // Redirección basada en el rol
            if ($user['tipo_usuario'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $message = "Credenciales incorrectas.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="card" style="max-width: 500px; margin: 20px auto;">
    <h2>Iniciar Sesión</h2>
    <?php if($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form action="login.php" method="POST">
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required>
        
        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" required>
        
        <br><br>
        <button type="submit" class="button">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</div>

<?php include 'includes/footer.php'; ?>
