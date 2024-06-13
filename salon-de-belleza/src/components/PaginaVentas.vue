<template>
    <div>
      <!-- Mostrar la lista de ventas -->
      <ul>
        <li v-for="venta in ventas" :key="venta.id_venta">
          {{ venta.id_venta }} - {{ venta.id_usuario }} - {{ venta.fecha }} - Bs.{{ venta.total }} - {{ venta.metodo_pago }}
          <button @click="eliminarVenta(venta.id_venta)">Eliminar</button>
          <button @click="cargarVentaParaActualizar(venta)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar una venta -->
      <form @submit.prevent="agregarOActualizarVenta">
        <input v-model="nuevaVenta.id_usuario" placeholder="ID de usuario" required>
        <input v-model="nuevaVenta.fecha" type="date" placeholder="Fecha" required>
        <input v-model="nuevaVenta.total" placeholder="Total" required>
        <select v-model="nuevaVenta.metodo_pago" required>
        <option value="">Seleccionar metodo pago</option>
        <option value="efectivo">efectivo</option>
        <option value="credito">credito</option>
        </select>
        <button type="submit">{{ actualizando ? 'Actualizar Venta' : 'Agregar Venta' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        ventas: [],
        nuevaVenta: {
          id_venta: null,
          id_usuario: '',
          fecha: '',
          total: '',
          metodo_pago: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando una venta
      };
    },
    mounted() {
      this.obtenerVentas();
    },
    methods: {
      obtenerVentas() {
        console.log('Obteniendo ventas...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/ventas.php?action=obtenerVentas')
          .then(response => {
            console.log('Ventas obtenidas:', response.data);
            this.ventas = response.data;
          })
          .catch(error => {
            console.error('Error al obtener ventas:', error);
          });
      },
      agregarOActualizarVenta() {
        console.log('Enviando formulario...', this.nuevaVenta);
        const action = this.actualizando ? 'actualizarVenta' : 'crearVenta';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/ventas.php?action=${action}`, this.nuevaVenta, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Venta actualizada' : 'Venta agregada'}:`, response.data);
            this.obtenerVentas();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} venta:`, error);
          });
      },
      eliminarVenta(idVenta) {
        console.log('Eliminando venta con ID:', idVenta);
        axios.post('http://localhost/Proyecto_Software_Final/backend/ventas.php?action=eliminarVenta', { id_venta: idVenta }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Venta eliminada:', response.data);
            this.obtenerVentas();
          })
          .catch(error => {
            console.error('Error al eliminar venta:', error);
          });
      },
      cargarVentaParaActualizar(venta) {
        console.log('Cargando venta para actualizar:', venta);
        this.nuevaVenta = { ...venta }; // Copiar los datos de la venta al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevaVenta = {
          id_venta: null,
          id_usuario: '',
          fecha: '',
          total: '',
          metodo_pago: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  