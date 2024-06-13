<template>
    <div>
      <!-- Mostrar la lista de productos -->
      <ul>
        <li v-for="producto in productos" :key="producto.id_producto">
          {{ producto.id_producto }} - {{ producto.nombre }} - {{ producto.descripcion }} - Bs.{{ producto.precio }} - {{ producto.stock }} Unidades. - {{ producto.id_categoria }}
          <button @click="eliminarProducto(producto.id_producto)">Eliminar</button>
          <button @click="cargarProductoParaActualizar(producto)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un producto -->
      <form @submit.prevent="agregarOActualizarProducto">
        <input v-model="nuevoProducto.nombre" placeholder="Nombre del producto" required>
        <textarea v-model="nuevoProducto.descripcion" placeholder="Descripción del producto" required></textarea>
        <input v-model="nuevoProducto.precio" type="number" step="0.01" placeholder="Precio del producto" required>
        <input v-model="nuevoProducto.stock" type="number" placeholder="Stock del producto" required>
        <input v-model="nuevoProducto.id_categoria" type="number" placeholder="ID de la categoría" required>
        <button type="submit">{{ actualizando ? 'Actualizar Producto' : 'Agregar Producto' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        productos: [],
        nuevoProducto: {
          id_producto: null,
          nombre: '',
          descripcion: '',
          precio: null,
          stock: null,
          id_categoria: null
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un producto
      };
    },
    mounted() {
      this.obtenerProductos();
    },
    methods: {
      obtenerProductos() {
        console.log('Obteniendo productos...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/productos.php?action=obtenerProductos')
          .then(response => {
            console.log('Productos obtenidos:', response.data);
            this.productos = response.data;
          })
          .catch(error => {
            console.error('Error al obtener productos:', error);
          });
      },
      agregarOActualizarProducto() {
        console.log('Enviando formulario...', this.nuevoProducto);
        const action = this.actualizando ? 'actualizarProducto' : 'crearProducto';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/productos.php?action=${action}`, this.nuevoProducto, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Producto actualizado' : 'Producto agregado'}:`, response.data);
            this.obtenerProductos();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} producto:`, error);
          });
      },
      eliminarProducto(idProducto) {
        console.log('Eliminando producto con ID:', idProducto);
        axios.post('http://localhost/Proyecto_Software_Final/backend/productos.php?action=eliminarProducto', { id_producto: idProducto }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Producto eliminado:', response.data);
            this.obtenerProductos();
          })
          .catch(error => {
            console.error('Error al eliminar producto:', error);
          });
      },
      cargarProductoParaActualizar(producto) {
        console.log('Cargando producto para actualizar:', producto);
        this.nuevoProducto = { ...producto }; // Copiar los datos del producto al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoProducto = {
          id_producto: null,
          nombre: '',
          descripcion: '',
          precio: null,
          stock: null,
          id_categoria: null
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  