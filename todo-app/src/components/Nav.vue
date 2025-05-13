<template>
  <div class="nav">
    <!-- if no token show login and register -->
    <router-link v-if="!token" to="/login">
      <button>Log In</button>
    </router-link>
    <router-link v-if="!token" to="/signup">
      <button>Sign Up</button>
    </router-link>
<!-- if token available show logout -->
    <button v-if="token" @click="logout">Log Out</button>
  </div>
</template>

<script>
import { token } from '../store/auth';

export default {
  name: 'Nav',

  computed: {
    token() {
      return token.value;
    }
  },

  methods: {
    logout() {
      localStorage.removeItem('authToken');
      token.value = null;
      this.$router.push('/');
    }
  }
};
</script>
<style scoped>
.nav {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 20px;
  width: 100%;
  box-sizing: border-box; 
}

.nav button {
  background: #f58aba;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
}

.nav button:hover {
  background: #e64a90;
}
</style>
