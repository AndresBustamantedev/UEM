import { Button, Form, InputGroup } from "react-bootstrap";

// Buscador reutilizable: recibe el valor y callbacks desde la página.
// No hace fetch por sí mismo; solo gestiona la UI del formulario.
function SearchBar({ value, onChange, onSubmit, onClear, disabled }) {
  return (
    <Form
      onSubmit={(e) => {
        // Evitamos recargar la página al enviar el form
        e.preventDefault();
        onSubmit?.();
      }}
    >
      <InputGroup>
        {/* Input controlado por el estado de la página (value/onChange) */}
        <Form.Control
          placeholder="Buscar por nombre... (ej: Homer)"
          value={value}
          onChange={(e) => onChange?.(e.target.value)}
          disabled={disabled}
        />
        {/* Botón de limpieza rápida */}
        <Button
          variant="outline-dark"
          type="button"
          onClick={() => onClear?.()}
          disabled={disabled || !value}
        >
          Limpiar
        </Button>
        {/* Submit del formulario */}
        <Button variant="dark" type="submit" disabled={disabled}>
          Buscar
        </Button>
      </InputGroup>
    </Form>
  );
}

export default SearchBar;
