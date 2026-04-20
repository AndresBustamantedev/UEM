<?php
require_once 'config/db.php';

echo "<h1>Restablecer Contraseña de Administrador</h1>";

$email = 'admin@inmobiliaria.com';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Verificar si el admin existe
    $stmt = $pdo->prepare("SELECT usuario_id FROM usuario WHERE correo = :correo");
    $stmt->execute(['correo' => $email]);
    
    if ($stmt->rowCount() > 0) {
        // Actualizar contraseña
        $sql = "UPDATE usuario SET clave = :clave WHERE correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['clave' => $hash, 'correo' => $email]);
        echo "<p style='color:green'>✅ Contraseña actualizada correctamente.</p>";
    } else {
        // Crear usuario admin si no existe
        $sql = "INSERT INTO usuario (nombres, correo, clave, tipo_usuario) VALUES (:nombres, :correo, :clave, :tipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombres' => 'Administrador',
            'correo' => $email,
            'clave' => $hash,
            'tipo' => 'admin'
        ]);
        echo "<p style='color:green'>✅ Usuario Administrador creado correctamente.</p>";
    }
    
    echo "<p><strong>Credenciales:</strong></p>";
    echo "<ul>";
    echo "<li>Correo: <strong>$email</strong></li>";
    echo "<li>Clave: <strong>$password</strong></li>";
    echo "</ul>";
    echo "<p><a href='login.php'>Ir al Login</a></p>";
    
} catch (PDOException $e) {
    die("<p style='color:red'>Error: " . $e->getMessage() . "</p>");
}
?>
