import { Component, inject, signal, computed, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { Product, ProductsResponse } from './models/product.model';
import Swal from 'sweetalert2';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App implements OnInit {
  // Inyección del servicio HttpClient para hacer peticiones HTTP
  private http = inject(HttpClient);

  // Datos (Signals para reactividad)
  products = signal<Product[]>([]); // Almacena todos los productos
  cart = signal<Product[]>([]);     // Almacena los productos del carrito

  // Filtros (Signals para los valores de los filtros)
  minPrice = signal<number>(0);
  selectedCategory = signal<string>('');
  selectedBrand = signal<string>('');

  // Propiedades Computadas para los desplegables (se actualizan automáticamente cuando cambian los productos)
  // Crea una lista única de categorías
  categories = computed(() => [...new Set(this.products().map(p => p.category))]);
  // Crea una lista única de marcas
  brands = computed(() => [...new Set(this.products().map(p => p.brand))]);

  // Productos Filtrados (Signal que almacena los productos que cumplen los criterios)
  filteredProducts = signal<Product[]>([]);

  ngOnInit() {
    this.fetchProducts(); // Cargar productos al iniciar
  }

  fetchProducts() {
    // Obteniendo 100 productos para tener suficiente variedad para los filtros
    this.http.get<ProductsResponse>('https://dummyjson.com/products?limit=100')
      .subscribe({
        next: (res) => {
          this.products.set(res.products);
          this.filteredProducts.set(res.products); // Inicialmente mostramos todos
        },
        error: (err) => console.error('Error cargando productos', err)
      });
  }

  applyFilters() {
    // Obtener valores actuales de los signals
    const min = this.minPrice();
    const cat = this.selectedCategory();
    const brand = this.selectedBrand();

    // Filtrar la lista original de productos
    const filtered = this.products().filter(p => {
      const matchPrice = p.price >= min;
      const matchCat = cat ? p.category === cat : true;
      const matchBrand = brand ? p.brand === brand : true;
      return matchPrice && matchCat && matchBrand;
    });

    // Actualizar la lista visible
    this.filteredProducts.set(filtered);
  }

  addToCart(product: Product) {
    // Añadir producto al array del carrito
    this.cart.update(curr => [...curr, product]);
  }

  removeFromCart(index: number) {
      // Eliminar producto del carrito por su índice
      this.cart.update(curr => curr.filter((_, i) => i !== index));
  }
  
  // Calcular total del carrito automáticamente
  cartTotal = computed(() => this.cart().reduce((acc, p) => acc + p.price, 0));

  buy() {
    const total = this.cartTotal();
    // Mostrar alerta de confirmación con SweetAlert2
    Swal.fire({
      title: 'Confirmar compra',
      text: `Vas a realizar una compra por valor de ${total.toFixed(2)}. ¿Estás seguro?`,
      imageUrl: 'logo.png', // Logo de la tienda
      imageWidth: 200,
      imageHeight: 'auto',
      imageAlt: 'Logo Tienda',
      showCancelButton: true,
      confirmButtonText: 'Aceptar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#e6441d', // Color naranja personalizado
      cancelButtonColor: '#3085d6'
    }).then((result) => {
      if (result.isConfirmed) {
        // Si confirma, vaciar carrito y mostrar éxito
        this.cart.set([]);
        Swal.fire({
          title: '¡Compra realizada!',
          text: 'Tu carrito ha sido vaciado.',
          icon: 'success',
          confirmButtonColor: '#e6441d'
        });
      }
    });
  }
}
