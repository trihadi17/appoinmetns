<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mt-5">
          <div class="card-header text-center">
            <h4>Login</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="submitLogin">
              <div class="form-group">
                <label for="username" class="mb-2">Username</label>
                <input
                  type="text"
                  id="username"
                  v-model="username"
                  class="form-control"
                  placeholder="Enter your username"
                  required
                />
              </div>
              <button
                type="submit"
                class="btn btn-primary btn-block btn-sm mt-4"
              >
                Login
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: "",
    };
  },
  methods: {
    async submitLogin() {
      try {
        // API
        const response = await axios.post(`api/login`, {
          username: this.username,
        });

        // inisialisasi data
        const { user, token, expires_in } = response.data.data;

        // Hitung waktu kadaluwarsa token (UNIX timestamps)
        const now = Math.floor(Date.now() / 1000); // Waktu sekarang dalam detik
        const tokenExpiry = now + expires_in; // Waktu sekarang + 1 jam (3600)

        // Simpan Token
        localStorage.setItem("authToken", token);
        localStorage.setItem("userData", JSON.stringify(user));
        localStorage.setItem("tokenExpiry", tokenExpiry);
        console.log(localStorage.getItem("authToken"));
        console.log(localStorage.getItem("userData"));
        console.log(localStorage.getItem("tokenExpiry"));

        this.$router.push({ name: "appointment" });
      } catch (error) {
        console.error("Login failed:", error);
        alert("Login failed. Please try again.");
      }
    },
  },
};
</script>

<style scoped>
.container {
  margin-top: 50px;
}
.card {
  border-radius: 10px;
}
</style>