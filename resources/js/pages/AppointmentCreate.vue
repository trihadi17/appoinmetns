<template>
  <div class="container mt-5">
    <h2 class="mb-4">Create Event</h2>
    <form @submit.prevent="submitForm">
      <!-- Title Input -->
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input
          type="text"
          id="title"
          class="form-control"
          v-model="form.title"
          placeholder="Enter event title"
          required
        />
      </div>

      <!-- Start DateTime Input -->
      <div class="mb-3">
        <label for="start" class="form-label">Start Date & Time</label>
        <input
          type="datetime-local"
          id="start"
          class="form-control"
          v-model="form.start"
          required
        />
      </div>

      <!-- End DateTime Input -->
      <div class="mb-3">
        <label for="end" class="form-label">End Date & Time</label>
        <input
          type="datetime-local"
          id="end"
          class="form-control"
          v-model="form.end"
          required
        />
      </div>

      <!-- Participants Dropdown -->
      <div class="mb-3">
        <label for="participants" class="form-label">Participants</label>
        <select
          id="participants"
          class="form-select"
          v-model="form.user_id"
          required
        >
          <option value="" disabled>Select a participant</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      // Form Data
      form: {
        title: "",
        start: "",
        end: "",
        user_id: "",
      },
      // Users Data
      users: [],
    };
  },
  methods: {
    // Fetch Users for Dropdown
    async fetchUsers() {
      try {
        // Ambil token
        const token = localStorage.getItem("authToken");

        const response = await axios.get(`/api/other`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.users = response.data.data;
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    // Submit Form
    async submitForm() {
      try {
        // Ambil token
        const token = localStorage.getItem("authToken");

        const response = await axios.post("/api/appointment", this.form, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        alert(response.data.message);
        console.log(response.data);
        // Reset form
        this.form = {
          title: "",
          start: "",
          end: "",
          user_id: "",
        };

        this.$router.push({ name: "appointment" });
      } catch (error) {
        console.error("Error creating event:", error);
        alert(error.response.data.message);
      }
    },
  },
  mounted() {
    this.fetchUsers();
  },
};
</script>

<style>
/* Optional: Add some custom styles */
</style>
