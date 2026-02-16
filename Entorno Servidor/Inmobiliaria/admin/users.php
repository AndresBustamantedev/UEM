<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM usuario WHERE usuario_id = :id");
    $stmt->execute(['id' => $id]);
    $message = "Usuario eliminado.";
}

// Handle Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM usuario WHERE names LIKE :search OR correo LIKE :search"; // Oops 'nombres' not 'names'
$sql = "SELECT * FROM usuario WHERE nombres LIKE :search OR correo LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding"><h1>Admin Panel</h1></div>
            <nav><ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="users.php">Usuarios</a></li>
                <li><a href="pisos.php">Pisos</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul></nav>
        </div>
    </header>

    <div class="container">
        <h1>Usuarios</h1>
        <?php if(isset($message)) echo "<div class='alert success'>$message</div>"; ?>
        
        <div style="margin-bottom: 20px;">
            <a href="user_form.php" class="button">Nuevo Usuario</a>
            <form action="users.php" method="GET" style="display:inline; float:right;">
                <input type="text" name="search" placeholder="Buscar..." value="<?php echo htmlspecialchars($search); ?>" style="width: auto; display: inline;">
                <button type="submit" class="button">Buscar</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['usuario_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($user['correo']); ?></td>
                    <td><?php echo htmlspecialchars($user['tipo_usuario']); ?></td>
                    <td>
                        <a href="user_form.php?id=<?php echo $user['usuario_id']; ?>" class="button" style="background:#333;">Editar</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro?');">
                            <input type="hidden" name="delete_id" value="<?php echo $user['usuario_id']; ?>">
                            <button type="submit" class="button" style="background:#f44336;">Borrar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
