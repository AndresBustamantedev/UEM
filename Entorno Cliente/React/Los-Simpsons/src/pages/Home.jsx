import { Button, Card, Col, Row, Container } from "react-bootstrap";
import { Link } from "react-router-dom";
import bannerImg from "../assets/banner.png";

// Página de inicio: presentación del proyecto y CTA para ir a Personajes.
function Home() {
  return (
    <div className="d-flex flex-column gap-4 pb-4">
      <div
        className="position-relative w-100"
        style={{ height: "60vh", minHeight: "400px" }}
      >
        <img
          src={bannerImg}
          alt="Los Simpsons"
          className="w-100 h-100 object-fit-cover"
        />
        <div className="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
          <div className="text-center px-3" style={{ maxWidth: "800px" }}>
            <h1 className="display-3 fw-bold mb-3 text-shadow text-dark">
              Los Simpsons
            </h1>
            <p className="lead mb-4 text-shadow text-dark">
              Explora los personajes de los simpson usando una API publica
            </p>
            <div className="d-flex flex-wrap justify-content-center gap-3">
              <Button as={Link} to="/characters" variant="dark" size="lg">
                Empezar
              </Button>
              <Button
                as="a"
                href="https://thesimpsonsapi.com/"
                target="_blank"
                rel="noreferrer"
                variant="light"
                size="lg"
              >
                Ver API
              </Button>
            </div>
          </div>
        </div>
      </div>

      <Container>
        <h2 className="h5 fw-semibold text-center mb-1 mt-4">Resumen</h2>
        <div className="text-muted text-center mb-3">
          Acceso rápido a las partes principales de la aplicación
        </div>
        <Row xs={1} md={3} className="g-4">
          <Col>
            <Card className="h-100 shadow-sm">
              <Card.Body>
                <Card.Title className="h5">Personajes</Card.Title>
                <Card.Text className="text-muted mb-0">
                  Lista con buscador, cards y botón para ver el detalle en un
                  modal.
                </Card.Text>
              </Card.Body>
            </Card>
          </Col>
          <Col>
            <Card className="h-100 shadow-sm">
              <Card.Body>
                <Card.Title className="h5">Favoritos</Card.Title>
                <Card.Text className="text-muted mb-0">
                  Guarda tus personajes en localStorage y recupéralos cuando
                  vuelvas.
                </Card.Text>
              </Card.Body>
            </Card>
          </Col>
          <Col>
            <Card className="h-100 shadow-sm">
              <Card.Body>
                <Card.Title className="h5">Detalle</Card.Title>
                <Card.Text className="text-muted mb-0">
                  Imagen grande, edad, cumpleaños, ocupación y frases (si
                  existen).
                </Card.Text>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </Container>
    </div>
  );
}

export default Home;
