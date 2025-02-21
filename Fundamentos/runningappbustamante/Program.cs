using System;
using System.Collections.Generic;

// Definición de la clase Usuario
class Usuario
{
    public string Correo { get; set; }
    public string Contraseña { get; set; }
    public List<Entrenamiento> Entrenamientos { get; set; } = new List<Entrenamiento>();
}

// Definición de la clase Entrenamiento
class Entrenamiento
{
    public double Distancia { get; set; }
    public TimeSpan Tiempo { get; set; }
}

class Program
{
    static List<Usuario> usuarios = new List<Usuario>(); // Lista de usuarios registrados
    static Usuario usuarioActual = null; // Usuario actualmente en sesión

    static void Main()
    {
        // Menú principal
        while (true)
        {
            Console.WriteLine("\n--- RUNNING APP ---");
            Console.WriteLine("\n--- Menú Principal ---");
            Console.WriteLine("1. Registrar usuario");
            Console.WriteLine("2. Iniciar sesión");
            Console.WriteLine("3. Salir");
            Console.Write("Seleccione una opción: ");
            
            string opcion = Console.ReadLine();
            switch (opcion)
            {
                case "1": RegistrarUsuario(); break;
                case "2": IniciarSesion(); break;
                case "3": return;
                default: Console.WriteLine("Opción no válida"); break;
            }
        }
    }

    // Método para registrar un nuevo usuario
    static void RegistrarUsuario()
    {
        Console.Write("Ingrese correo: ");
        string correo = Console.ReadLine();
        
        if (usuarios.Exists(u => u.Correo == correo))
        {
            Console.WriteLine("El usuario ya está registrado.");
            return;
        }
        
        Console.Write("Ingrese contraseña: ");
        string contraseña = Console.ReadLine();
        
        usuarios.Add(new Usuario { Correo = correo, Contraseña = contraseña });
        Console.WriteLine("Usuario registrado con éxito.");
    }

    // Método para iniciar sesión con un usuario existente
    static void IniciarSesion()
    {
        Console.Write("Ingrese correo: ");
        string correo = Console.ReadLine();
        Console.Write("Ingrese contraseña: ");
        string contraseña = Console.ReadLine();
        
        usuarioActual = usuarios.Find(u => u.Correo == correo && u.Contraseña == contraseña);
        if (usuarioActual == null)
        {
            Console.WriteLine("Credenciales incorrectas.");
            return;
        }
        
        Console.WriteLine("Sesión iniciada.");
        MenuUsuario();
    }

    // Menú de usuario autenticado
    static void MenuUsuario()
    {
        while (usuarioActual != null)
        {
            Console.WriteLine("\n--- Menú de usuario ---");
            Console.WriteLine("1. Registrar entrenamiento");
            Console.WriteLine("2. Listar entrenamientos");
            Console.WriteLine("3. Vaciar entrenamientos");
            Console.WriteLine("4. Cerrar sesión");
            Console.Write("Seleccione una opción: ");
            
            string opcion = Console.ReadLine();
            switch (opcion)
            {
                case "1": RegistrarEntrenamiento(); break;
                case "2": ListarEntrenamientos(); break;
                case "3": VaciarEntrenamientos(); break;
                case "4": usuarioActual = null; Console.WriteLine("Sesión cerrada."); break;
                default: Console.WriteLine("Opción no válida"); break;
            }
        }
    }

    // Método para registrar un nuevo entrenamiento
    static void RegistrarEntrenamiento()
    {
        Console.Write("Ingrese distancia recorrida (km): ");
        double distancia = double.Parse(Console.ReadLine());
        Console.Write("Ingrese tiempo empleado (minutos): ");
        double minutos = double.Parse(Console.ReadLine());
        
        usuarioActual.Entrenamientos.Add(new Entrenamiento { Distancia = distancia, Tiempo = TimeSpan.FromMinutes(minutos) });
        Console.WriteLine("Entrenamiento registrado con éxito.");
    }

    // Método para listar entrenamientos del usuario actual
    static void ListarEntrenamientos()
    {
        if (usuarioActual.Entrenamientos.Count == 0)
        {
            Console.WriteLine("No hay entrenamientos registrados.");
            return;
        }
        
        foreach (var entrenamiento in usuarioActual.Entrenamientos)
        {
            Console.WriteLine($"Distancia: {entrenamiento.Distancia} km, Tiempo: {entrenamiento.Tiempo.TotalMinutes} min");
        }
    }

    // Método para vaciar la lista de entrenamientos
    static void VaciarEntrenamientos()
    {
        usuarioActual.Entrenamientos.Clear();
        Console.WriteLine("Todos los entrenamientos han sido eliminados.");
    }
}
