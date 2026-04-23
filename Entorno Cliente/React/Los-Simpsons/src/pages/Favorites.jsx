import { useMemo, useState } from "react";
import { Alert, Button, Col, Row, Container } from "react-bootstrap";
import { Link } from "react-router-dom";
import CharacterCard from "../components/CharacterCard.jsx";
import CharacterModal from "../components/CharacterModal.jsx";

// Clave de localStorage para favoritos.
const FAVORITES_KEY = "los_simpsons_favorites";
const LEGACY_FAVORITES_KEY = "springfield_favorites";
const CDN_URL = "https://cdn.thesimpsonsapi.com/500";

// Repara URLs antiguas y asegura que las imágenes apunten al CDN.
function fixImageUrl(url) {
  if (!url) return url;
  if (url.startsWith("http://") || url.startsWith("https://")) {
    if (url.startsWith("https://thesimpsonsapi.com/")) {
      try {
        const u = new URL(url);
        return `${CDN_URL}${u.pathname}`;
      } catch {
        return url;
      }
    }
    return url;
  }
  if (url.startsWith("/")) return `${CDN_URL}${url}`;
  return `${CDN_URL}/${url}`;
}

// Lee favoritos desde localStorage.
function readFavorites() {
  try {
    const raw =
      localStorage.getItem(FAVORITES_KEY) ??
      localStorage.getItem(LEGACY_FAVORITES_KEY);
    const parsed = raw ? JSON.parse(raw) : [];
    const list = Array.isArray(parsed) ? parsed : [];
    const fixed = list.map((f) => ({
      ...f,
      imageUrl: fixImageUrl(f.imageUrl),
    }));

    localStorage.setItem(FAVORITES_KEY, JSON.stringify(fixed));
    if (localStorage.getItem(LEGACY_FAVORITES_KEY)) {
      localStorage.removeItem(LEGACY_FAVORITES_KEY);
    }

    return fixed;
  } catch {
    return [];
  }
}

// Guarda favoritos en localStorage.
function writeFavorites(list) {
  localStorage.setItem(FAVORITES_KEY, JSON.stringify(list));
}

// Página de Favoritos: muestra lo guardado en localStorage.
function Favorites() {
  // Cargamos favoritos una sola vez al montar (lazy init).
  const [favorites, setFavorites] = useState(() => readFavorites());
  // Set para comprobar si un id está en favoritos rápidamente.
  const favoriteIds = useMemo(
    () => new Set(favorites.map((f) => f.id)),
    [favorites],
  );

  // Modal de detalle (usamos id para pedir el detalle a la API).
  const [modalOpen, setModalOpen] = useState(false);
  const [selectedId, setSelectedId] = useState(null);

  // En favoritos, este botón básicamente elimina (toggle).
  function toggleFavorite(character) {
    // Si viene un personaje del modal (detalle), también lo convertimos a estructura mínima.
    const minimal = {
      id: character.id,
      name: character.name,
      occupation: character.occupation,
      status: character.status,
      imageUrl: character.imageUrl,
    };

    setFavorites((prev) => {
      const exists = prev.some((f) => f.id === minimal.id);
      const next = exists
        ? prev.filter((f) => f.id !== minimal.id)
        : [minimal, ...prev];
      writeFavorites(next);
      return next;
    });
  }

  // Abre el modal con el personaje seleccionado.
  function openModal(id) {
    setSelectedId(id);
    setModalOpen(true);
  }

  // Si no hay favoritos, mostramos un mensaje y un botón para ir a Personajes.
  if (favorites.length === 0) {
    return (
      <Container className="py-4">
        <Alert
          variant="info"
          className="d-flex align-items-center justify-content-between gap-3"
        >
          <div>
            <strong>No hay favoritos todavía.</strong>
            <div className="text-muted">
              Guarda personajes para verlos aquí.
            </div>
          </div>
          <Button as={Link} to="/characters" variant="dark">
            Ir a personajes
          </Button>
        </Alert>
      </Container>
    );
  }

  return (
    <Container className="py-4">
      {/* Cabecera de la página */}
      <div className="mb-4">
        <h1 className="h3 mb-1">Favoritos</h1>
        <div className="text-muted">
          Tus personajes guardados en localStorage.
        </div>
      </div>

      {/* Grid de cards con los favoritos */}
      <Row xs={1} md={2} lg={3} xl={4} className="g-4">
        {favorites.map((c) => (
          <Col key={c.id}>
            <CharacterCard
              character={c}
              isFavorite={true}
              onViewMore={openModal}
              onToggleFavorite={toggleFavorite}
            />
          </Col>
        ))}
      </Row>

      {/* Modal de detalle reutilizado (mismo que en /characters) */}
      <CharacterModal
        show={modalOpen}
        onHide={() => setModalOpen(false)}
        characterId={selectedId}
        isFavorite={selectedId ? favoriteIds.has(selectedId) : false}
        onToggleFavorite={toggleFavorite}
      />
    </Container>
  );
}

export default Favorites;
