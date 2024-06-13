<template>
    <div>
      <!-- Mostrar la lista de detalles de ventas -->
      <ul>
        <li v-for="detalle in detallesVentas" :key="detalle.id_detalle">
          {{ detalle.id_detalle }} - {{ detalle.id_venta }} - {{ detalle.id_producto }} - {{ detalle.cantidad }} Unidades - Bs.{{ detalle.precio_unitario }}
          <button @click="eliminarDetalleVenta(detalle.id_detalle)">Eliminar</button>
          <button @click="cargarDetalleVentaParaActualizar(detalle)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un detalle de venta -->
      <form @submit.prevent="agregarOActualizarDetalleVenta">
        <input v-model="nuevoDetalleVenta.id_venta" placeholder="ID Venta" required>
        <input v-model="nuevoDetalleVenta.id_producto" placeholder="ID Producto" required>
        <input v-model="nuevoDetalleVenta.cantidad" type="number" placeholder="Cantidad" required>
        <input v-model="nuevoDetalleVenta.precio_unitario" type="number" step="0.01" placeholder="Precio Unitario" required>
        <button type="submit">{{ actualizando ? 'Actualizar Detalle Venta' : 'Agregar Detalle Venta' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        detallesVentas: [],
        nuevoDetalleVenta: {
          id_detalle: null,
          id_venta: null,
          id_producto: null,
          cantidad: null,
          precio_unitario: null
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un detalle de venta
      };
    },
    mounted() {
      this.obtenerDetallesVentas();
    },
    methods: {
      obtenerDetallesVentas() {
        console.log('Obteniendo detalles de ventas...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/detallesventas.php?action=obtenerDetallesVentas')
          .then(response => {
            console.log('Detalles de ventas obtenidos:', response.data);
            this.detallesVentas = response.data;
          })
          .catch(error => {
            console.error('Error al obtener detalles de ventas:', error);
          });
      },
      agregarOActualizarDetalleVenta() {
        console.log('Enviando formulario...', this.nuevoDetalleVenta);
        const action = this.actualizando ? 'actualizarDetalleVenta' : 'crearDetalleVenta';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/detallesventas.php?action=${action}`, this.nuevoDetalleVenta, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Detalle de venta actualizado' : 'Detalle de venta agregado'}:`, response.data);
            this.obtenerDetallesVentas();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} detalle de venta:`, error);
          });
      },
      eliminarDetalleVenta(idDetalle) {
        console.log('Eliminando detalle de venta con ID:', idDetalle);
        axios.post('http://localhost/Proyecto_Software_Final/backend/detallesventas.php?action=eliminarDetalleVenta', { id_detalle: idDetalle }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Detalle de venta eliminado:', response.data);
            this.obtenerDetallesVentas();
          })
          .catch(error => {
            console.error('Error al eliminar detalle de venta:', error);
          });
      },
      cargarDetalleVentaParaActualizar(detalle) {
        console.log('Cargando detalle de venta para actualizar:', detalle);
        this.nuevoDetalleVenta = { ...detalle }; // Copiar los datos del detalle de venta al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoDetalleVenta = {
          id_detalle: null,
          id_venta: null,
          id_producto: null,
          cantidad: null,
          precio_unitario: null
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  