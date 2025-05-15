<template>
  <div>
    <div class="heading">
      REGISTER
    </div>
    <form class="form-container" @submit.prevent="registerUser">
      <div class="form-group">
        <label for="name" class="label">Name</label>
        <div class="input-container">
          <input v-model="name" name="name" id="name" placeholder="Enter your name" type="text" required>
        </div>
      </div>

      <div class="form-group">
        <label for="email" class="label">Email</label>
        <div class="input-container">
          <input v-model="email" name="email" id="email" placeholder="xyz@gmail.com" type="email" required>
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="label">Password</label>
        <div class="input-container">
          <input v-model="password" name="password" id="password" placeholder="Enter your password" type="password" required>
        </div>
      </div>

      <div class="form-group">
        <label for="password_confirmation" class="label">Confirm Password</label>
        <div class="input-container">
          <input v-model="password_confirmation" name="password_confirmation" id="password_confirmation" placeholder="Enter your password again" type="password" required>
        </div>
      </div>

      <div class="form-actions">
        <a href="/" class="cancel-link">Cancel</a>
        <button type="submit" class="login-button">Register</button>
      </div>
    </form>

    <div>
    <p>
     Already have an account? <a href="/login">Log In</a>.
    </p>
</div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Signup',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  methods: {
  async registerUser() {
    const startTime = performance.now(); // Start time

    try {
      if (this.password !== this.password_confirmation) {
        alert('Passwords do not match.');
        return;
      }

      const payload = { 
        name: this.name, 
        email: this.email,
        password: this.password
      };

      const response = await axios.post('https://e106-110-39-38-126.ngrok-free.app/api/signup', payload);

      const endTime = performance.now(); // End time
      const timeTaken = endTime - startTime;
      console.log(`Registration process took ${timeTaken.toFixed(2)} milliseconds.`);

      alert('Registration successful!');
      this.$router.push('/login');

    } catch (err) {
      const endTime = performance.now(); // Capture even in case of error
      const timeTaken = endTime - startTime;
      console.log(`Registration failed in ${timeTaken.toFixed(2)} milliseconds.`);

      console.error('Registration failed', err);
      alert('Failed to register user.');
    }
  }
}
}
</script>

<style scoped>
.heading {
  align-items: center;
  color: #e64a90;
  font-weight: bold;
}

.form-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #e64a90;
  border-radius: 8px;
}

.form-group {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.label {
  font-weight: bold;
  color: #e64a90;
  width: 100px; /* Fixed width for label */
}

.input-container {
  flex: 1;
}

.input-container input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  align-items: center;
}

.cancel-link {
  text-decoration: none;
  color: #333;
  font-weight: 600;
}

.login-button {
  padding: 8px 16px;
  background-color: #f58aba;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.login-button:hover {
  background-color: #e64a90;
}
</style>
