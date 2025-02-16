import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        // Ingreso de datos para el Coche 1
        System.out.println("Introduce los datos para el Coche 1:");
        System.out.print("Marca: ");
        String marca1 = scanner.nextLine();
        System.out.print("Modelo: ");
        String modelo1 = scanner.nextLine();
        System.out.print("CV: ");
        int cv1 = scanner.nextInt();
        System.out.print("CC: ");
        int cc1 = scanner.nextInt();
        scanner.nextLine(); // Limpiar el buffer
        System.out.print("Matrícula: ");
        String matricula1 = scanner.nextLine();

        // Crear Coche 1
        Coche coche1 = new Coche(marca1, modelo1, cv1, cc1, matricula1);

        // Ingreso de datos para el Coche 2
        System.out.println("\nIntroduce los datos para el Coche 2:");
        System.out.print("Marca: ");
        String marca2 = scanner.nextLine();
        System.out.print("Modelo: ");
        String modelo2 = scanner.nextLine();
        System.out.print("CV: ");
        int cv2 = scanner.nextInt();
        System.out.print("CC: ");
        int cc2 = scanner.nextInt();
        scanner.nextLine(); // Limpiar el buffer
        System.out.print("Matrícula: ");
        String matricula2 = scanner.nextLine();

        // Crear Coche 2
        Coche coche2 = new Coche(marca2, modelo2, cv2, cc2, matricula2);

        // Ingreso de datos para la Carrera
        System.out.println("\nIntroduce los datos para la Carrera:");
        System.out.print("Kilómetros totales: ");
        double kmTotales = scanner.nextDouble();
        System.out.print("Número de vueltas: ");
        int numVueltas = scanner.nextInt();

        // Crear carrera
        Carrera carrera = new Carrera(kmTotales, numVueltas);

        // Asignar participantes
        carrera.setParticipantes(coche1, coche2);

        // Iniciar la carrera
        carrera.iniciarCarrera();

        scanner.close(); // Cerrar el scanner
    }
}