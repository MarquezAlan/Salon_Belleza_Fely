<template>
    <div>
      <!-- Mostrar la lista de inventarios -->
      <ul>
        <li v-for="inventario in inventarios" :key="inventario.id_inventario">
          {{ inventario.id_inventario }} - {{ inventario.id_producto }} - {{ inventario.cantidad }} - {{ inventario.fecha_actualizacion }}
          <button @click="eliminarInventario(inventario.id_inventario)">Eliminar</button>
          <button @click="cargarInventarioParaActualizar(inventario)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un inventario -->
      <form @submit.prevent="agregarOActualizarInventario">
        <input v-model="nuevoInventario.id_producto" placeholder="ID Producto" required>
        <input v-model="nuevoInventario.cantidad" placeholder="Cantidad" required>
        <input v-model="nuevoInventario.fecha_actualizacion" placeholder="Fecha (AAAA/MM/DD)" required>
        <button type="submit">{{ actualizando ? 'Actualizar Inventario' : 'Agregar Inventario' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        inventarios: [],
        nuevoInventario: {
          id_inventario: null,
          id_producto: '',
          cantidad: '',
          fecha_actualizacion: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un inventario
      };
    },
    mounted() {
      this.obtenerInventarios();
    },
    methods: {
      obtenerInventarios() {
        console.log('Obteniendo inventarios...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/inventarios.php?action=obtenerInventarios')
          .then(response => {
            console.log('Inventarios obtenidos:', response.data);
            this.inventarios = response.data;
          })
          .catch(error => {
            console.error('Error al obtener inventarios:', error);
          });
      },
      agregarOActualizarInventario() {
        console.log('Enviando formulario...', this.nuevoInventario);
        const action = this.actualizando ? 'actualizarInventario' : 'crearInventario';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/inventarios.php?action=${action}`, this.nuevoInventario, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Inventario actualizado' : 'Inventario agregado'}:`, response.data);
            this.obtenerInventarios();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} inventario:`, error);
          });
      },
      eliminarInventario(idInventario) {
        console.log('Eliminando inventario con ID:', idInventario);
        axios.post('http://localhost/Proyecto_Software_Final/backend/inventarios.php?action=eliminarInventario', { id_inventario: idInventario }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Inventario eliminado:', response.data);
            this.obtenerInventarios();
          })
          .catch(error => {
            console.error('Error al eliminar inventario:', error);
          });
      },
      cargarInventarioParaActualizar(inventario) {
        console.log('Cargando inventario para actualizar:', inventario);
        this.nuevoInventario = { ...inventario }; // Copiar los datos del inventario al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoInventario = {
          id_inventario: null,
          id_producto: '',
          cantidad: '',
          fecha_actualizacion: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  