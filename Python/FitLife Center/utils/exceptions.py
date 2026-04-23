class FitLifeException(Exception):
    """Excepción base para la aplicación FitLife Center."""
    pass

class FullCapacityError(FitLifeException):
    """Se lanza cuando una actividad ha alcanzado su aforo máximo."""
    def __init__(self, activity_name):
        super().__init__(f"No hay plazas disponibles para la actividad '{activity_name}'.")

class InvalidUserError(FitLifeException):
    """Se lanza cuando se encuentra un tipo de usuario no válido."""
    pass
