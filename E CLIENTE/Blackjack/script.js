// -------------------------------
// MODELO DEL JUEGO (Objetos, datos)
// -------------------------------

// Palos: símbolo para mostrar y código para el nombre del PNG
const palos = [
  { simbolo: "♠", codigo: "S" }, // Picas
  { simbolo: "♥", codigo: "H" }, // Corazones
  { simbolo: "♦", codigo: "D" }, // Diamantes
  { simbolo: "♣", codigo: "C" }, // Tréboles
];

const rangos = [
  { nombre: "A", valor: 1 },
  { nombre: "2", valor: 2 },
  { nombre: "3", valor: 3 },
  { nombre: "4", valor: 4 },
  { nombre: "5", valor: 5 },
  { nombre: "6", valor: 6 },
  { nombre: "7", valor: 7 },
  { nombre: "8", valor: 8 },
  { nombre: "9", valor: 9 },
  { nombre: "10", valor: 10 },
  { nombre: "J", valor: 11 },
  { nombre: "Q", valor: 11 },
  { nombre: "K", valor: 11 },
];

// Clase carta con referencia a la imagen PNG en img
class Carta {
  constructor(rango, palo) {
    this.rango = rango.nombre;        // "A", "2", ..., "K"
    this.valor = rango.valor;        // 1, 2, ..., 11
    this.paloSimbolo = palo.simbolo; // "♠", "♥", ...
    this.paloCodigo = palo.codigo;   // "S", "H", "D", "C"

    // Ruta de las imagenes
    this.rutaImagen = `img/${this.rango}${this.paloCodigo}.png`;
  }

  texto() {
    return this.rango + this.paloSimbolo;
  }
}

let mazo = [];
let manoBanca = [];
let manoJugador = [];
let puntosBanca = 0;
let puntosJugador = 0;
let nombreJugador = "Jugador";
let juegoTerminado = false;

// Referencias al DOM
const cartasBancaDiv = document.getElementById("cartasBanca");
const cartasJugadorDiv = document.getElementById("cartasJugador");
const puntosBancaSpan = document.getElementById("puntosBanca");
const puntosJugadorSpan = document.getElementById("puntosJugador");
const nombreJugadorSpan = document.getElementById("nombreJugador");
const mensajeBienvenidaP = document.getElementById("mensajeBienvenida");
const estadoJuegoDiv = document.getElementById("estadoJuego");
const resultadoDiv = document.getElementById("resultado");
const btnPedir = document.getElementById("btnPedir");
const btnPlantarse = document.getElementById("btnPlantarse");
const btnReiniciar = document.getElementById("btnReiniciar");

// -------------------------------
// FUNCIONES DE UTILIDAD
// -------------------------------
function crearMazo() {
  const nuevoMazo = [];
  palos.forEach((palo) => {
    rangos.forEach((rango) => {
      const carta = new Carta(rango, palo);
      nuevoMazo.push(carta);
    });
  });
  return nuevoMazo;
}

