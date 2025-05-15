<template>
<div>
    <div class="heading">
        LOG IN
    </div>

  <form class="form-container" @submit.prevent="login">
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

    <div class="form-actions">
      <a href="/" class="cancel-link">Cancel</a>
      <button type="submit" class="login-button">Log In</button>
    </div>

    <div v-if="error" class="error-messages">
  <div v-for="(messages, field) in error" :key="field">
    <div v-for="message in messages" :key="message" class="error-text">
      {{ message }}
    </div>
  </div>
</div>
  </form>
  <div>
    <p>
     Don't have an account? <a href="/signup">Register here</a>.
    </p>
</div>

</div>
</template>

<script>
import axios from 'axios';
import {token} from '../store/auth';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null, 
    };
  },
  methods: {
  async login() {
    try {
      const response = await axios.post('http://localhost:8000/api/login', {
        email: this.email,
        password: this.password,
      });
      const receivedToken = response.data.token;
      localStorage.setItem('authToken', receivedToken);
      token.value = receivedToken; 
      this.$router.replace('/');
      //it will remove token from storage after 2 minutes
      setTimeout(function()
      {localStorage.removeItem('authToken');
        token.value = null;       
      }, 120 * 1000);
      
    } catch (error) {
      console.error('Login failed', error);
      this.error = error.response?.data?.errors || ['Login failed, please try again.'];
    }
  }
},
};
</script>

<style scoped>
.heading{
    align-items: center;
    color:#e64a90;
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

.error-messages {
  color: red;
  margin-top: 10px;
}
.error-text {
  font-size: 0.9em;
}

</style>
