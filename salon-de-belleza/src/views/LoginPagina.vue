<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card login-card p-4 shadow-sm border-custom">
      <div class="card-body">
        <h3 class="card-title text-center mb-4 text-primary">Iniciar sesión</h3>
        <form @submit.prevent="iniciarSesion">
          <div class="form-group">
            <label for="email" class="icon-label text-primary">
              <i class="fas fa-envelope"></i> Email
            </label>
            <input
              type="email"
              class="form-control"
              id="email"
              v-model="username"
              placeholder="Ingrese su email"
              required
            />
          </div>
          <div class="form-group">
            <label for="password" class="icon-label text-primary">
              <i class="fas fa-lock"></i> Contraseña
              <i
                class="fas"
                :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                @click="togglePasswordVisibility"
                style="cursor: pointer; margin-left: 10px;"
              ></i>
            </label>
            <input
              class="form-control"
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Ingrese su contraseña"
              required
            />
          </div>
          <div class="form-check mb-4">
            <input
              type="checkbox"
              class="form-check-input"
              id="rememberMe"
              v-model="rememberMe"
            />
            <label class="form-check-label" for="rememberMe">Recordarme</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block mt-4">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginPagina',
  data() {
    return {
      username: '',
      password: '',
      showPassword: false,
      rememberMe: false
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    iniciarSesion() {
      if (this.username === 'admin' && this.password === 'admin') {
        this.$router.push('/admin');
      } else {
        alert('Usuario o contraseña incorrectos');
      }
    }
  }
};
</script>

<style scoped>
body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f8f9fa;
}

.login-card {
  max-width: 400px;
  width: 100%;
  border-radius: 15px;
  background-color: #ffffff;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}

.login-card:hover {
  transform: translateY(-10px);
}

.card-title {
  font-size: 1.8rem;
  font-weight: bold;
  color: #007bff;
}

.icon-label {
  display: flex;
  align-items: center;
}

.icon-label i {
  margin-right: 10px;
  font-size: 1.2rem;
  color: #007bff;
}

.form-control {
  border-radius: 50px;
  padding-left: 20px;
  height: 45px;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.btn-primary {
  background-color: #007bff;
  border: none;
  border-radius: 50px;
  font-size: 1.2rem;
  transition: background-color 0.3s, transform 0.3s;
}

.btn-primary:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}

.form-check-input {
  border-radius: 50px;
}

.fas {
  cursor: pointer;
}

.fas:hover {
  color: #007bff;
}

/* Nueva clase para el borde con gradiente */
.border-custom {
  border: 5px solid;
  border-image: linear-gradient(to right, #ff7e5f, #feb47b, #ffb6c1, #ff7f50, #ff6347) 1;
  border-radius: 15px;
}

@media (max-width: 768px) {
  .login-card {
    max-width: 100%;
    padding: 20px;
  }
}
</style>
