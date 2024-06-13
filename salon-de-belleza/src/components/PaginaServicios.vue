<template>
    <div>
      <!-- Mostrar la lista de servicios -->
      <ul>
        <li v-for="servicio in servicios" :key="servicio.id_servicio">
        {{ servicio.id_servicio }} - {{ servicio.nombre }} - {{ servicio.descripcion }} - Bs.{{ servicio.precio }}
          <button @click="eliminarServicio(servicio.id_servicio)">Eliminar</button>
          <button @click="cargarServicioParaActualizar(servicio)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un servicio -->
      <form @submit.prevent="agregarOActualizarServicio">
        <input v-model="nuevoServicio.nombre" placeholder="Nombre del servicio" required>
        <textarea v-model="nuevoServicio.descripcion" placeholder="Descripción" required></textarea>
        <input v-model="nuevoServicio.precio" type="number" step="0.01" placeholder="Precio" required>
        <button type="submit">{{ actualizando ? 'Actualizar Servicio' : 'Agregar Servicio' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        servicios: [],
        nuevoServicio: {
          id_servicio: null,
          nombre: '',
          descripcion: '',
          precio: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un servicio
      };
    },
    mounted() {
      this.obtenerServicios();
    },
    methods: {
      obtenerServicios() {
        console.log('Obteniendo servicios...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/servicios.php?action=obtenerServicios')
          .then(response => {
            console.log('Servicios obtenidos:', response.data);
            this.servicios = response.data;
          })
          .catch(error => {
            console.error('Error al obtener servicios:', error);
          });
      },
      agregarOActualizarServicio() {
        console.log('Enviando formulario...', this.nuevoServicio);
        const action = this.actualizando ? 'actualizarServicio' : 'crearServicio';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/servicios.php?action=${action}`, this.nuevoServicio, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Servicio actualizado' : 'Servicio agregado'}:`, response.data);
            this.obtenerServicios();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} servicio:`, error);
          });
      },
      eliminarServicio(idServicio) {
        console.log('Eliminando servicio con ID:', idServicio);
        axios.post('http://localhost/Proyecto_Software_Final/backend/servicios.php?action=eliminarServicio', { id_servicio: idServicio }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Servicio eliminado:', response.data);
            this.obtenerServicios();
          })
          .catch(error => {
            console.error('Error al eliminar servicio:', error);
          });
      },
      cargarServicioParaActualizar(servicio) {
        console.log('Cargando servicio para actualizar:', servicio);
        this.nuevoServicio = { ...servicio }; // Copiar los datos del servicio al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoServicio = {
          id_servicio: null,
          nombre: '',
          descripcion: '',
          precio: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  