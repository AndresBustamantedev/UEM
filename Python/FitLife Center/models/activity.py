from abc import ABC, abstractmethod
from utils.exceptions import FullCapacityError

class Activity(ABC):
    """
    Clase base abstracta que representa una actividad deportiva.
    """
    def __init__(self, name: str, base_price: float, max_spots: int):
        self._name = name
        self._base_price = base_price
        self._max_spots = max_spots
        self._occupied_spots = 0

    @property
    def name(self):
        return self._name

    @property
    def occupied_spots(self):
        return self._occupied_spots

    @property
    def max_spots(self):
        return self._max_spots

    def check_availability(self) -> bool:
        """Comprueba si hay plazas disponibles."""
        return self._occupied_spots < self._max_spots

    def book_spot(self):
        """Incrementa el contador de plazas ocupadas si hay disponibilidad."""
        if not self.check_availability():
            raise FullCapacityError(self._name)
        self._occupied_spots += 1

    def release_spot(self):
        """Decrementa el contador de plazas ocupadas."""
        if self._occupied_spots > 0:
            self._occupied_spots -= 1

    @abstractmethod
    def calculate_price(self) -> float:
        """Calcula el precio final de la actividad."""
        pass

    def __str__(self):
        return f"{self._name} ({self._occupied_spots}/{self._max_spots} plazas)"

    @abstractmethod
    def to_dict(self):
        pass

    @staticmethod
    def from_dict(data):
        if data['type'] == 'CollectiveClass':
            activity = CollectiveClass(data['name'], data['base_price'], data['max_spots'])
        elif data['type'] == 'PersonalTraining':
            activity = PersonalTraining(data['name'], data['base_price'], data['max_spots'], data['surcharge_percentage'])
        else:
            raise ValueError(f"Tipo de actividad desconocido: {data['type']}")
        
        activity._occupied_spots = data['occupied_spots']
        return activity


class CollectiveClass(Activity):
    """
    Representa una actividad grupal con horario fijo y aforo limitado.
    El precio es el precio base.
    """
    def __init__(self, name: str, base_price: float, max_spots: int):
        super().__init__(name, base_price, max_spots)

    def calculate_price(self) -> float:
        return self._base_price

    def to_dict(self):
        return {
            'type': 'CollectiveClass',
            'name': self._name,
            'base_price': self._base_price,
            'max_spots': self._max_spots,
            'occupied_spots': self._occupied_spots
        }


class PersonalTraining(Activity):
    """
    Representa una sesión de entrenamiento personalizado.
    El precio incluye un recargo.
    """
    def __init__(self, name: str, base_price: float, max_spots: int, surcharge_percentage: float):
        super().__init__(name, base_price, max_spots)
        self._surcharge_percentage = surcharge_percentage

    def calculate_price(self) -> float:
        return self._base_price * (1 + self._surcharge_percentage / 100)

    def to_dict(self):
        return {
            'type': 'PersonalTraining',
            'name': self._name,
            'base_price': self._base_price,
            'max_spots': self._max_spots,
            'surcharge_percentage': self._surcharge_percentage,
            'occupied_spots': self._occupied_spots
        }
