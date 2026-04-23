# Los Simpsons

SPA sencilla para explorar personajes de *Los Simpson* usando la API pública:
https://thesimpsonsapi.com/api

Proyecto pensado como entrega realista (DAW2): código simple, estructura clara, Bootstrap y funcionalidades básicas pero completas.

## Funcionalidades

- Listado de personajes en cards (imagen, nombre, ocupación, estado)
- Modal de detalle (imagen grande, edad, cumpleaños, frases si existen)
- Búsqueda por nombre
- Filtros por edad y género
- Paginación
- Favoritos con `localStorage`

## Stack

- React + Vite
- React Router DOM
- Bootstrap + React Bootstrap
- Fetch API
- localStorage

## Rutas

- `/` Inicio
- `/characters` Personajes
- `/favorites` Favoritos

## Instalación y uso

```bash
npm install
npm run dev
```

Build de producción:

```bash
npm run build
npm run preview
```

## Estructura

```txt
src/
  components/
    Navbar.jsx
    SearchBar.jsx
    CharacterCard.jsx
    CharacterModal.jsx
  pages/
    Home.jsx
    Characters.jsx
    Favorites.jsx
  services/
    simpsonsService.js
  App.jsx
  main.jsx
```

## Notas

- La API devuelve personajes paginados (20 por página). En esta app, la página de personajes carga todos los personajes y aplica búsqueda/filtros de forma global.
- Las imágenes se sirven desde el CDN de la API.
