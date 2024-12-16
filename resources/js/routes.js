export const routes = [
    {
        path: "/",
        name: "login",
        component: () => import("./pages/Login.vue"),
        meta: { guest: true },
    },
    {
        path: "/appointment",
        name: "appointment",
        component: () => import("./pages/Appointment.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/appointment/create",
        name: "appointment.create",
        component: () => import("./pages/AppointmentCreate.vue"),
        meta: { requiresAuth: true },
    },
];
