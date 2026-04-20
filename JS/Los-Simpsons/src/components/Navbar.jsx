import { Container, Nav, Navbar } from "react-bootstrap";
import { NavLink } from "react-router-dom";

// Barra de navegación principal de la app.
// Usa componentes de Bootstrap y NavLink para resaltar la ruta activa.
function AppNavbar() {
  return (
    <Navbar expand="md" sticky="top" style={{ backgroundColor: "#e6f3ffff" }}>
      <Container>
        {/* Logo / nombre del proyecto (vuelve al inicio) */}
        <Navbar.Brand
          as={NavLink}
          to="/"
          className="fw-bold"
          style={{ fontSize: "1.4rem" }}
        >
          Los Simpsons
        </Navbar.Brand>
        {/* Botón hamburguesa para móvil */}
        <Navbar.Toggle aria-controls="main-nav" />
        <Navbar.Collapse id="main-nav">
          {/* Enlaces principales */}
          <Nav className="ms-auto" style={{ fontSize: "1.15rem" }}>
            <Nav.Link as={NavLink} to="/" end>
              Inicio
            </Nav.Link>
            <Nav.Link as={NavLink} to="/characters">
              Personajes
            </Nav.Link>
            <Nav.Link as={NavLink} to="/favorites">
              Favoritos
            </Nav.Link>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
}

export default AppNavbar;
