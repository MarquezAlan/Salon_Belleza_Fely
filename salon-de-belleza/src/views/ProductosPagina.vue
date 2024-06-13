<template>
    <div>
      <h1>Gestión de Productos</h1>
      <PaginaProductos />
    </div>
  </template>
  
  <script>
  import PaginaProductos from '../components/PaginaProductos.vue';
  
  export default {
    name: 'ProductosPagina',
    components: {
      PaginaProductos
    }
  }
  </script>
  