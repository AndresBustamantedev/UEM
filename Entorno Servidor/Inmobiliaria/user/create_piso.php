<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'vendedor') {
    header("Location: ../login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $calle = $_POST['calle'];
    $numero = $_POST['numero'];
    $piso_num = $_POST['piso'];
    $puerta = $_POST['puerta'];
    $cp = $_POST['cp'];
    $metros = $_POST['metros'];
    $zona = $_POST['zona'];
    $precio = $_POST['precio'];
    $usuario_id = $_SESSION['usuario_id'];
    
    $imagen = '';
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

    if (empty($message)) {
        $sql = "INSERT INTO pisos (calle, numero, piso, puerta, cp, metros, zona, precio, imagen, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$calle, $numero, $piso_num, $puerta, $cp, $metros, $zona, $precio, $imagen, $usuario_id])) {
            header("Location: my_pisos.php");
            exit;
        } else {
            $message = "Error al guardar el piso.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="card" style="max-width: 800px; margin: 20px auto;">
    <div style="border-bottom: 1px solid #eee; margin-bottom: 20px; padding-bottom: 10px;">
        <h2 style="margin: 0; color: #35424a;">Publicar Nuevo Piso</h2>
        <p style="margin: 5px 0 0; color: #777;">Completa la información para anunciar tu inmueble.</p>
    </div>

    <?php if($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Sección Ubicación -->
            <div class="form-section-title">Ubicación</div>
            
            <div class="form-full">
                <label>Calle:</label>
                <input type="text" name="calle" placeholder="Ej: Calle Mayor" required>
            </div>
            
            <div>
                <label>Número:</label>
                <input type="number" name="numero" placeholder="Ej: 10" required>
            </div>
            <div>
                <label>Piso:</label>
                <input type="number" name="piso" placeholder="Ej: 3" required>
            </div>
            
            <div>
                <label>Puerta:</label>
                <input type="text" name="puerta" placeholder="Ej: A, Izq" required>
            </div>
            <div>
                <label>Código Postal:</label>
                <input type="number" name="cp" placeholder="28001" required>
            </div>
            
            <div class="form-full">
                <label>Zona / Barrio:</label>
                <input type="text" name="zona" placeholder="Ej: Centro Histórico">
            </div>

            <!-- Sección Detalles -->
            <div class="form-section-title">Características del Inmueble</div>

            <div>
                <label>Superficie (m²):</label>
                <input type="number" name="metros" placeholder="Ej: 90" required>
            </div>
            <div>
                <label>Precio (€):</label>
                <input type="number" step="0.01" name="precio" placeholder="Ej: 250000" required>
            </div>

            <!-- Sección Multimedia -->
            <div class="form-section-title">Multimedia</div>

            <div class="form-full">
                <label>Imagen Principal:</label>
                <input type="file" name="imagen" accept="image/*" required>
                <p style="font-size: 0.85em; color: #666; margin-top: 5px;">Sube una foto atractiva de la fachada o el salón.</p>
            </div>
        </div>
        
        <div style="margin-top: 30px; text-align: right; padding-top: 20px; border-top: 1px solid #eee;">
            <a href="my_pisos.php" class="button button-secondary" style="margin-right: 10px;">Cancelar</a>
            <button type="submit" class="button">Publicar Anuncio</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
