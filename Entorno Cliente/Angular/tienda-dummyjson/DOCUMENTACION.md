# Documentación del Proyecto: Tienda DummyJSON

## 1. Descripción General
Este proyecto consiste en una Single Page Application (SPA) desarrollada con **Angular** que simula una tienda online. La aplicación consume datos de una API externa (DummyJSON), permite filtrar productos, añadirlos a un carrito de compras y simular un proceso de compra.

El proyecto ha sido diseñado para cumplir con los objetivos de aprendizaje relacionados con la creación de objetos, gestión de eventos y manipulación del DOM mediante un framework moderno.

## 2. Objetivos y Resultados de Aprendizaje (RA)
El desarrollo cubre los siguientes puntos del enunciado:

*   **RA2 (Sintaxis y Ejecución)**: Uso de TypeScript/JavaScript moderno (ES6+) con tipos estrictos.
*   **RA3 (Objetos Predefinidos)**: Utilización de `fetch` (vía `HttpClient`), `Math`, `JSON`, etc.
*   **RA5 (Eventos)**: Gestión de eventos de usuario (`click`, `change`, `input`) para filtros y acciones del carrito.
*   **RA6 (Modelo de Objetos)**: Renderizado dinámico del DOM basado en el estado de la aplicación (Angular Signals).

## 3. Arquitectura Técnica

### 3.1 Framework: Angular (Versión Moderna)
Se ha utilizado la última arquitectura de Angular basada en **Standalone Components** y **Signals**, prescindiendo de los módulos tradicionales (`NgModule`) para reducir la complejidad y mejorar el rendimiento.

*   **Signals (`signal`, `computed`)**: Se utilizan para la gestión del estado reactivo. Cuando una señal cambia (ej. se añade un producto al carrito), la vista se actualiza automáticamente sin necesidad de manipulación manual del DOM.
*   **Control Flow (`@for`, `@if`)**: Se utiliza la nueva sintaxis de plantillas de Angular para iterar sobre listas y mostrar elementos condicionalmente.

### 3.2 Estructura de Datos
El modelo de datos se define en `src/app/models/product.model.ts`:
*   **`Product`**: Interfaz que define la estructura de un producto (id, título, precio, categoría, imagen, etc.).
*   **`ProductsResponse`**: Interfaz para tipar la respuesta de la API.

## 4. Funcionalidades Detalladas

### 4.1 Carga de Datos
*   **Fuente**: `https://dummyjson.com/products`
*   **Implementación**: Se utiliza `HttpClient` de Angular para realizar una petición GET asíncrona. Los datos se cargan al iniciar el componente (`ngOnInit`).

### 4.2 Filtrado de Productos
Ubicado en la parte superior (`header`). Permite filtrar por:
1.  **Precio Mínimo**: Input numérico.
2.  **Categoría**: Desplegable dinámico generado a partir de los productos cargados.
3.  **Marca**: Desplegable dinámico generado a partir de los productos cargados.

**Lógica**: Se utiliza un método `applyFilters()` que recalcula la lista `filteredProducts` basándose en los criterios seleccionados.

### 4.3 Carrito de Compras
Ubicado en el lateral derecho (`aside`).
*   **Añadir**: Botón en cada tarjeta de producto.
*   **Eliminar**: Botón (X) en cada ítem del carrito.
*   **Total**: Propiedad computada (`computed`) que recalcula el precio total automáticamente cada vez que el carrito cambia.

### 4.4 Simulación de Compra
Al pulsar "Comprar", se activa un flujo de confirmación utilizando la librería externa **SweetAlert2**.
*   Mensaje: "¿Estás seguro? Vas a realizar una compra por valor de XXX".
*   Acción: Si se confirma, se vacía el carrito y se muestra un mensaje de éxito.

## 5. Librerías Externas Utilizadas

1.  **SweetAlert2** (`sweetalert2`)
    *   **Uso**: Reemplazo estético para los `alert` y `confirm` nativos del navegador.
    *   **Motivo**: Mejora la experiencia de usuario (UX) en el proceso de confirmación de compra.

2.  **Animate.css** (`animate.css`)
    *   **Uso**: Biblioteca CSS para animaciones de entrada.
    *   **Aplicación**:
        *   `animate__fadeInDown`: Aparición suave de los filtros.
        *   `animate__zoomIn`: Efecto de zoom al cargar las tarjetas de productos.
        *   `animate__slideInRight`: Entrada lateral al añadir elementos al carrito.

## 6. Estructura de Archivos Clave

*   **`src/app/app.ts`**: Lógica principal (Componente). Contiene el estado (signals), la inyección de dependencias y los métodos de negocio.
*   **`src/app/app.html`**: Vista (Template). Estructura HTML con bindings de Angular (`[value]`, `(click)`) y clases de animación.
*   **`src/app/app.scss`**: Estilos. Uso de SCSS para estilos anidados, Grid Layout para la maqueta y diseño responsive.
*   **`src/app/app.config.ts`**: Configuración global. Habilita `HttpClient` con `withFetch`.

## 7. Instrucciones de Ejecución

1.  **Instalar dependencias**:
    ```bash
    npm install
    ```
2.  **Iniciar servidor de desarrollo**:
    ```bash
    npm start
    ```
3.  **Visualizar**: Abrir `http://localhost:4200` en el navegador.