function barajar(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

function robarCarta() {
  return mazo.pop();
}

function calcularPuntos(mano) {
  let total = 0;
  mano.forEach((carta) => (total += carta.valor));
  return total;
}

// Mostrar carta como IMG PNG en el DOM
function mostrarCartaEnDOM(carta, contenedor) {
  const img = document.createElement("img");
  img.classList.add("carta");
  img.src = carta.rutaImagen;
  img.alt = carta.texto();
  contenedor.appendChild(img);
}

function actualizarPuntos() {
  puntosBanca = calcularPuntos(manoBanca);
  puntosJugador = calcularPuntos(manoJugador);
  puntosBancaSpan.textContent = puntosBanca;
  puntosJugadorSpan.textContent = puntosJugador;
}

function desactivarControlesJugador() {
  btnPedir.disabled = true;
  btnPlantarse.disabled = true;
}

function activarControlesJugador() {
  btnPedir.disabled = false;
  btnPlantarse.disabled = false;
}

// -------------------------------
// LÓGICA PRINCIPAL DEL JUEGO
// -------------------------------
function iniciarJuego() {
  const nombrePrompt = prompt("Introduce tu nombre:");
  if (nombrePrompt && nombrePrompt.trim() !== "") {
    nombreJugador = nombrePrompt.trim();
  }
  nombreJugadorSpan.textContent = nombreJugador;
  mensajeBienvenidaP.textContent =
    "Bienvenido/a, " + nombreJugador + ". ¡Empieza la partida!";

  // Crear y barajar mazo
  mazo = crearMazo();
  barajar(mazo);

  // Empieza la banca
  estadoJuegoDiv.textContent = "Turno de la banca...";
  turnoBanca();
}

function turnoBanca() {
  function pedirCartaBancaConRetraso() {
    if (juegoTerminado) return;

    puntosBanca = calcularPuntos(manoBanca);

    // Si ya tiene 17 o más puntos, se planta
    if (puntosBanca >= 17) {
      puntosBancaSpan.textContent = puntosBanca;

      if (puntosBanca >= 22) {
        // Banca se pasa => gana el jugador
        finalizarJuego("Jugador");
      } else {
        // Turno del jugador
        estadoJuegoDiv.textContent = "Turno de " + nombreJugador + ".";
        activarControlesJugador();
      }
      return;
    }

    // Si tiene menos de 17, roba una carta tras un pequeño retraso
    setTimeout(() => {
      const carta = robarCarta();
      manoBanca.push(carta);
      mostrarCartaEnDOM(carta, cartasBancaDiv);
      actualizarPuntos();

      if (puntosBanca >= 22) {
        finalizarJuego("Jugador");
        return;
      }

      pedirCartaBancaConRetraso();
    }, 700);
  }

  pedirCartaBancaConRetraso();
}

function turnoJugadorPedirCarta() {
  if (juegoTerminado) return;

  const carta = robarCarta();
  manoJugador.push(carta);
  mostrarCartaEnDOM(carta, cartasJugadorDiv);
  actualizarPuntos();

  if (puntosJugador >= 22) {
    // Jugador se pasa
    finalizarJuego("Banca");
  } else if (puntosJugador === 21) {
    // Justo 21, decidimos ganador
    determinarGanadorFinal();
  }
}

function turnoJugadorPlantarse() {
  if (juegoTerminado) return;
  determinarGanadorFinal();
}

function determinarGanadorFinal() {
  juegoTerminado = true;
  desactivarControlesJugador();

  // Si alguno se pasa (por si acaso)
  if (puntosBanca >= 22 && puntosJugador >= 22) {
    resultadoDiv.textContent = "Ambos os habéis pasado de 21. Empate.";
    estadoJuegoDiv.textContent = "Empate.";
    return;
  } else if (puntosBanca >= 22) {
    finalizarJuego("Jugador");
    return;
  } else if (puntosJugador >= 22) {
    finalizarJuego("Banca");
    return;
  }

  // Si ambos tienen 21
  if (puntosBanca === 21 && puntosJugador === 21) {
    resultadoDiv.textContent = "¡Ambos tenéis 21! Empate.";
    estadoJuegoDiv.textContent = "Empate.";
    return;
  }

  const distanciaBanca = 21 - puntosBanca;
  const distanciaJugador = 21 - puntosJugador;

  if (distanciaBanca === distanciaJugador) {
    resultadoDiv.textContent =
      "Os habéis acercado lo mismo a 21. Empate.";
    estadoJuegoDiv.textContent = "Empate.";
  } else if (distanciaJugador < 0 && distanciaBanca < 0) {
    resultadoDiv.textContent = "Ambos os habéis pasado de 21. Empate.";
    estadoJuegoDiv.textContent = "Empate.";
  } else if (distanciaJugador < 0) {
    finalizarJuego("Banca");
  } else if (distanciaBanca < 0) {
    finalizarJuego("Jugador");
  } else if (distanciaJugador < distanciaBanca) {
    finalizarJuego("Jugador");
  } else {
    finalizarJuego("Banca");
  }
}

function finalizarJuego(ganador) {
  juegoTerminado = true;
  desactivarControlesJugador();

  if (ganador === "Jugador") {
    resultadoDiv.textContent = "¡" + nombreJugador + " gana la partida!";
    estadoJuegoDiv.textContent = "Has ganado.";
  } else if (ganador === "Banca") {
    resultadoDiv.textContent = "La banca gana la partida.";
    estadoJuegoDiv.textContent = "Has perdido.";
  } else {
    resultadoDiv.textContent = "Empate.";
    estadoJuegoDiv.textContent = "Empate.";
  }

  // Mostrar botón de reinicio
  btnReiniciar.style.display = "block";
}

// -------------------------------
// REINICIAR PARTIDA
// -------------------------------
btnReiniciar.addEventListener("click", reiniciarJuego);

function reiniciarJuego() {
  btnReiniciar.style.display = "none";

  // Reset de variables
  mazo = [];
  manoBanca = [];
  manoJugador = [];
  puntosBanca = 0;
  puntosJugador = 0;
  juegoTerminado = false;

  // Limpiar DOM
  cartasBancaDiv.innerHTML = "";
  cartasJugadorDiv.innerHTML = "";
  resultadoDiv.textContent = "";
  estadoJuegoDiv.textContent = "";
  puntosBancaSpan.textContent = 0;
  puntosJugadorSpan.textContent = 0;

  // Mantener el mismo nombre de jugador
  mensajeBienvenidaP.textContent =
    "Nueva partida para " + nombreJugador + ".";

  // Nuevo mazo y nuevo turno de banca
  mazo = crearMazo();
  barajar(mazo);
  estadoJuegoDiv.textContent = "Turno de la banca...";
  turnoBanca();
}

// -------------------------------
// EVENTOS INICIALES
// -------------------------------
btnPedir.addEventListener("click", turnoJugadorPedirCarta);
btnPlantarse.addEventListener("click", turnoJugadorPlantarse);

// Iniciar el juego al cargar la página
window.addEventListener("DOMContentLoaded", iniciarJuego);
