import { useEffect, useState } from "react";
import {
  Alert,
  Badge,
  Button,
  ListGroup,
  Modal,
  Spinner,
} from "react-bootstrap";
import { getCharacterById } from "../services/simpsonsService.js";

// Color del badge según el estado (Alive/Dead).
function getStatusVariant(status) {
  const s = (status ?? "").toLowerCase();
  if (s === "alive") return "success";
  if (s === "dead") return "danger";
  return "secondary";
}

// Modal de detalle (no es una página).
// Cuando se abre (show=true) y hay characterId, hace fetch del personaje completo.
function CharacterModal({
  show,
  onHide,
  characterId,
  isFavorite,
  onToggleFavorite,
}) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [character, setCharacter] = useState(null);

  useEffect(() => {
    // Si el modal está cerrado o no hay id, no hacemos nada.
    if (!show || !characterId) return;

    // Flag simple para evitar setState si el modal se cierra antes de acabar el fetch.
    let cancelled = false;
    setLoading(true);
    setError(null);
    setCharacter(null);

    getCharacterById(characterId)
      .then((data) => {
        if (cancelled) return;
        setCharacter(data);
      })
      .catch((e) => {
        if (cancelled) return;
        setError(e?.message ?? "Error al cargar el personaje");
      })
      .finally(() => {
        if (cancelled) return;
        setLoading(false);
      });

    return () => {
      cancelled = true;
    };
  }, [show, characterId]);

  return (
    <Modal show={show} onHide={onHide} centered size="lg">
      <Modal.Header closeButton>
        <Modal.Title>Detalle del personaje</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        {/* Estado de carga / error / contenido */}
        {loading ? (
          <div className="d-flex justify-content-center py-5">
            <Spinner animation="border" role="status" />
          </div>
        ) : error ? (
          <Alert variant="danger" className="mb-0">
            {error}
          </Alert>
        ) : character ? (
          <div className="d-flex flex-column flex-md-row gap-4">
            <div style={{ width: 220 }} className="mx-auto mx-md-0">
              {/* Imagen grande */}
              {character.imageUrl ? (
                <img
                  src={character.imageUrl}
                  alt={character.name}
                  className="img-fluid rounded shadow-sm"
                />
              ) : null}
            </div>

            <div className="flex-grow-1">
              <div className="d-flex align-items-start justify-content-between gap-2">
                <h2 className="h4 mb-2">{character.name}</h2>
                <Badge bg={getStatusVariant(character.status)}>
                  {character.status ?? "Unknown"}
                </Badge>
              </div>

              <div className="text-muted mb-3">
                {character.occupation || "Sin datos"}
              </div>

              {/* Datos básicos */}
              <ListGroup variant="flush" className="mb-3">
                <ListGroup.Item className="px-0">
                  <strong>Edad:</strong> {character.age ?? "—"}
                </ListGroup.Item>
                <ListGroup.Item className="px-0">
                  <strong>Cumpleaños:</strong> {character.birthdate ?? "—"}
                </ListGroup.Item>
              </ListGroup>

              {/* Algunas frases (si existen) */}
              {Array.isArray(character.phrases) && character.phrases.length ? (
                <>
                  <h3 className="h6 mb-2">Frases famosas</h3>
                  <ul className="mb-0">
                    {character.phrases.slice(0, 5).map((p, idx) => (
                      <li key={`${character.id}-${idx}`}>{p}</li>
                    ))}
                  </ul>
                </>
              ) : (
                <div className="text-muted">Sin frases disponibles.</div>
              )}
            </div>
          </div>
        ) : null}
      </Modal.Body>

      <Modal.Footer>
        <Button variant="outline-dark" onClick={onHide}>
          Cerrar
        </Button>
        {/* Favoritos se gestiona en el padre; el modal solo dispara el callback */}
        <Button
          variant={isFavorite ? "outline-dark" : "dark"}
          onClick={() => character && onToggleFavorite?.(character)}
          disabled={!character}
        >
          {isFavorite ? "Quitar de favoritos" : "Añadir a favoritos"}
        </Button>
      </Modal.Footer>
    </Modal>
  );
}

export default CharacterModal;
