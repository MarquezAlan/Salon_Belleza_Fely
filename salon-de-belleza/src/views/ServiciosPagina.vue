<template>
    <div>
      <h1>Gestión de Servicios</h1>
      <PaginaServicios />
    </div>
  </template>
  
  <script>
  import PaginaServicios from '../components/PaginaServicios.vue';
  
  export default {
    name: 'ServiciosPagina',
    components: {
      PaginaServicios
    }
  }
  </script>
  