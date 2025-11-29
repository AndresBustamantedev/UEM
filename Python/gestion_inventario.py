import os

ARCHIVO_INVENTARIO = "inventario.txt"


# ---------- 1. CARGA DEL INVENTARIO ----------

def crear_inventario_inicial():
    """Crea un archivo inventario.txt con algunos productos por defecto."""
    productos_iniciales = [
        ("Portátil", 799.99, 10),
        ("Teléfono", 299.99, 5),
        ("Tablet", 199.99, 0),
    ]

    with open(ARCHIVO_INVENTARIO, "w", encoding="utf-8") as f:
        for nombre, precio, cantidad in productos_iniciales:
            f.write(f"{nombre};{precio};{cantidad}\n")


def cargar_inventario():
    """
    Lee los productos desde inventario.txt.
    Si el archivo no existe, lo crea con un inventario inicial.
    Devuelve una lista de diccionarios con los productos.
    """
    if not os.path.exists(ARCHIVO_INVENTARIO):
        crear_inventario_inicial()

    inventario = []

    with open(ARCHIVO_INVENTARIO, "r", encoding="utf-8") as f:
        for linea in f:
            linea = linea.strip()
            if not linea:
                continue  # Saltar líneas vacías
            partes = linea.split(";")
            if len(partes) != 3:
                continue  # Saltar líneas mal formateadas

            nombre = partes[0]
            precio = float(partes[1])
            cantidad = int(partes[2])

            inventario.append({
                "nombre": nombre,
                "precio": precio,
                "cantidad": cantidad
            })

    return inventario


def guardar_inventario(inventario):
    """Guarda la lista de productos en el archivo inventario.txt."""
    with open(ARCHIVO_INVENTARIO, "w", encoding="utf-8") as f:
        for producto in inventario:
            f.write(f"{producto['nombre']};{producto['precio']};{producto['cantidad']}\n")


# ---------- 2. MOSTRAR INVENTARIO ----------

def mostrar_inventario(inventario):
    """Imprime en pantalla los productos con formato legible."""
    if not inventario:
        print("No hay productos en el inventario.")
        return

    print("\n--- INVENTARIO ACTUAL ---")
    print(f"{'Nº':<4} {'Nombre':<15} {'Precio (€)':<12} {'Cantidad':<8}")
    print("-" * 45)
    for i, producto in enumerate(inventario, start=1):
        print(f"{i:<4} {producto['nombre']:<15} {producto['precio']:<12.2f} {producto['cantidad']:<8}")
    print("-" * 45)


# ---------- 3. CALCULAR VALOR TOTAL DEL INVENTARIO ----------

def calcular_valor_total(inventario):
    """Devuelve el valor total del inventario (precio * cantidad de cada producto)."""
    total = 0.0
    for producto in inventario:
        total += producto["precio"] * producto["cantidad"]
    return total


# ---------- 4. IDENTIFICAR PRODUCTOS AGOTADOS ----------

def mostrar_productos_agotados(inventario):
    """Muestra los productos cuya cantidad es 0."""
    agotados = [p for p in inventario if p["cantidad"] == 0]

    if not agotados:
        print("\nNo hay productos agotados.")
        return

    print("\n--- PRODUCTOS AGOTADOS ---")
    for producto in agotados:
        print(f"- {producto['nombre']}")
    print("--------------------------")


# ---------- 5. ACTUALIZAR CANTIDAD DE UN PRODUCTO ----------

def actualizar_cantidad_producto(inventario):
    """
    Permite al usuario seleccionar un producto y modificar su cantidad.
    Después guarda los cambios en inventario.txt.
    """
    if not inventario:
        print("No hay productos para actualizar.")
        return

    mostrar_inventario(inventario)

    try:
        opcion = int(input("Introduce el número del producto que quieres actualizar: "))
        if opcion < 1 or opcion > len(inventario):
            print("Opción no válida.")
            return
    except ValueError:
        print("Debes introducir un número.")
        return

    producto = inventario[opcion - 1]
    print(f"Has seleccionado: {producto['nombre']} (cantidad actual: {producto['cantidad']})")

    try:
        nueva_cantidad = int(input("Introduce la nueva cantidad: "))
        if nueva_cantidad < 0:
            print("La cantidad no puede ser negativa.")
            return
    except ValueError:
        print("Debes introducir un número entero.")
        return

    producto["cantidad"] = nueva_cantidad
    guardar_inventario(inventario)
    print(f"Cantidad actualizada. Nuevo stock de {producto['nombre']}: {producto['cantidad']}")


# ---------- MENÚ PRINCIPAL ----------

def mostrar_menu():
    print("\n===== GESTIÓN DE INVENTARIO - TIENDA ELECTRÓNICA =====")
    print("1. Mostrar inventario")
    print("2. Calcular valor total del inventario")
    print("3. Mostrar productos agotados")
    print("4. Actualizar cantidad de un producto")
    print("5. Salir")


def main():
    inventario = cargar_inventario()

    while True:
        mostrar_menu()
        opcion = input("Elige una opción: ")

        if opcion == "1":
            mostrar_inventario(inventario)
        elif opcion == "2":
            total = calcular_valor_total(inventario)
            print(f"\nEl valor total del inventario es: {total:.2f} €")
        elif opcion == "3":
            mostrar_productos_agotados(inventario)
        elif opcion == "4":
            actualizar_cantidad_producto(inventario)
        elif opcion == "5":
            print("Saliendo del programa...")
            break
        else:
            print("Opción no válida. Intenta de nuevo.")


if __name__ == "__main__":
    main()
