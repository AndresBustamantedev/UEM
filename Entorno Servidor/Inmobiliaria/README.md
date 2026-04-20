# Inmobiliaria Web

Proyecto de gestión inmobiliaria con PHP y MySQL.

## Instalación

1.  **Base de Datos**:
    *   Cree una base de datos en MySQL llamada `inmobiliaria`.
    *   Importe el archivo `db_schema.sql` para crear las tablas e insertar usuarios de prueba.

2.  **Configuración**:
    *   Edite `config/db.php` si sus credenciales de base de datos son diferentes a las predeterminadas (root/sin contraseña).

3.  **Ejecución**:
    *   Coloque la carpeta del proyecto en su servidor web (Apache/XAMPP).
    *   Acceda a `http://localhost/UEM/Entorno Servidor/Inmobiliaria/`.

## Usuarios de Prueba

*   **Administrador**:
    *   Correo: `admin@inmobiliaria.com`
    *   Clave: `admin123`
*   **Vendedor**:
    *   Correo: `vendedor1@test.com`
    *   Clave: `123456`
*   **Comprador**:
    *   Correo: `comprador1@test.com`
    *   Clave: `123456`

## Estructura

*   `admin/`: Panel de administración (CRUD Usuarios y Pisos).
*   `user/`: Funcionalidades de usuarios (Comprar, Publicar).
*   `includes/`: Cabecera y pie de página comunes.
*   `config/`: Conexión a BD.
*   `uploads/`: Imágenes de los pisos.
*   `css/`: Estilos.
