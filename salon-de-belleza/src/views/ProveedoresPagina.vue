<template>
    <div>
      <h1>Gestión de Proveedores</h1>
      <PaginaProveedores />
    </div>
  </template>
  
  <script>
  import PaginaProveedores from '../components/PaginaProveedores.vue';
  
  export default {
    name: 'ProveedoresPagina',
    components: {
      PaginaProveedores
    }
  }
  </script>
  