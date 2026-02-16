# Cambios a Bootstrap y Mejoras de Diseño

He migrado todo el proyecto de CSS puro a **Bootstrap 5** para mejorar la responsividad y el mantenimiento, además de implementar varias mejoras visuales para darle un aspecto más profesional y moderno.

## 1. Integración de Bootstrap 5
He sustituido el sistema de rejilla manual y los resets de CSS por las clases estándar de Bootstrap:
- **Librerías**: Añadí los CDN de Bootstrap 5.3.3 (CSS/JS) y Bootstrap Icons.
- **Layout**: Reemplacé los contenedores `display: grid` por el sistema `.container`, `.row` y `.col-*`, asegurando que la web se adapte perfectamente a móviles, tablets y escritorio.
- **Componentes**: Implementé el componente `.navbar` para el menú (con botón hamburguesa en móvil) y `.form-control` para estilizar los formularios de contacto.
- **Utilidades**: Usé clases de utilidad como `.d-flex`, `.justify-content-between`, `.mt-auto` y `.text-center` para alinear elementos sin escribir CSS extra.

## 2. Ajustes de Diseño y Estilo
Para mantener la identidad visual "gamer" pero con un toque más pulido:
- **Contenedor Compacto**: Limité el ancho máximo del contenido a `1000px` en pantallas grandes para evitar que las imágenes se estiren demasiado verticalmente.
- **Scrollbar Estable**: Forcé la visualización del scroll vertical (`overflow-y: scroll`) para evitar saltos molestos al navegar entre páginas de diferente altura.
- **Iconos**: Sustituí los emojis antiguos por **Bootstrap Icons** (`<i class="bi ..."></i>`), dándole un acabado más limpio a las secciones de soporte, contacto y características.

## 3. Mejoras en la Experiencia de Usuario (UX)
- **Tarjetas Interactivas**: Convertí las tarjetas de categorías en enlaces (`<a>`) completos, añadiendo una animación de elevación y zoom en las imágenes al pasar el ratón.
- **Navegación**: Corregí el comportamiento del logo para que siempre redirija al `index.html`.
- **Ofertas Semanales**: Añadí una nueva sección en la home con tarjetas horizontales y badges de descuento para destacar promociones.
- **Feedback Interactivo**: Implementé un **modal de Bootstrap** en el botón de compra ("Agregar al carrito") para ofrecer una recompensa visual al usuario (póster gratis).

## 4. Rediseño de la Página de Producto
He transformado completamente la ficha del juego para modernizarla:
- **Fondo Inmersivo**: Añadí un fondo con la imagen del juego a pantalla completa y un degradado (overlay) que se funde perfectamente con el color de la web, integrando el menú de navegación sobre él.
- **Galería Interactiva**: Implementé una galería de imágenes funcional con JavaScript; al hacer clic en las miniaturas, la imagen principal cambia.
- **Alineación de Imágenes**: Ajusté el diseño a dos columnas simétricas (`col-md-6`), forzando las imágenes a una relación de aspecto `16/9` y usando `object-fit: contain` para que siempre se vean completas y alineadas con el bloque de texto.

## 5. Renovación de la Página de Inicio (Index)
- **Hero Slider 3D**: Sustituí el banner estático por un **Carrusel de Bootstrap** dinámico con 3 diapositivas (Bienvenida, Nuevo Lanzamiento, Ofertas).
    - Apliqué efectos 3D (`perspective`, `rotate`) a las imágenes para dar profundidad.
    - Integré el carrusel con el fondo de la web (transparente + degradados) eliminando cortes visuales.
    - Unifiqué la altura y estructura de los slides para evitar saltos de contenido al navegar.

## 6. Mejoras en Soporte (Ticket)
- **Sección FAQ**: Añadí un acordeón de Bootstrap (`.accordion`) para las Preguntas Frecuentes, con iconos personalizados y estilo oscuro.
- **Cabecera Rediseñada**: Reorganicé la información de contacto en dos columnas limpias con iconos grandes y una llamada a la acción clara para crear tickets.
