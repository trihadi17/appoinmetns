import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import axios from "axios";
import VueAxios from "vue-axios";

// bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

import { createRouter, createWebHistory } from "vue-router";
import { routes } from "./routes";
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guard untuk cek login status
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("authToken"); // Ambil token dari localStorage
    const tokenExpiry = localStorage.getItem("tokenExpiry"); // Ambil waktu expire
    const now = Math.floor(Date.now() / 1000); // Waktu sekarang dalam detik

    // Cek apakah route membutuhkan autentikasi (requiresAuth)
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        // Jika route memerlukan autentikasi
        if (!token || now > tokenExpiry) {
            // Token tidak ada atau sudah kedaluwarsa
            localStorage.removeItem("authToken"); // Hapus token
            localStorage.removeItem("tokenExpiry"); // Hapus waktu expire

            alert("Not logged in or Session expired. Please log back in.");

            next({ name: "login" }); // Redirect ke halaman login
        } else {
            next(); // Token valid, lanjutkan ke halaman appointment
        }
    } else if (to.matched.some((record) => record.meta.guest)) {
        // Jika route hanya untuk guest, tetapi pengguna sudah login
        if (token && now <= tokenExpiry) {
            next({ name: "appointment" }); // Redirect ke halaman appointment jika sudah login
        } else {
            next(); // Lanjutkan jika belum login
        }
    } else {
        // Jika route tidak membutuhkan autentikasi, lanjutkan
        next();
    }
});

const app = createApp(App);
app.use(router);
app.use(VueAxios, axios);

app.mount("#app");
