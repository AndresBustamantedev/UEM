import sys
from typing import List
from models.user import Client, Trainer, User
from models.activity import CollectiveClass, PersonalTraining, Activity
from utils.exceptions import FullCapacityError
from utils.data_manager import DataManager

class FitLifeApp:
    def __init__(self):
        self.users: List[User] = []
        self.activities: List[Activity] = []
        self._load_data()

    def _load_data(self):
        """Carga los datos iniciales usando DataManager."""
        self.users, self.activities = DataManager.load_data()

    def _save_data(self):
        """Guarda los datos actuales usando DataManager."""
        DataManager.save_data(self.users, self.activities)

    def _find_user_by_email(self, email):
        for user in self.users:
            if user.email == email:
                return user
        return None

    def _find_activity_by_name(self, name):
        for activity in self.activities:
            if activity.name == name:
                return activity
        return None

    def create_user(self):
        print("\n--- Crear Usuario ---")
        type_choice = input("Tipo de usuario (1: Cliente, 2: Entrenador): ")
        name = input("Nombre: ")
        email = input("Email: ")

        if self._find_user_by_email(email):
            print("Error: Ya existe un usuario con ese email.")
            return

        if type_choice == '1':
            new_user = Client(name, email)
        elif type_choice == '2':
            specialty = input("Especialidad: ")
            new_user = Trainer(name, email, specialty)
        else:
            print("Opción no válida.")
            return

        self.users.append(new_user)
        self._save_data()
        print(f"Usuario {name} creado correctamente.")

    def create_activity(self):
        print("\n--- Crear Actividad ---")
        type_choice = input("Tipo de actividad (1: Colectiva, 2: Entrenamiento Personal): ")
        name = input("Nombre de la actividad: ")
        
        if self._find_activity_by_name(name):
            print("Error: Ya existe una actividad con ese nombre.")
            return

        try:
            base_price = float(input("Precio base: "))
            max_spots = int(input("Plazas máximas: "))
        except ValueError:
            print("Error: El precio y las plazas deben ser números.")
            return

        if type_choice == '1':
            new_activity = CollectiveClass(name, base_price, max_spots)
        elif type_choice == '2':
            try:
                surcharge = float(input("Porcentaje de recargo (ej. 20 para 20%): "))
                new_activity = PersonalTraining(name, base_price, max_spots, surcharge)
            except ValueError:
                print("Error: El recargo debe ser un número.")
                return
        else:
            print("Opción no válida.")
            return

        self.activities.append(new_activity)
        self._save_data()
        print(f"Actividad {name} creada correctamente.")

    def make_reservation(self):
        print("\n--- Realizar Reserva ---")
        email = input("Email del cliente: ")
        client = self._find_user_by_email(email)

        if not client:
            print("Error: Cliente no encontrado.")
            return
        
        if not isinstance(client, Client):
            print("Error: El usuario no es un cliente.")
            return

        print("\nActividades disponibles:")
        for idx, act in enumerate(self.activities):
            print(f"{idx + 1}. {act}")

        activity_name = input("Nombre de la actividad a reservar: ")
        activity = self._find_activity_by_name(activity_name)

        if not activity:
            print("Error: Actividad no encontrada.")
            return

        try:
            client.make_reservation(activity)
            self._save_data()
        except FullCapacityError as e:
            print(f"Error: {e}")
        except Exception as e:
            print(f"Error inesperado: {e}")

    def cancel_reservation(self):
        print("\n--- Cancelar Reserva ---")
        email = input("Email del cliente: ")
        client = self._find_user_by_email(email)

        if not client or not isinstance(client, Client):
            print("Error: Cliente no válido.")
            return

        client.list_reservations()
        activity_name = input("Nombre de la actividad a cancelar: ")
        
        client.cancel_reservation(activity_name)
        self._save_data()

    def list_users(self):
        print("\n--- Listado de Usuarios ---")
        if not self.users:
            print("No hay usuarios registrados.")
        for user in self.users:
            print(user)

    def list_activities(self):
        print("\n--- Listado de Actividades ---")
        if not self.activities:
            print("No hay actividades registradas.")
        for activity in self.activities:
            print(activity)

    def list_client_reservations(self):
        print("\n--- Ver Reservas de Cliente ---")
        email = input("Email del cliente: ")
        client = self._find_user_by_email(email)

        if not client or not isinstance(client, Client):
            print("Error: Cliente no válido.")
            return
        
        client.list_reservations()

    def run(self):
        while True:
            print("\n=== Menú Principal FitLife Center ===")
            print("1. Crear Usuario")
            print("2. Crear Actividad")
            print("3. Realizar Reserva")
            print("4. Cancelar Reserva")
            print("5. Listar Usuarios")
            print("6. Listar Actividades")
            print("7. Ver Reservas de un Cliente")
            print("8. Salir")
            
            option = input("Seleccione una opción: ")

            if option == '1':
                self.create_user()
            elif option == '2':
                self.create_activity()
            elif option == '3':
                self.make_reservation()
            elif option == '4':
                self.cancel_reservation()
            elif option == '5':
                self.list_users()
            elif option == '6':
                self.list_activities()
            elif option == '7':
                self.list_client_reservations()
            elif option == '8':
                print("Guardando datos y saliendo...")
                self._save_data()
                print("¡Hasta luego!")
                break
            else:
                print("Opción no válida. Inténtelo de nuevo.")

if __name__ == "__main__":
    app = FitLifeApp()
    app.run()
