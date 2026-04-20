<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'vendedor') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM pisos WHERE usuario_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $user_id]);
$pisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="card">
    <h2>Mis Pisos en Venta</h2>
    <a href="create_piso.php" class="button">Publicar Nuevo Piso</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <?php foreach ($pisos as $piso): ?>
        <div class="card">
            <?php if($piso['imagen']): ?>
                <img src="../uploads/<?php echo htmlspecialchars($piso['imagen']); ?>" alt="Imagen" class="piso-img">
            <?php else: ?>
                <div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">Sin Imagen</div>
            <?php endif; ?>
            
            <h3><?php echo htmlspecialchars($piso['calle']); ?></h3>
            <p><strong>Precio:</strong> <?php echo number_format($piso['precio'], 2); ?> €</p>
            <p><strong>Estado:</strong> En Venta</p> 
            <!-- Podríamos verificar si está en 'comprados' para decir 'Vendido' -->
        </div>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>
