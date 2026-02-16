<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Verificación del Sistema</h1>";

// 1. Verificar archivo de configuración
if (file_exists('config/db.php')) {
    echo "<p style='color:green'>✅ config/db.php encontrado.</p>";
    require_once 'config/db.php';
} else {
    die("<p style='color:red'>❌ Error: config/db.php no existe.</p>");
}

// 2. Verificar Conexión a Base de Datos
try {
    if (isset($pdo)) {
        echo "<p style='color:green'>✅ Conexión a MySQL exitosa.</p>";
    } else {
        die("<p style='color:red'>❌ Error: Variable \$pdo no definida.</p>");
    }
} catch (Exception $e) {
    die("<p style='color:red'>❌ Error de conexión: " . $e->getMessage() . "</p>");
}

// 3. Verificar Tablas
$tablas_esperadas = ['usuario', 'pisos', 'comprados'];
$tablas_encontradas = [];

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tablas_bd = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tablas_esperadas as $tabla) {
        if (in_array($tabla, $tablas_bd)) {
            echo "<p style='color:green'>✅ Tabla '$tabla' existe.</p>";
            $tablas_encontradas[] = $tabla;
        } else {
            echo "<p style='color:red'>❌ Tabla '$tabla' NO encontrada.</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error al listar tablas: " . $e->getMessage() . "</p>";
}

// 4. Verificar Datos
if (in_array('usuario', $tablas_encontradas)) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuario");
    $count = $stmt->fetchColumn();
    echo "<p>Usuarios registrados: <strong>$count</strong></p>";
    if ($count > 0) {
        echo "<p style='color:green'>✅ Hay usuarios en la base de datos.</p>";
    } else {
        echo "<p style='color:orange'>⚠️ La tabla usuarios está vacía. Ejecuta install.php o inserta datos manualmente.</p>";
    }
}

echo "<hr>";
echo "<p><strong>Estado Final:</strong> " . (count($tablas_encontradas) == 3 ? "OPERATIVO" : "CON ERRORES") . "</p>";
echo "<p><a href='index.php'>Ir a la Página Principal</a></p>";
?>
