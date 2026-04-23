import { Badge, Button, Card } from "react-bootstrap";

// Traduce el texto de estado a un color de Badge de Bootstrap.
function getStatusVariant(status) {
  const s = (status ?? "").toLowerCase();
  if (s === "alive") return "success";
  if (s === "dead") return "danger";
  return "secondary";
}

// Card simple para mostrar un personaje.
// Toda la lógica (abrir modal, favoritos, etc.) la gestiona el padre mediante callbacks.
function CharacterCard({
  character,
  isFavorite,
  onViewMore,
  onToggleFavorite,
}) {
  const occupation = character.occupation || "Sin datos";

  return (
    <Card className="h-100 shadow-sm">
      {/* Imagen cuadrada (ratio 1:1). Si no hay imagen, queda el fondo */}
      <div className="ratio ratio-1x1 bg-light">
        {character.imageUrl ? (
          <img
            src={character.imageUrl}
            alt={character.name}
            className="w-100 h-100"
            style={{ objectFit: "cover" }}
            loading="lazy"
          />
        ) : null}
      </div>
      <Card.Body className="d-flex flex-column">
        <div className="d-flex align-items-start justify-content-between gap-2">
          <Card.Title className="mb-1">{character.name}</Card.Title>
          <Badge bg={getStatusVariant(character.status)}>
            {character.status ?? "Unknown"}
          </Badge>
        </div>
        <Card.Text className="text-muted mb-3">{occupation}</Card.Text>

        {/* Acciones: abrir modal y añadir/quitar favorito */}
        <div className="mt-auto d-flex gap-2">
          <Button
            variant="outline-dark"
            onClick={() => onViewMore?.(character.id)}
          >
            Ver más
          </Button>
          <Button
            variant={isFavorite ? "outline-dark" : "dark"}
            onClick={() => onToggleFavorite?.(character)}
          >
            {isFavorite ? "Quitar de favoritos" : "Añadir a favoritos"}
          </Button>
        </div>
      </Card.Body>
    </Card>
  );
}

export default CharacterCard;
