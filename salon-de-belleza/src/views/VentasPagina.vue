<template>
    <div>
      <h1>Gestión de Ventas</h1>
      <PaginaVentas />
    </div>
  </template>
  
  <script>
  import PaginaVentas from '../components/PaginaVentas.vue';
  
  export default {
    name: 'VentasPagina',
    components: {
      PaginaVentas
    }
  }
  </script>
  