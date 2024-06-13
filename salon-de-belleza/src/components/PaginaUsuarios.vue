<template>
  <div>
    <!-- Mostrar la lista de usuarios -->
    <ul>
      <li v-for="usuario in usuarios" :key="usuario.id_usuario">
        {{ usuario.id_usuario }} - {{ usuario.nombre_usuario }} - {{ usuario.nombre }} - {{ usuario.apellido }} - {{ usuario.email }} - {{ usuario.telefono }} - {{ usuario.direccion }}
        <button @click="eliminarUsuario(usuario.id_usuario)">Eliminar</button>
        <button @click="cargarUsuarioParaActualizar(usuario)">Actualizar</button>
      </li>
    </ul>

    <!-- Formulario para agregar/actualizar un usuario -->
    <form @submit.prevent="agregarOActualizarUsuario">
      <input v-model="nuevoUsuario.nombre_usuario" placeholder="Nombre de usuario" required>
      <input v-model="nuevoUsuario.contrasenia" type="password" placeholder="Contraseña" required>
      <input v-model="nuevoUsuario.nombre" placeholder="Nombre" required>
      <input v-model="nuevoUsuario.apellido" placeholder="Apellido" required>
      <input v-model="nuevoUsuario.email" placeholder="Email" required>
      <input v-model="nuevoUsuario.telefono" placeholder="Teléfono" required>
      <input v-model="nuevoUsuario.direccion" placeholder="Dirección" required>
      <button type="submit">{{ actualizando ? 'Actualizar Usuario' : 'Agregar Usuario' }}</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      usuarios: [],
      nuevoUsuario: {
        id_usuario: null,
        nombre_usuario: '',
        contrasenia: '',
        nombre: '',
        apellido: '',
        email: '',
        telefono: '',
        direccion: ''
      },
      actualizando: false // Variable para saber si estamos actualizando o agregando un usuario
    };
  },
  mounted() {
    this.obtenerUsuarios();
  },
  methods: {
    obtenerUsuarios() {
      console.log('Obteniendo usuarios...');
      axios.get('http://localhost/Proyecto_Software_Final/backend/usuarios.php?action=obtenerUsuarios')
        .then(response => {
          console.log('Usuarios obtenidos:', response.data);
          this.usuarios = response.data;
        })
        .catch(error => {
          console.error('Error al obtener usuarios:', error);
        });
    },
    agregarOActualizarUsuario() {
      console.log('Enviando formulario...', this.nuevoUsuario);
      const action = this.actualizando ? 'actualizarUsuario' : 'crearUsuario';
      axios.post(`http://localhost/Proyecto_Software_Final/backend/usuarios.php?action=${action}`, this.nuevoUsuario, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          console.log(`${this.actualizando ? 'Usuario actualizado' : 'Usuario agregado'}:`, response.data);
          this.obtenerUsuarios();
          this.limpiarFormulario();
        })
        .catch(error => {
          console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} usuario:`, error);
        });
    },
    eliminarUsuario(idUsuario) {
      console.log('Eliminando usuario con ID:', idUsuario);
      axios.post('http://localhost/Proyecto_Software_Final/backend/usuarios.php?action=eliminarUsuario', { id_usuario: idUsuario }, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          console.log('Usuario eliminado:', response.data);
          this.obtenerUsuarios();
        })
        .catch(error => {
          console.error('Error al eliminar usuario:', error);
        });
    },
    cargarUsuarioParaActualizar(usuario) {
      console.log('Cargando usuario para actualizar:', usuario);
      this.nuevoUsuario = { ...usuario }; // Copiar los datos del usuario al formulario
      this.actualizando = true; // Cambiar el estado a "actualizando"
    },
    limpiarFormulario() {
      console.log('Limpiando formulario...');
      this.nuevoUsuario = {
        id_usuario: null,
        nombre_usuario: '',
        contrasenia: '',
        nombre: '',
        apellido: '',
        email: '',
        telefono: '',
        direccion: ''
      };
      this.actualizando = false; // Resetear el estado a "agregar"
    }
  }
};
</script>

<style scoped>
/* Tus estilos aquí */
</style>
