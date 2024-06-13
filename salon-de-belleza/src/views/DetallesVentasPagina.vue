<template>
    <div>
      <h1>Gestión de Detalles de Ventas</h1>
      <PaginaDetallesVentas />
    </div>
  </template>
  
  <script>
  import PaginaDetallesVentas from '../components/PaginaDetallesVentas.vue';
  
  export default {
    name: 'DetallesVentasPagina',
    components: {
      PaginaDetallesVentas
    }
  }
  </script>
  