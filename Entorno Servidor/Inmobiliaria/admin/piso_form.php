<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$piso = [
    'Codigo_piso' => '', 'calle' => '', 'numero' => '', 'piso' => '', 'puerta' => '',
    'cp' => '', 'metros' => '', 'zona' => '', 'precio' => '', 'imagen' => '', 'usuario_id' => ''
];
$is_editing = false;
$message = '';

// Fetch users for dropdown
$users_stmt = $pdo->query("SELECT usuario_id, nombres, correo FROM usuario WHERE tipo_usuario IN ('admin', 'vendedor')");
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $is_editing = true;
    $stmt = $pdo->prepare("SELECT * FROM pisos WHERE Codigo_piso = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $piso = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$piso) die("Piso no encontrado");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $calle = $_POST['calle'];
    $numero = $_POST['numero'];
    $piso_num = $_POST['piso'];
    $puerta = $_POST['puerta'];
    $cp = $_POST['cp'];
    $metros = $_POST['metros'];
    $zona = $_POST['zona'];
    $precio = $_POST['precio'];
    $usuario_id = $_POST['usuario_id'];
    
    // Image Upload
    $imagen = $piso['imagen']; // Keep old image by default
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $target_dir = "../uploads/";
        $filename = time() . "_" . basename($_FILES["imagen"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $imagen = $filename;
        } else {
            $message = "Error al subir la imagen.";
        }
    }

    if ($is_editing) {
        $sql = "UPDATE pisos SET calle=?, numero=?, piso=?, puerta=?, cp=?, metros=?, zona=?, precio=?, imagen=?, usuario_id=? WHERE Codigo_piso=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$calle, $numero, $piso_num, $puerta, $cp, $metros, $zona, $precio, $imagen, $usuario_id, $_POST['Codigo_piso']])) {
            header("Location: pisos.php");
            exit;
        }
    } else {
        $sql = "INSERT INTO pisos (calle, numero, piso, puerta, cp, metros, zona, precio, imagen, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$calle, $numero, $piso_num, $puerta, $cp, $metros, $zona, $precio, $imagen, $usuario_id])) {
            header("Location: pisos.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_editing ? 'Editar' : 'Crear'; ?> Piso</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $is_editing ? 'Editar' : 'Crear'; ?> Piso</h1>
        <?php if($message) echo "<div class='alert'>$message</div>"; ?>
        
        <form method="POST" class="card" enctype="multipart/form-data">
            <input type="hidden" name="Codigo_piso" value="<?php echo $piso['Codigo_piso']; ?>">
            
            <label>Calle:</label>
            <input type="text" name="calle" value="<?php echo htmlspecialchars($piso['calle']); ?>" required>
            
            <div style="display:flex; gap:10px;">
                <div style="flex:1;"><label>Número:</label><input type="number" name="numero" value="<?php echo $piso['numero']; ?>" required></div>
                <div style="flex:1;"><label>Piso:</label><input type="number" name="piso" value="<?php echo $piso['piso']; ?>" required></div>
                <div style="flex:1;"><label>Puerta:</label><input type="text" name="puerta" value="<?php echo $piso['puerta']; ?>" required></div>
            </div>

            <div style="display:flex; gap:10px;">
                <div style="flex:1;"><label>CP:</label><input type="number" name="cp" value="<?php echo $piso['cp']; ?>" required></div>
                <div style="flex:1;"><label>Metros:</label><input type="number" name="metros" value="<?php echo $piso['metros']; ?>" required></div>
            </div>

            <label>Zona:</label>
            <input type="text" name="zona" value="<?php echo htmlspecialchars($piso['zona']); ?>">

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?php echo $piso['precio']; ?>" required>

            <label>Vendedor (Propietario):</label>
            <select name="usuario_id" required>
                <?php foreach ($users as $u): ?>
                    <option value="<?php echo $u['usuario_id']; ?>" <?php echo $u['usuario_id'] == $piso['usuario_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($u['nombres'] . " (" . $u['correo'] . ")"); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Imagen:</label>
            <?php if($piso['imagen']): ?>
                <img src="../uploads/<?php echo $piso['imagen']; ?>" width="100"><br>
            <?php endif; ?>
            <input type="file" name="imagen">
            
            <br><br>
            <button type="submit" class="button">Guardar</button>
            <a href="pisos.php" class="button" style="background:#666;">Cancelar</a>
        </form>
    </div>
</body>
</html>
