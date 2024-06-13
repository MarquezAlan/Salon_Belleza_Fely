<template>
    <div>
      <!-- Mostrar la lista de pagos -->
      <ul>
        <li v-for="pago in pagos" :key="pago.id_pago">
          {{ pago.id_pago }} - {{ pago.id_venta }} - {{ pago.fecha }} - Bs.{{ pago.monto }} - {{ pago.metodo_pago }}
          <button @click="eliminarPago(pago.id_pago)">Eliminar</button>
          <button @click="cargarPagoParaActualizar(pago)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un pago -->
      <form @submit.prevent="agregarOActualizarPago">
        <input v-model="nuevoPago.id_venta" placeholder="ID Venta" required>
        <input v-model="nuevoPago.fecha" type="date" required>
        <input v-model="nuevoPago.monto" type="number" step="0.01" placeholder="Monto" required>
        <select v-model="nuevoPago.metodo_pago" required>
        <option value="">Seleccionar metodo pago</option>
        <option value="efectivo">efectivo</option>
        <option value="credito">credito</option>
        </select>
        <button type="submit">{{ actualizando ? 'Actualizar Pago' : 'Agregar Pago' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        pagos: [],
        nuevoPago: {
          id_pago: null,
          id_venta: null,
          fecha: '',
          monto: null,
          metodo_pago: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un pago
      };
    },
    mounted() {
      this.obtenerPagos();
    },
    methods: {
      obtenerPagos() {
        console.log('Obteniendo pagos...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/pagos.php?action=obtenerPagos')
          .then(response => {
            console.log('Pagos obtenidos:', response.data);
            this.pagos = response.data;
          })
          .catch(error => {
            console.error('Error al obtener pagos:', error);
          });
      },
      agregarOActualizarPago() {
        console.log('Enviando formulario...', this.nuevoPago);
        const action = this.actualizando ? 'actualizarPago' : 'crearPago';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/pagos.php?action=${action}`, this.nuevoPago, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Pago actualizado' : 'Pago agregado'}:`, response.data);
            this.obtenerPagos();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} pago:`, error);
          });
      },
      eliminarPago(idPago) {
        console.log('Eliminando pago con ID:', idPago);
        axios.post('http://localhost/Proyecto_Software_Final/backend/pagos.php?action=eliminarPago', { id_pago: idPago }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Pago eliminado:', response.data);
            this.obtenerPagos();
          })
          .catch(error => {
            console.error('Error al eliminar pago:', error);
          });
      },
      cargarPagoParaActualizar(pago) {
        console.log('Cargando pago para actualizar:', pago);
        this.nuevoPago = { ...pago }; // Copiar los datos del pago al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoPago = {
          id_pago: null,
          id_venta: null,
          fecha: '',
          monto: null,
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
  