<template>
  <div>
    <h1>Gestión de Usuarios</h1>
    <PaginaUsuarios />
  </div>
</template>

<script>
import PaginaUsuarios from '../components/PaginaUsuarios.vue';

export default {
  name: 'UsuariosPagina',
  components: {
    PaginaUsuarios
  }
}
</script>
