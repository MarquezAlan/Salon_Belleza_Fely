import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/HomePagina.vue';
import Login from '../views/LoginPagina.vue';
import Admin from '../views/AdminPagina.vue';
import Usuarios from '../views/UsuariosPagina.vue';
import Servicios from '../views/ServiciosPagina.vue';
import Categorias from '../views/CategoriasPagina.vue';
import Administradores from '../views/AdministradoresPagina.vue';
import Proveedores from '../views/ProveedoresPagina.vue';
import Productos from '../views/ProductosPagina.vue';
import Inventarios from '../views/InventariosPagina.vue';
import Citas from '../views/CitasPagina.vue';
import Ventas from '../views/VentasPagina.vue';
import Pagos from '../views/PagosPagina.vue';
import DetallesVenta from '../views/DetallesVentasPagina.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/admin', component: Admin, children: [
      { path: 'usuarios', component: Usuarios },
      { path: 'servicios', component: Servicios },
      { path: 'categorias', component: Categorias },
      { path: 'administradores', component: Administradores },
      { path: 'proveedores', component: Proveedores },
      { path: 'productos', component: Productos },
      { path: 'inventarios', component: Inventarios },
      { path: 'citas', component: Citas },
      { path: 'ventas', component: Ventas },
      { path: 'pagos', component: Pagos },
      { path: 'detallesventas', component: DetallesVenta }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
