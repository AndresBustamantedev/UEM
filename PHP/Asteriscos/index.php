<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 1 - Rectángulo de Asteriscos</title>
</head>
<body>

<?php

// Generar números aleatorios entre 5 y 15
$alto = rand(5, 15);
$ancho = rand(5, 15);

// Mostrar los valores
echo "<p><strong>Alto:</strong> $alto</p>";
echo "<p><strong>Ancho:</strong> $ancho</p>";

// Dibujar el rectángulo
for ($i = 0; $i < $alto; $i++) {

    // Dibujar una fila
    for ($j = 0; $j < $ancho; $j++) {
        echo "* ";
    }

    echo "<br>";
}

?>

</body>
</html>
