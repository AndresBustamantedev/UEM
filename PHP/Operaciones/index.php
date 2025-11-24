<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Operaciones</title>
</head>
<body>

<h2>Formulario de operaciones</h2>

<form action="datos_operaciones.php" method="post">
    
    <label>Introduzca el primer número:</label>
    <input type="number" name="num1" required>
    <br><br>

    <label>Introduzca el segundo número:</label>
    <input type="number" name="num2" required>
    <br><br>

    <label>Seleccione la operación:</label><br>

    <input type="radio" name="operacion" value="suma" required> Suma<br>
    <input type="radio" name="operacion" value="resta"> Resta<br>
    <input type="radio" name="operacion" value="producto"> Producto<br>
    <input type="radio" name="operacion" value="cociente"> Cociente<br><br>

    <button type="submit">Enviar datos</button>

</form>

</body>
</html>
