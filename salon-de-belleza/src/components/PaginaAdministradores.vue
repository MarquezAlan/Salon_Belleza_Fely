<template>
    <div>
      <!-- Mostrar la lista de administradores -->
      <ul>
        <li v-for="administrador in administradores" :key="administrador.id_administrador">
        {{ administrador.id_administrador }} - {{ administrador.nombre_usuario }} - {{ administrador.nombre }} - {{ administrador.apellido }} - {{ administrador.email }}
          <button @click="eliminarAdministrador(administrador.id_administrador)">Eliminar</button>
          <button @click="cargarAdministradorParaActualizar(administrador)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un administrador -->
      <form @submit.prevent="agregarOActualizarAdministrador">
        <input v-model="nuevoAdministrador.nombre_usuario" placeholder="Nombre de usuario" required>
        <input v-model="nuevoAdministrador.contrasenia" type="password" placeholder="Contraseña" required>
        <input v-model="nuevoAdministrador.nombre" placeholder="Nombre" required>
        <input v-model="nuevoAdministrador.apellido" placeholder="Apellido" required>
        <input v-model="nuevoAdministrador.email" placeholder="Email" required>
        <button type="submit">{{ actualizando ? 'Actualizar Administrador' : 'Agregar Administrador' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        administradores: [],
        nuevoAdministrador: {
          id_administrador: null,
          nombre_usuario: '',
          contrasenia: '',
          nombre: '',
          apellido: '',
          email: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un administrador
      };
    },
    mounted() {
      this.obtenerAdministradores();
    },
    methods: {
      obtenerAdministradores() {
        console.log('Obteniendo administradores...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/administradores.php?action=obtenerAdministradores')
          .then(response => {
            console.log('Administradores obtenidos:', response.data);
            this.administradores = response.data;
          })
          .catch(error => {
            console.error('Error al obtener administradores:', error);
          });
      },
      agregarOActualizarAdministrador() {
        console.log('Enviando formulario...', this.nuevoAdministrador);
        const action = this.actualizando ? 'actualizarAdministrador' : 'crearAdministrador';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/administradores.php?action=${action}`, this.nuevoAdministrador, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Administrador actualizado' : 'Administrador agregado'}:`, response.data);
            this.obtenerAdministradores();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} administrador:`, error);
          });
      },
      eliminarAdministrador(idAdministrador) {
        console.log('Eliminando administrador con ID:', idAdministrador);
        axios.post('http://localhost/Proyecto_Software_Final/backend/administradores.php?action=eliminarAdministrador', { id_administrador: idAdministrador }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Administrador eliminado:', response.data);
            this.obtenerAdministradores();
          })
          .catch(error => {
            console.error('Error al eliminar administrador:', error);
          });
      },
      cargarAdministradorParaActualizar(administrador) {
        console.log('Cargando administrador para actualizar:', administrador);
        this.nuevoAdministrador = { ...administrador }; // Copiar los datos del administrador al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevoAdministrador = {
          id_administrador: null,
          nombre_usuario: '',
          contrasenia: '',
          nombre: '',
          apellido: '',
          email: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  