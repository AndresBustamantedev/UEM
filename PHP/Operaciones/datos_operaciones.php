<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>

<h2>Resultado de la operación</h2>

<?php

// Recibir datos del formulario
$num1 = $_POST['num1'];
$num2 = $_POST['num2'];
$operacion = $_POST['operacion'];

switch ($operacion) {

    case "suma":
        $resultado = $num1 + $num2;
        echo "El resultado de realizar la <strong>suma</strong> de los números $num1 y $num2 es <strong>$resultado</strong>.";
        break;

    case "resta":
        $resultado = $num1 - $num2;
        echo "El resultado de realizar la <strong>resta</strong> de los números $num1 y $num2 es <strong>$resultado</strong>.";
        break;

    case "producto":
        $resultado = $num1 * $num2;
        echo "El resultado de realizar el <strong>producto</strong> de los números $num1 y $num2 es <strong>$resultado</strong>.";
        break;

    case "cociente":
        if ($num2 == 0) {
            echo "No se puede dividir entre 0.";
        } else {
            $resultado = $num1 / $num2;
            echo "El resultado de realizar el <strong>cociente</strong> de los números $num1 y $num2 es <strong>$resultado</strong>.";
        }
        break;
}

?>

</body>
</html>
