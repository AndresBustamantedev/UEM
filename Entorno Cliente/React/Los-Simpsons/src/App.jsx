import { Container } from 'react-bootstrap'
import { Navigate, Route, Routes } from 'react-router-dom'
import AppNavbar from './components/Navbar.jsx'
import Characters from './pages/Characters.jsx'
import Favorites from './pages/Favorites.jsx'
import Home from './pages/Home.jsx'

function App() {
  return (
    <>
      <AppNavbar />
      <main>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/characters" element={<Characters />} />
          <Route path="/favorites" element={<Favorites />} />
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </main>
    </>
  )
}

export default App
