<template>
    <div>
      <!-- Mostrar la lista de categorías -->
      <ul>
        <li v-for="categoria in categorias" :key="categoria.id_categoria">
        {{ categoria.id_categoria }} - {{ categoria.nombre }} - {{ categoria.descripcion }}
          <button @click="eliminarCategoria(categoria.id_categoria)">Eliminar</button>
          <button @click="cargarCategoriaParaActualizar(categoria)">Actualizar</button>
        </li>
      </ul>
  
      <!-- Formulario para agregar/actualizar una categoría -->
      <form @submit.prevent="agregarOActualizarCategoria">
        <input v-model="nuevaCategoria.nombre" placeholder="Nombre" required>
        <input v-model="nuevaCategoria.descripcion" placeholder="Descripción" required>
        <button type="submit">{{ actualizando ? 'Actualizar Categoría' : 'Agregar Categoría' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        categorias: [],
        nuevaCategoria: {
          id_categoria: null,
          nombre: '',
          descripcion: ''
        },
        actualizando: false // Variable para saber si estamos actualizando o agregando una categoría
      };
    },
    mounted() {
      this.obtenerCategorias();
    },
    methods: {
      obtenerCategorias() {
        console.log('Obteniendo categorías...');
        axios.get('http://localhost/Proyecto_Software_Final/backend/categorias.php?action=obtenerCategorias')
          .then(response => {
            console.log('Categorías obtenidas:', response.data);
            this.categorias = response.data;
          })
          .catch(error => {
            console.error('Error al obtener categorías:', error);
          });
      },
      agregarOActualizarCategoria() {
        console.log('Enviando formulario...', this.nuevaCategoria);
        const action = this.actualizando ? 'actualizarCategoria' : 'crearCategoria';
        axios.post(`http://localhost/Proyecto_Software_Final/backend/categorias.php?action=${action}`, this.nuevaCategoria, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log(`${this.actualizando ? 'Categoría actualizada' : 'Categoría agregada'}:`, response.data);
            this.obtenerCategorias();
            this.limpiarFormulario();
          })
          .catch(error => {
            console.error(`Error al ${this.actualizando ? 'actualizar' : 'agregar'} categoría:`, error);
          });
      },
      eliminarCategoria(idCategoria) {
        console.log('Eliminando categoría con ID:', idCategoria);
        axios.post('http://localhost/Proyecto_Software_Final/backend/categorias.php?action=eliminarCategoria', { id_categoria: idCategoria }, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
          .then(response => {
            console.log('Categoría eliminada:', response.data);
            this.obtenerCategorias();
          })
          .catch(error => {
            console.error('Error al eliminar categoría:', error);
          });
      },
      cargarCategoriaParaActualizar(categoria) {
        console.log('Cargando categoría para actualizar:', categoria);
        this.nuevaCategoria = { ...categoria }; // Copiar los datos de la categoría al formulario
        this.actualizando = true; // Cambiar el estado a "actualizando"
      },
      limpiarFormulario() {
        console.log('Limpiando formulario...');
        this.nuevaCategoria = {
          id_categoria: null,
          nombre: '',
          descripcion: ''
        };
        this.actualizando = false; // Resetear el estado a "agregar"
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos aquí */
  </style>
  