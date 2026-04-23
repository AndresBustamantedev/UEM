from models.activity import Activity

class Reservation:
    """
    Representa una reserva realizada por un cliente para una actividad.
    """
    def __init__(self, activity: Activity, price: float):
        self._activity = activity
        self._price = price

    @property
    def activity(self):
        return self._activity

    @property
    def price(self):
        return self._price

    def __str__(self):
        return f"Actividad: {self._activity.name}, Precio: {self._price:.2f}€"

    def to_dict(self):
        return {
            'activity_name': self._activity.name,
            'price': self._price
        }

    @staticmethod
    def from_dict(data, activities_dict):
        """
        Crea una instancia de Reservation desde un diccionario.
        Requiere un diccionario de actividades (name -> Activity object) para enlazar la referencia.
        """
        activity_name = data['activity_name']
        if activity_name not in activities_dict:
            # En un caso real, podríamos manejar esto de otra forma si la actividad fue borrada
            raise ValueError(f"Actividad '{activity_name}' no encontrada para la reserva.")
        
        activity = activities_dict[activity_name]
        return Reservation(activity, data['price'])
