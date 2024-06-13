<template>
    <div>
      <!-- Mostrar la lista de citas -->
      <ul>
        <li v-for="cita in citas" :key="cita.id_cita">
          {{ cita.id_cita }} - {{ cita.id_usuario }} - {{ cita.fecha }} - {{ cita.hora }} - {{ cita.id_servicio }} - {{ cita.estado }}
          <button @click="eliminarCita(cita.id_cita)">Eliminar</button>
          <button @click="cargarCitaParaActualizar(cita)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar una cita -->
      <form @submit.prevent="agregarOActualizarCita">
        <input v-model="nuevaCita.id_usuario" type="number" placeholder="ID de Usuario" required>
        <input v-model="nuevaCita.fecha" type="date" placeholder="Fecha" required>
        <input v-model="nuevaCita.hora" type="time" placeholder="Hora" required>
        <input v-model="nuevaCita.id_servicio" type="number" placeholder="ID de Servicio" required>
        <select v-model="nuevaCita.estado" required>
        <option value="">Seleccionar estado</option>
        <option value="pendiente">Pendiente</option>
        <option value="realizada">Realizada</option>
        </select>
        <button type="submit">{{ actualizando ? 'Actualizar Cita' : 'Agregar Cita' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        citas: [],
        nuevaCita: {
          id_usuario: null,
          fecha: '',
          hora: '',
          id_servicio: null,
          estado: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando una cita
      };
    },
    mounted() {
      this.obtenerCitas();
    },
    methods: {
      obtenerCitas() {
        console.log('Obteniendo citas...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/citas.php?action=obtenerCitas')
          .then(response => {
            console.log('Citas obtenidas:', response.data);
            this.citas = response.data;
          })
          .catch(error => {
            console.error('Error al obtener citas:', error);
          });
      },
      agregarOActualizarCita() {
        console.log('Enviando formulario...', this.nuevaCita);
        const action = this.actualizando ? 'actualizarCita' : 'crearCita';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/citas.php?action=${action}`, this.nuevaCita, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Cita actualizada' : 'Cita agregada'}:`, response.data);
            this.obtenerCitas();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} cita:`, error);
          });
      },
      eliminarCita(idCita) {
        console.log('Eliminando cita con ID:', idCita);
        axios.post('http://localhost/Proyecto_Software_Final/backend/citas.php?action=eliminarCita', { id_cita: idCita }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Cita eliminada:', response.data);
            this.obtenerCitas();
          })
          .catch(error => {
            console.error('Error al eliminar cita:', error);
          });
      },
      cargarCitaParaActualizar(cita) {
        console.log('Cargando cita para actualizar:', cita);
        this.nuevaCita = { ...cita }; // Copiar los datos de la cita al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevaCita = {
          id_usuario: null,
          fecha: '',
          hora: '',
          id_servicio: null,
          estado: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  