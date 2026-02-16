<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$user = [
    'usuario_id' => '',
    'nombres' => '',
    'correo' => '',
    'tipo_usuario' => 'comprador'
];
$is_editing = false;
$message = '';

if (isset($_GET['id'])) {
    $is_editing = true;
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) die("Usuario no encontrado");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $clave = $_POST['clave'];

    if ($is_editing) {
        $sql = "UPDATE usuario SET nombres = :nombres, correo = :correo, tipo_usuario = :tipo_usuario WHERE usuario_id = :id";
        $params = [
            'nombres' => $nombres,
            'correo' => $correo,
            'tipo_usuario' => $tipo_usuario,
            'id' => $_POST['usuario_id']
        ];
        
        if (!empty($clave)) {
            $sql = "UPDATE usuario SET nombres = :nombres, correo = :correo, tipo_usuario = :tipo_usuario, clave = :clave WHERE usuario_id = :id";
            $params['clave'] = password_hash($clave, PASSWORD_DEFAULT);
        }
        
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            header("Location: users.php");
            exit;
        }
    } else {
        // Create
        if (empty($clave)) {
            $message = "La contraseña es obligatoria para nuevos usuarios.";
        } else {
            $sql = "INSERT INTO usuario (nombres, correo, clave, tipo_usuario) VALUES (:nombres, :correo, :clave, :tipo_usuario)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([
                'nombres' => $nombres,
                'correo' => $correo,
                'clave' => password_hash($clave, PASSWORD_DEFAULT),
                'tipo_usuario' => $tipo_usuario
            ])) {
                header("Location: users.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_editing ? 'Editar' : 'Crear'; ?> Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $is_editing ? 'Editar' : 'Crear'; ?> Usuario</h1>
        <?php if($message) echo "<div class='alert'>$message</div>"; ?>
        
        <form method="POST" class="card">
            <input type="hidden" name="usuario_id" value="<?php echo $user['usuario_id']; ?>">
            
            <label>Nombre:</label>
            <input type="text" name="nombres" value="<?php echo htmlspecialchars($user['nombres']); ?>" required>
            
            <label>Correo:</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
            
            <label>Tipo Usuario:</label>
            <select name="tipo_usuario">
                <option value="comprador" <?php echo $user['tipo_usuario'] == 'comprador' ? 'selected' : ''; ?>>Comprador</option>
                <option value="vendedor" <?php echo $user['tipo_usuario'] == 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                <option value="admin" <?php echo $user['tipo_usuario'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            
            <label>Contraseña <?php echo $is_editing ? '(Dejar en blanco para no cambiar)' : ''; ?>:</label>
            <input type="password" name="clave" <?php echo $is_editing ? '' : 'required'; ?>>
            
            <br><br>
            <button type="submit" class="button">Guardar</button>
            <a href="users.php" class="button" style="background:#666;">Cancelar</a>
        </form>
    </div>
</body>
</html>
