<template>
  <!-- HEADER -->
  <div v-if="!isLoginPage" class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <router-link class="navbar-brand" :to="{ name: 'appointment' }">
        <!-- Bootstrap Logo from CDN -->
        <img
          src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg"
          alt="Bootstrap Logo"
          width="30"
        />
      </router-link>

      <!-- Logout Button (on the right) -->
      <div class="navbar-collapse d-flex justify-content-end">
        <button class="btn btn-sm btn-danger" type="submit" @click="logout">
          Logout
        </button>
      </div>
    </nav>
  </div>

  <!-- CONTENT -->
  <div class="container">
    <!-- ROUTER VIEW -->
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  computed: {
    // Menggunakan computed property untuk mengecek apakah route saat ini adalah '/login'
    isLoginPage() {
      return this.$route.path === "/";
    },
  },
  methods: {
    async logout() {
      try {
        // Ambil token
        const token = localStorage.getItem("authToken");

        // API
        const response = await axios.get(`/api/logout`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        // Menghapus token, tokenexpire,user dari localStorage
        localStorage.removeItem("authToken");
        localStorage.removeItem("tokenExpiry");
        localStorage.removeItem("userData");

        // Push
        this.$router.push({ name: "login" });
      } catch (error) {
        alert("Logout failed. Please try again." + error);
      }
    },
  },
};
</script>
