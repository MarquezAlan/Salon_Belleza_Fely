<template>
    <div>
      <h1>Gestión de Inventarios</h1>
      <PaginaInventarios />
    </div>
  </template>
  
  <script>
  import PaginaInventarios from '../components/PaginaInventarios.vue';
  
  export default {
    name: 'InventariosPagina',
    components: {
      PaginaInventarios
    }
  }
  </script>
  