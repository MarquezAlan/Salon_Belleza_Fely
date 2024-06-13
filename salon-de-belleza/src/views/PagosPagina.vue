<template>
    <div>
      <h1>Gestión de Pagos</h1>
      <PaginaPagos />
    </div>
  </template>
  
  <script>
  import PaginaPagos from '../components/PaginaPagos.vue';
  
  export default {
    name: 'PagosPagina',
    components: {
      PaginaPagos
    }
  }
  </script>
  