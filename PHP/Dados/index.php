<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 2 - Juego de Dados con Imágenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .jugador {
            margin-bottom: 20px;
        }
        .dados {
            display: flex;
            gap: 10px;
        }
        .dados img {
            width: 60px;       /* Ajusta el tamaño del dado si quieres */
            height: 60px;
        }
        .resultado-final {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Juego de Dados - PHP</h2>

<?php

// Funciones

// Lanza 5 dados y devuelve un array con los valores
function lanzarDados() {
    $dados = [];
    for ($i = 0; $i < 5; $i++) {
        $dados[] = rand(1, 6);
    }
    return $dados;
}

// Suma los valores de los dados
function sumarDados($lista) {
    return array_sum($lista);
}

// Muestra las imágenes de los dados
function mostrarDadosConImagenes($lista) {
    echo '<div class="dados">';
    foreach ($lista as $dado) {
        // Ruta de la imagen según el valor del dado
        $ruta = "img/dado{$dado}.png"; 
        echo "<img src='$ruta' alt='Dado $dado'>";
    }
    echo '</div>';
}


// Una ronda del juego

// Lanzamientos de cada jugador
$jugador1 = lanzarDados();
$jugador2 = lanzarDados();

// Sumas de cada jugador
$suma1 = sumarDados($jugador1);
$suma2 = sumarDados($jugador2);

// Mostrar resultados de Jugador 1
echo '<div class="jugador">';
echo '<h3>Jugador 1</h3>';
mostrarDadosConImagenes($jugador1);
echo "<p>Total: <strong>$suma1</strong></p>";
echo '</div>';

// Mostrar resultados de Jugador 2
echo '<div class="jugador">';
echo '<h3>Jugador 2</h3>';
mostrarDadosConImagenes($jugador2);
echo "<p>Total: <strong>$suma2</strong></p>";
echo '</div>';

// Determinar ganador
echo '<div class="resultado-final">';
if ($suma1 > $suma2) {
    echo "En conjunto, ha ganado el jugador 1.";
} elseif ($suma2 > $suma1) {
    echo "En conjunto, ha ganado el jugador 2.";
} else {
    echo "¡Empate! Ambos jugadores tienen la misma suma.";
}
echo '</div>';

?>

</body>
</html>
