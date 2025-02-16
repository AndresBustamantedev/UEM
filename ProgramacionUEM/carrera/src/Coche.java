public class Coche {
    private String marca;
    private String modelo;
    private int cv;
    private int cc;
    private String matricula;
    private double velocidad; // Velocidad actual
    private double kmRecorridos;

    public Coche(String marca, String modelo, int cv, int cc, String matricula) {
        this.marca = marca;
        this.modelo = modelo;
        this.cv = cv;
        this.cc = cc;
        this.matricula = matricula;
        this.velocidad = 0; // Inicialmente el coche no tiene velocidad
        this.kmRecorridos = 0; // Inicialmente no tiene recorrido
    }

    public void acelerar(double velocidadDeseada) {
        double incrementoVelocidad = 0;

        // Calcular incremento de velocidad según los caballos de fuerza
        if (cv > 100) { // Más de 100 cv
            incrementoVelocidad = Math.random() * (velocidadDeseada - 10) + 10;
        } else { // Menos de 100 cv
            incrementoVelocidad = Math.random() * velocidadDeseada;
        }

        // Un coche no puede acelerar menos de 10 km/h
        if (incrementoVelocidad < 10) {
            incrementoVelocidad = 10;
        }

        // Incrementar velocidad y kilómetros recorridos
        velocidad += incrementoVelocidad;
        kmRecorridos += incrementoVelocidad * 0.5;
    }

    public void mostrarDatos() {
        System.out.println("Coche{" +
                "Marca='" + marca + '\'' +
                ", Modelo='" + modelo + '\'' +
                ", CV=" + cv +
                ", CC=" + cc +
                ", Matrícula='" + matricula + '\'' +
                ", Velocidad=" + velocidad +
                " km/h, KM Recorridos=" + kmRecorridos +
                '}');
    }

    public double getKmRecorridos() {
        return kmRecorridos;
    }

    public String getMatricula() {
        return matricula;
    }
}