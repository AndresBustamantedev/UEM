<?php
require_once 'config/db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $correo = trim($_POST['correo']);
    $clave = $_POST['clave'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validaciones básicas
    if (empty($nombres) || empty($correo) || empty($clave) || empty($tipo_usuario)) {
        $message = "Por favor complete todos los campos.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT usuario_id FROM usuario WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        
        if ($stmt->rowCount() > 0) {
            $message = "El correo electrónico ya está registrado.";
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nombres, correo, clave, tipo_usuario) VALUES (:nombres, :correo, :clave, :tipo_usuario)";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute(['nombres' => $nombres, 'correo' => $correo, 'clave' => $hashed_password, 'tipo_usuario' => $tipo_usuario])) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $message = "Error al registrar. Inténtelo de nuevo.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="card" style="max-width: 500px; margin: 20px auto;">
    <h2>Registro de Usuario</h2>
    <?php if($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form action="register.php" method="POST">
        <label for="nombres">Nombre Completo:</label>
        <input type="text" name="nombres" required>
        
        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required>
        
        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" required>
        
        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select name="tipo_usuario" required>
            <option value="comprador">Comprador</option>
            <option value="vendedor">Vendedor</option>
        </select>
        
        <br><br>
        <button type="submit" class="button">Registrarse</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</div>

<?php include 'includes/footer.php'; ?>
