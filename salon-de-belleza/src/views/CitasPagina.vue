<template>
    <div>
      <h1>Gestión de Citas</h1>
      <PaginaCitas />
    </div>
  </template>
  
  <script>
  import PaginaCitas from '../components/PaginaCitas.vue';
  
  export default {
    name: 'CitasPagina',
    components: {
      PaginaCitas
    }
  }
  </script>
  