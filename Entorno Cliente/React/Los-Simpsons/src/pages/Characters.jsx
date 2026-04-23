import { useEffect, useMemo, useState } from "react";
import {
  Alert,
  Button,
  Col,
  Container,
  Form,
  Row,
  Spinner,
} from "react-bootstrap";
import CharacterCard from "../components/CharacterCard.jsx";
import CharacterModal from "../components/CharacterModal.jsx";
import SearchBar from "../components/SearchBar.jsx";
import { getAllCharacters } from "../services/simpsonsService.js";

// Clave de localStorage para guardar favoritos.
const FAVORITES_KEY = "los_simpsons_favorites";
const LEGACY_FAVORITES_KEY = "springfield_favorites";
const CDN_URL = "https://cdn.thesimpsonsapi.com/500";
const PAGE_SIZE = 20;

// Asegura que la imagen apunte al CDN (la API devuelve rutas tipo /character/2.webp).
// También repara favoritos antiguos si se guardaron con una URL vieja.
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

// Lee favoritos desde localStorage (si existe) y valida la estructura.
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

// Guarda el array completo de favoritos en localStorage.
function writeFavorites(list) {
  localStorage.setItem(FAVORITES_KEY, JSON.stringify(list));
}

// Página principal: buscador + listado de personajes.
function Characters() {
  // Datos de la API + estados de UI
  const [allCharacters, setAllCharacters] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [searchInput, setSearchInput] = useState("");
  const [query, setQuery] = useState("");

  // Paginación local
  const [page, setPage] = useState(1);

  // Filtros (cliente)
  const [ageFilter, setAgeFilter] = useState("all");
  const [genderFilter, setGenderFilter] = useState("all");

  // Favoritos persistidos en localStorage
  const [favorites, setFavorites] = useState(() => readFavorites());

  // Modal de detalle
  const [modalOpen, setModalOpen] = useState(false);
  const [selectedId, setSelectedId] = useState(null);

  // Set para comprobar favoritos en O(1) (evitamos buscar en el array constantemente)
  const favoriteIds = useMemo(
    () => new Set(favorites.map((f) => f.id)),
    [favorites],
  );

  // Carga completa de personajes para que búsqueda/filtro sean globales.
  async function loadAllCharacters() {
    setLoading(true);
    setError(null);
    try {
      const first = await getAllCharacters(1);
      const pages = first.pages || 1;
      let merged = Array.isArray(first.results) ? first.results : [];

      if (pages > 1) {
        const tasks = [];
        for (let p = 2; p <= pages; p += 1) {
          tasks.push(getAllCharacters(p));
        }
        const rest = await Promise.all(tasks);
        for (const chunk of rest) {
          merged = merged.concat(
            Array.isArray(chunk.results) ? chunk.results : [],
          );
        }
      }

      setAllCharacters(merged);
    } catch (e) {
      setError(e?.message ?? "Error al cargar personajes");
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    // Al montar la página, cargamos todos los personajes.
    loadAllCharacters();
  }, []);

  // Añade o quita un personaje de favoritos (y lo persiste).
  function toggleFavorite(character) {
    // Guardamos solo lo necesario para mostrar la card en /favorites
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

  // Busca por nombre (global, sobre todos los personajes cargados).
  function onSearchSubmit() {
    setQuery(searchInput.trim().toLowerCase());
    setPage(1);
  }

  // Limpia el input y vuelve a la lista principal.
  function onClear() {
    setSearchInput("");
    setQuery("");
    setPage(1);
  }

  const filteredCharacters = useMemo(() => {
    const byQuery = query
      ? allCharacters.filter((c) =>
          (c.name ?? "").toLowerCase().includes(query),
        )
      : allCharacters;

    const byGender =
      genderFilter === "all"
        ? byQuery
        : byQuery.filter((c) => (c.gender ?? "Unknown") === genderFilter);

    if (ageFilter === "all") return byGender;
    if (ageFilter === "unknown") return byGender.filter((c) => c.age == null);
    if (ageFilter === "u18") {
      return byGender.filter((c) => typeof c.age === "number" && c.age < 18);
    }
    if (ageFilter === "18_39") {
      return byGender.filter(
        (c) => typeof c.age === "number" && c.age >= 18 && c.age <= 39,
      );
    }
    if (ageFilter === "40p") {
      return byGender.filter((c) => typeof c.age === "number" && c.age >= 40);
    }
    return byGender;
  }, [allCharacters, query, genderFilter, ageFilter]);

  const totalPages = Math.max(
    1,
    Math.ceil(filteredCharacters.length / PAGE_SIZE),
  );
  const paginatedCharacters = useMemo(() => {
    const start = (page - 1) * PAGE_SIZE;
    return filteredCharacters.slice(start, start + PAGE_SIZE);
  }, [filteredCharacters, page]);

  useEffect(() => {
    if (page > totalPages) {
      setPage(totalPages);
    }
  }, [page, totalPages]);

  useEffect(() => {
    setPage(1);
  }, [ageFilter, genderFilter]);

  // Abre el modal y guarda el id seleccionado.
  function openModal(id) {
    setSelectedId(id);
    setModalOpen(true);
  }

  // Estado vacío: cuando no hay carga, no hay error y la lista está vacía.
  const noResults = !loading && !error && filteredCharacters.length === 0;

  return (
    <Container className="py-4">
      {/* Cabecera de página + buscador */}
      <div className="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
        <div>
          <h1 className="h3 mb-1">Personajes</h1>
          <div className="text-muted">
            Busca y guarda tus favoritos para verlos después.
          </div>
        </div>
        <div style={{ maxWidth: 520, width: "100%" }}>
          <SearchBar
            value={searchInput}
            onChange={setSearchInput}
            onSubmit={onSearchSubmit}
            onClear={onClear}
            disabled={loading}
          />
          <div className="d-flex flex-column flex-md-row gap-2 mt-2">
            <Form.Select
              value={ageFilter}
              onChange={(e) => setAgeFilter(e.target.value)}
              disabled={loading}
            >
              <option value="all">Edad: Todas</option>
              <option value="u18">Edad: Menores (&lt;18)</option>
              <option value="18_39">Edad: 18-39</option>
              <option value="40p">Edad: 40+</option>
              <option value="unknown">Edad: Sin dato</option>
            </Form.Select>
            <Form.Select
              value={genderFilter}
              onChange={(e) => setGenderFilter(e.target.value)}
              disabled={loading}
            >
              <option value="all">Género: Todos</option>
              <option value="Male">Género: Male</option>
              <option value="Female">Género: Female</option>
              <option value="Unknown">Género: Unknown</option>
            </Form.Select>
          </div>
        </div>
      </div>

      {/* Mensaje de error si algo falla */}
      {error ? <Alert variant="danger">{error}</Alert> : null}

      {/* Loading spinner mientras esperamos respuesta */}
      {loading ? (
        <div className="d-flex justify-content-center py-5">
          <Spinner animation="border" role="status" />
        </div>
      ) : null}

      {/* Estado vacío */}
      {noResults ? (
        <Alert variant="warning">No se encontraron personajes.</Alert>
      ) : null}

      {/* Grid de cards */}
      <Row xs={1} md={2} lg={3} xl={4} className="g-4 mb-5">
        {paginatedCharacters.map((c) => (
          <Col key={c.id}>
            <CharacterCard
              character={c}
              isFavorite={favoriteIds.has(c.id)}
              onViewMore={openModal}
              onToggleFavorite={toggleFavorite}
            />
          </Col>
        ))}
      </Row>

      {/* Paginación */}
      {!loading && !error && filteredCharacters.length > 0 && totalPages > 1 ? (
        <div className="d-flex justify-content-center align-items-center gap-3 mb-4">
          <Button
            variant="outline-dark"
            disabled={page === 1}
            onClick={() => setPage((prev) => prev - 1)}
          >
            Anterior
          </Button>
          <span className="text-muted fw-semibold">
            Página {page} de {totalPages}
          </span>
          <Button
            variant="outline-dark"
            disabled={page === totalPages}
            onClick={() => setPage((prev) => prev + 1)}
          >
            Siguiente
          </Button>
        </div>
      ) : null}

      {/* Modal de detalle (se carga por id cuando se abre) */}
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

export default Characters;
