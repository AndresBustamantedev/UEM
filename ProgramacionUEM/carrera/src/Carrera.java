public class Carrera {
    private Coche coche1;
    private Coche coche2;
    private String ganador;
    private double kmTotales;
    private int numVueltas;

    public Carrera(double kmTotales, int numVueltas) {
        this.kmTotales = kmTotales;
        this.numVueltas = numVueltas;
        this.ganador = null; // Inicialmente no hay ganador
    }

    public void setParticipantes(Coche coche1, Coche coche2) {
        this.coche1 = coche1;
        this.coche2 = coche2;
    }

    public void iniciarCarrera() {
        System.out.println("\nIniciando la carrera...");
        System.out.println("\nDatos de los participantes:");
        coche1.mostrarDatos();
        coche2.mostrarDatos();

        while (true) {
            for (int i = 0; i < numVueltas; i++) {
                coche1.acelerar(30); // Ejemplo con velocidad deseada = 30
                coche2.acelerar(30);
            }

            System.out.println("\nResultado después de las vueltas:");
            System.out.println("Coche 1:");
            coche1.mostrarDatos();
            System.out.println("Coche 2:");
            coche2.mostrarDatos();

            if (coche1.getKmRecorridos() >= kmTotales || coche2.getKmRecorridos() >= kmTotales) {
                ganador = (coche1.getKmRecorridos() >= kmTotales) ? coche1.getMatricula() : coche2.getMatricula();
                break; // Terminar la carrera
            } else {
                System.out.println("No se ha alcanzado la distancia total, dando vueltas extra...");
            }
        }

        System.out.println("\n¡El ganador ha sido el coche con matrícula: " + ganador + "!");
    }
}