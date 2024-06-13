<template>
  <div class="admin-container">
    <div class="header text-center mb-4">
      <h1>{{ title }}</h1>
      <p>{{ subtitle }}</p>
    </div>
    <div class="links-container">
      <router-link class="btn btn-primary" to="/admin/usuarios">Gestionar Usuarios</router-link>
      <router-link class="btn btn-primary" to="/admin/servicios">Gestionar Servicios</router-link>
      <router-link class="btn btn-primary" to="/admin/categorias">Gestionar Categorías</router-link>
      <router-link class="btn btn-primary" to="/admin/administradores">Gestionar Administradores</router-link>
      <router-link class="btn btn-primary" to="/admin/proveedores">Gestionar Proveedores</router-link>
      <router-link class="btn btn-primary" to="/admin/productos">Gestionar Productos</router-link>
      <router-link class="btn btn-primary" to="/admin/inventarios">Gestionar Inventarios</router-link>
      <router-link class="btn btn-primary" to="/admin/citas">Gestionar Citas</router-link>
      <router-link class="btn btn-primary" to="/admin/ventas">Gestionar Ventas</router-link>
      <router-link class="btn btn-primary" to="/admin/pagos">Gestionar Pagos</router-link>
      <router-link class="btn btn-primary" to="/admin/detallesventas">Gestionar Detalles-Ventas</router-link>
    </div>
    <div class="services-section">
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card bg-erp">
            <div class="card-body">
              <h5 class="card-title">Tratamientos Faciales</h5>
              <p class="card-text">Explora nuestros tratamientos especializados para el cuidado de tu piel.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card bg-hosting">
            <div class="card-body">
              <h5 class="card-title">Manicura y Pedicura</h5>
              <p class="card-text">Disfruta de un servicio de manicura y pedicura con los mejores productos.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card bg-ssl">
            <div class="card-body">
              <h5 class="card-title">Maquillaje Profesional</h5>
              <p class="card-text">Déjate asesorar por nuestros expertos en maquillaje para eventos especiales.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card bg-domains">
            <div class="card-body">
              <h5 class="card-title">Depilación y Cera</h5>
              <p class="card-text">Ofrecemos depilación con cera, con técnicas seguras y efectivas.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card bg-business">
            <div class="card-body">
              <h5 class="card-title">Tratamientos Corporales</h5>
              <p class="card-text">Descubre nuestros tratamientos corporales para el bienestar y la relajación.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card bg-web">
            <div class="card-body">
              <h5 class="card-title">Peluquería</h5>
              <p class="card-text">Visítanos para un corte de cabello profesional y estilizado.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'AdminPagina',
  data() {
    return {
      title: 'Panel de Administración',
      subtitle: 'Bienvenido, administrador.'
    };
  }
};
</script>

<style scoped>
.admin-container {
  padding: 20px;
  background-color: #f8f9fa;
}

.header {
  text-align: center;
  margin-bottom: 20px;
}

.header h1 {
  font-size: 2.5rem;
  color: #333;
}

.header p {
  font-size: 1.2rem;
  color: #666;
}

.links-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
  margin-bottom: 20px;
}

.links-container .btn {
  border-radius: 50px;
  padding: 10px 20px;
  font-size: 1rem;
}

.services-section {
  margin-top: 20px;
}

.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  color: white;
  text-align: center;
  padding: 20px;
}

.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card-title {
  font-size: 1.5rem;
  font-weight: bold;
}

.card-text {
  font-size: 1rem;
}

.bg-erp { background-color: #007bff; }
.bg-hosting { background-color: #28a745; }
.bg-ssl { background-color: #dc3545; }
.bg-domains { background-color: #fd7e14; }
.bg-business { background-color: #17a2b8; }
.bg-web { background-color: #6f42c1; }

.row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.col-md-4 {
  flex: 1;
}

@media (max-width: 768px) {
  .col-md-4 {
    flex: 100%;
  }
}
</style>
