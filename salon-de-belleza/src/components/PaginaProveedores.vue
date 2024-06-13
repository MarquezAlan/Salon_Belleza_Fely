<template>
    <div>
      <!-- Mostrar la lista de proveedores -->
      <ul>
        <li v-for="proveedor in proveedores" :key="proveedor.id_proveedor">
        {{ proveedor.id_proveedor }} - {{ proveedor.nombre }} - {{ proveedor.contacto }} - {{ proveedor.telefono }} - {{ proveedor.email }}
          <button @click="eliminarProveedor(proveedor.id_proveedor)">Eliminar</button>
          <button @click="cargarProveedorParaActualizar(proveedor)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar un proveedor -->
      <form @submit.prevent="agregarOActualizarProveedor">
        <input v-model="nuevoProveedor.nombre" placeholder="Nombre" required>
        <input v-model="nuevoProveedor.contacto" placeholder="Contacto" required>
        <input v-model="nuevoProveedor.telefono" placeholder="Teléfono" required>
        <input v-model="nuevoProveedor.email" placeholder="Email" required>
        <button type="submit">{{ actualizando ? 'Actualizar Proveedor' : 'Agregar Proveedor' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        proveedores: [],
        nuevoProveedor: {
          id_proveedor: null,
          nombre: '',
          contacto: '',
          telefono: '',
          email: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando un proveedor
      };
    },
    mounted() {
      this.obtenerProveedores();
    },
    methods: {
      obtenerProveedores() {
        console.log('Obteniendo proveedores...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/proveedores.php?action=obtenerProveedores')
          .then(response => {
            console.log('Proveedores obtenidos:', response.data);
            this.proveedores = response.data;
          })
          .catch(error => {
            console.error('Error al obtener proveedores:', error);
          });
      },
      agregarOActualizarProveedor() {
        console.log('Enviando formulario...', this.nuevoProveedor);
        const action = this.actualizando ? 'actualizarProveedor' : 'crearProveedor';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/proveedores.php?action=${action}`, this.nuevoProveedor, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(response => {
            console.log(`${this.actualizando ? 'Proveedor actualizado' : 'Proveedor agregado'}:`, response.data);
            this.obtenerProveedores();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} proveedor:`, error);
          });
    },
    eliminarProveedor(idProveedor) {
      console.log('Eliminando proveedor con ID:', idProveedor);
      axios.post('http://localhost/Proyecto_Software_Final/backend/proveedores.php?action=eliminarProveedor', { id_proveedor: idProveedor }, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .then(response => {
          console.log('Proveedor eliminado:', response.data);
          this.obtenerProveedores();
        })
        .catch(error => {
          console.error('Error al eliminar proveedor:', error);
        });
    },
    cargarProveedorParaActualizar(proveedor) {
      console.log('Cargando proveedor para actualizar:', proveedor);
      this.nuevoProveedor = { ...proveedor }; // Copiar los datos del proveedor al formulario
      this.actualizando = true; // Cambiar el estado a "actualizando"
    },
    limpiarFormulario() {
      console.log('Limpiando formulario...');
      this.nuevoProveedor = {
        id_proveedor: null,
        nombre: '',
        contacto: '',
        telefono: '',
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
