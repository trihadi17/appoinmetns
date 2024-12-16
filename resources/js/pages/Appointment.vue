<template>
  <!-- Header Title -->
  <div class="row">
    <div class="col-12">
      <div class="mb-4 mt-3">
        <h4>Hello, {{ user.name }}</h4>
      </div>
    </div>
  </div>

  <!-- Router Link (Appointment Create) -->
  <div class="row">
    <div class="col-12 mb-2 mt-2">
      <router-link
        class="btn btn-sm btn-success"
        :to="{ name: 'appointment.create' }"
        >Create</router-link
      >
    </div>
  </div>

  <!-- Card Box -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5>Appointment Data</h5>
        </div>
        <div class="card-body">
          <!-- Table inside Card -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Title</th>
                <th scope="col">Creator</th>
                <th scope="col">Participant</th>
                <th scope="col">Start</th>
                <th scope="col">End</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(appointment, index) in appointments" :key="index">
                <td>{{ appointment.title }}</td>
                <td>{{ appointment.creator.name }}</td>
                <td>{{ appointment.participants.user.name }}</td>
                <td>{{ formatDate(appointment.start) }}</td>
                <td>{{ formatDate(appointment.end) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment-timezone";

export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem("userData")),
      appointments: [],
      timezone: "",
    };
  },
  mounted() {
    this.fetchAppointments();
  },

  methods: {
    async fetchAppointments() {
      try {
        // Ambil token
        const token = localStorage.getItem("authToken");

        // API
        const response = await axios.get(`/api/appointment`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        // Simpan
        this.appointments = response.data.data;
      } catch (error) {
        console.error("Failed to fetch appointments:", error);
        alert("Failed to load appointments");
      }
    },
    formatDate(date) {
      // Mengonversi tanggal ke timezone yang diinginkan
      return moment
        .utc(date)
        .tz(this.user.preferred_timezone)
        .format("DD MMM YYYY, HH:mm:ss");
    },
  },
};
</script>