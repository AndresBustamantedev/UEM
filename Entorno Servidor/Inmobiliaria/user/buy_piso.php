<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'comprador') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Piso no especificado.");
}

$piso_id = $_GET['id'];
$user_id = $_SESSION['usuario_id'];

// Obtener datos del piso
$stmt = $pdo->prepare("SELECT * FROM pisos WHERE Codigo_piso = :id");
$stmt->execute(['id' => $piso_id]);
$piso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$piso) {
    die("Piso no encontrado.");
}

// Verificar si ya está comprado
$stmt = $pdo->prepare("SELECT * FROM comprados WHERE Codigo_piso = :id");
$stmt->execute(['id' => $piso_id]);
if ($stmt->rowCount() > 0) {
    die("Este piso ya ha sido vendido.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Realizar la compra
    $precio_final = $piso['precio']; // Se asume precio fijo, sin negociación por ahora
    
    $sql = "INSERT INTO comprados (usuario_comprador, Codigo_piso, Precio_final) VALUES (:user_id, :piso_id, :precio)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute(['user_id' => $user_id, 'piso_id' => $piso_id, 'precio' => $precio_final])) {
        $message = "¡Enhorabuena! Has comprado el piso.";
    } else {
        $message = "Error al procesar la compra.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="card" style="max-width: 800px; margin: 20px auto;">
    <?php if($message): ?>
        <div class="alert success"><?php echo $message; ?></div>
        <a href="../index.php" class="button">Volver al Inicio</a>
    <?php else: ?>
        <h2>Confirmar Compra</h2>
        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <?php if($piso['imagen']): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($piso['imagen']); ?>" alt="Imagen" style="width: 100%; border-radius: 5px;">
                <?php endif; ?>
            </div>
            <div style="flex: 1;">
                <h3><?php echo htmlspecialchars($piso['calle'] . ' ' . $piso['numero']); ?></h3>
                <p><strong>Zona:</strong> <?php echo htmlspecialchars($piso['zona']); ?></p>
                <p><strong>Metros:</strong> <?php echo $piso['metros']; ?> m²</p>
                <p><strong>Precio Final:</strong> <span style="font-size: 1.5em; color: #e8491d; font-weight: bold;"><?php echo number_format($piso['precio'], 2); ?> €</span></p>
                
                <form method="POST">
                    <p>¿Estás seguro de que deseas comprar este inmueble?</p>
                    <button type="submit" class="button" onclick="return confirm('¿Confirmar compra?');">Confirmar Compra</button>
                    <a href="../index.php" class="button" style="background: #666;">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
