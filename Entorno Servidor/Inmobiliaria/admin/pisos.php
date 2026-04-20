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
    // Delete image file if exists
    $stmt = $pdo->prepare("SELECT imagen FROM pisos WHERE Codigo_piso = :id");
    $stmt->execute(['id' => $id]);
    $piso = $stmt->fetch();
    if ($piso && $piso['imagen']) {
        @unlink("../uploads/" . $piso['imagen']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM pisos WHERE Codigo_piso = :id");
    $stmt->execute(['id' => $id]);
    $message = "Piso eliminado.";
}

// Handle Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT p.*, u.nombres as vendedor FROM pisos p LEFT JOIN usuario u ON p.usuario_id = u.usuario_id WHERE calle LIKE :search OR zona LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$pisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pisos</title>
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
        <h1>Pisos</h1>
        <?php if(isset($message)) echo "<div class='alert success'>$message</div>"; ?>
        
        <div style="margin-bottom: 20px;">
            <a href="piso_form.php" class="button">Nuevo Piso</a>
            <form action="pisos.php" method="GET" style="display:inline; float:right;">
                <input type="text" name="search" placeholder="Buscar..." value="<?php echo htmlspecialchars($search); ?>" style="width: auto; display: inline;">
                <button type="submit" class="button">Buscar</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Calle</th>
                    <th>Zona</th>
                    <th>Precio</th>
                    <th>Vendedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pisos as $piso): ?>
                <tr>
                    <td><?php echo $piso['Codigo_piso']; ?></td>
                    <td><?php echo htmlspecialchars($piso['calle'] . ' ' . $piso['numero']); ?></td>
                    <td><?php echo htmlspecialchars($piso['zona']); ?></td>
                    <td><?php echo number_format($piso['precio'], 2); ?> €</td>
                    <td><?php echo htmlspecialchars($piso['vendedor']); ?></td>
                    <td>
                        <a href="piso_form.php?id=<?php echo $piso['Codigo_piso']; ?>" class="button" style="background:#333;">Editar</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro?');">
                            <input type="hidden" name="delete_id" value="<?php echo $piso['Codigo_piso']; ?>">
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
