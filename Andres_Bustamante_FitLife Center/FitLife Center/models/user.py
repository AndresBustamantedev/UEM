from abc import ABC, abstractmethod
from typing import List, Optional
from models.activity import Activity
from models.reservation import Reservation
from utils.exceptions import FullCapacityError

class User(ABC):
    """
    Clase base abstracta que representa un usuario genérico en el sistema.
    """
    def __init__(self, name: str, email: str):
        self._name = name
        self._email = email

    @property
    def name(self):
        return self._name

    @property
    def email(self):
        return self._email

    def __str__(self):
        return f"{self._name} <{self._email}>"

    @abstractmethod
    def to_dict(self):
        pass

    @staticmethod
    def from_dict(data, activities_dict=None):
        if data['type'] == 'Client':
            client = Client(data['name'], data['email'])
            # Cargar reservas
            if 'reservations' in data and activities_dict:
                for res_data in data['reservations']:
                    try:
                        reservation = Reservation.from_dict(res_data, activities_dict)
                        client._reservations.append(reservation)
                    except ValueError as e:
                        print(f"Advertencia cargando reserva para {client.name}: {e}")
            return client
        elif data['type'] == 'Trainer':
            return Trainer(data['name'], data['email'], data['specialty'])
        else:
            raise ValueError(f"Tipo de usuario desconocido: {data['type']}")


class Client(User):
    """
    Representa un cliente del centro deportivo.
    Puede realizar reservas y consultarlas.
    """
    def __init__(self, name: str, email: str):
        super().__init__(name, email)
        self._reservations: List[Reservation] = []

    def to_dict(self):
        return {
            'type': 'Client',
            'name': self._name,
            'email': self._email,
            'reservations': [res.to_dict() for res in self._reservations]
        }

    def make_reservation(self, activity: Activity):
        """
        Crea una reserva para la actividad dada si hay plazas disponibles.
        """
        if not activity.check_availability():
            raise FullCapacityError(activity.name)

        # Calcular precio basado en la lógica del tipo de actividad
        price = activity.calculate_price()
        
        # Reservar la plaza
        activity.book_spot()

        # Crear registro de reserva
        reservation = Reservation(activity, price)
        self._reservations.append(reservation)
        print(f"Reserva confirmada para {self.name}: {activity.name} por {price:.2f}€")

    def list_reservations(self):
        """
        Muestra todas las reservas realizadas por el cliente.
        """
        print(f"--- Reservas de {self.name} ---")
        if not self._reservations:
            print("No se encontraron reservas.")
        for i, res in enumerate(self._reservations):
            print(f"{i + 1}. {res}")

    def cancel_reservation(self, activity_name: str):
        """
        Cancela una reserva basada en el nombre de la actividad.
        """
        reservation_to_cancel = None
        for res in self._reservations:
            if res.activity.name == activity_name:
                reservation_to_cancel = res
                break
        
        if reservation_to_cancel:
            # Liberar plaza en la actividad
            reservation_to_cancel.activity.release_spot()
            # Eliminar de la lista de reservas
            self._reservations.remove(reservation_to_cancel)
            print(f"Reserva cancelada para {self.name}: {activity_name}")
        else:
            print(f"No se encontró reserva para la actividad '{activity_name}' en el perfil de {self.name}.")


class Trainer(User):
    """
    Representa un entrenador/empleado del centro deportivo.
    Se especializa en ciertas actividades.
    """
    def __init__(self, name: str, email: str, specialty: str):
        super().__init__(name, email)
        self._specialty = specialty
        # Futura extensión: lista de actividades asignadas

    @property
    def specialty(self):
        return self._specialty

    def __str__(self):
        return f"Entrenador: {self.name}, Especialidad: {self._specialty}"

    def to_dict(self):
        return {
            'type': 'Trainer',
            'name': self._name,
            'email': self._email,
            'specialty': self._specialty
        }
