import json
import os
from models.activity import Activity
from models.user import User

class DataManager:
    """
    Clase responsable de guardar y cargar los datos de la aplicación en ficheros JSON.
    """
    ACTIVITIES_FILE = "activities.json"
    USERS_FILE = "users.json"

    @staticmethod
    def save_data(users, activities):
        """
        Guarda la lista de usuarios y actividades en ficheros JSON.
        """
        # Guardar Actividades
        activities_data = [activity.to_dict() for activity in activities]
        try:
            with open(DataManager.ACTIVITIES_FILE, 'w', encoding='utf-8') as f:
                json.dump(activities_data, f, indent=4, ensure_ascii=False)
            print("Datos de actividades guardados correctamente.")
        except Exception as e:
            print(f"Error guardando actividades: {e}")

        # Guardar Usuarios
        users_data = [user.to_dict() for user in users]
        try:
            with open(DataManager.USERS_FILE, 'w', encoding='utf-8') as f:
                json.dump(users_data, f, indent=4, ensure_ascii=False)
            print("Datos de usuarios guardados correctamente.")
        except Exception as e:
            print(f"Error guardando usuarios: {e}")

    @staticmethod
    def load_data():
        """
        Carga los usuarios y actividades desde ficheros JSON.
        Devuelve una tupla (lista_usuarios, lista_actividades).
        """
        activities = []
        users = []
        activities_dict = {}

        # Cargar Actividades primero
        if os.path.exists(DataManager.ACTIVITIES_FILE):
            try:
                with open(DataManager.ACTIVITIES_FILE, 'r', encoding='utf-8') as f:
                    activities_data = json.load(f)
                    for act_data in activities_data:
                        try:
                            activity = Activity.from_dict(act_data)
                            activities.append(activity)
                            activities_dict[activity.name] = activity
                        except ValueError as e:
                            print(f"Error cargando actividad: {e}")
                print(f"Cargadas {len(activities)} actividades.")
            except Exception as e:
                print(f"Error leyendo fichero de actividades: {e}")
        else:
            print("No se encontró fichero de actividades. Se iniciará vacío.")

        # Cargar Usuarios (y enlazar reservas con actividades)
        if os.path.exists(DataManager.USERS_FILE):
            try:
                with open(DataManager.USERS_FILE, 'r', encoding='utf-8') as f:
                    users_data = json.load(f)
                    for user_data in users_data:
                        try:
                            user = User.from_dict(user_data, activities_dict)
                            users.append(user)
                        except ValueError as e:
                            print(f"Error cargando usuario: {e}")
                print(f"Cargados {len(users)} usuarios.")
            except Exception as e:
                print(f"Error leyendo fichero de usuarios: {e}")
        else:
            print("No se encontró fichero de usuarios. Se iniciará vacío.")

        return users, activities
