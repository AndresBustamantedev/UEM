<?php
require_once 'config/db.php';
include 'includes/header.php';

// Construcción de la consulta de búsqueda
$whereClauses = [];
$params = [];

if (isset($_GET['zona']) && !empty($_GET['zona'])) {
    $whereClauses[] = "zona LIKE :zona";
    $params['zona'] = '%' . $_GET['zona'] . '%';
}

if (isset($_GET['precio_max']) && !empty($_GET['precio_max'])) {
    $whereClauses[] = "precio <= :precio_max";
    $params['precio_max'] = $_GET['precio_max'];
}

$sql = "SELECT * FROM pisos";
if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

// Solo mostrar pisos que NO han sido comprados (opcional, pero lógico)
// O mejor, mostrar todos y marcar los vendidos.
// Según el enunciado "Cualquiera puede consultar los pisos dados de alta".
// Vamos a filtrar los que ya están en la tabla comprados para no venderlos dos veces.
// Subconsulta: ... WHERE Codigo_piso NOT IN (SELECT Codigo_piso FROM comprados)

if (count($whereClauses) > 0) {
    $sql .= " AND Codigo_piso NOT IN (SELECT Codigo_piso FROM comprados)";
} else {
    $sql .= " WHERE Codigo_piso NOT IN (SELECT Codigo_piso FROM comprados)";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <h2>Buscar Pisos</h2>
    <form action="index.php" method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
        <div style="flex: 1;">
            <label>Zona:</label>
            <input type="text" name="zona" placeholder="Ej: Centro, Norte..." value="<?php echo isset($_GET['zona']) ? htmlspecialchars($_GET['zona']) : ''; ?>">
        </div>
        <div style="flex: 1;">
            <label>Precio Máximo:</label>
            <input type="number" name="precio_max" placeholder="Ej: 200000" value="<?php echo isset($_GET['precio_max']) ? htmlspecialchars($_GET['precio_max']) : ''; ?>">
        </div>
        <button type="submit" class="button">Buscar</button>
    </form>
</div>

<h2>Pisos Disponibles</h2>

<?php if (count($pisos) > 0): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php foreach ($pisos as $piso): ?>
            <div class="card">
                <?php if($piso['imagen']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($piso['imagen']); ?>" alt="Imagen del piso" class="piso-img">
                <?php else: ?>
                    <div style="height: 200px; background: #eee; display: flex; align-items: center; justify-content: center;">Sin Imagen</div>
                <?php endif; ?>
                
                <h3><?php echo htmlspecialchars($piso['calle']) . ' ' . $piso['numero']; ?></h3>
                <p><strong>Zona:</strong> <?php echo htmlspecialchars($piso['zona']); ?></p>
                <p><strong>Precio:</strong> <?php echo number_format($piso['precio'], 2); ?> €</p>
                <p><strong>Metros:</strong> <?php echo $piso['metros']; ?> m²</p>
                
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['tipo_usuario'] == 'comprador'): ?>
                    <a href="user/buy_piso.php?id=<?php echo $piso['Codigo_piso']; ?>" class="button" style="display: block; text-align: center; margin-top: 10px;">Comprar</a>
                <?php elseif (!isset($_SESSION['usuario_id'])): ?>
                    <p style="color: #666; font-size: 0.9em;">Inicia sesión como comprador para adquirir este piso.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No se encontraron pisos disponibles.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
