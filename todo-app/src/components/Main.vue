<template>
  <div class="container">
    <h1>Stay Organized, Stay Productive</h1>
    <p>Manage your tasks effortlessly and get things done with ease.</p>
    
    <router-link to="/plantask">
      <button v-if="token">Get Started</button>
    </router-link>
    <!-- <router-link to="/test">
      <button v-if="token">Test</button>
    </router-link> -->
  </div>
</template>

<script>
import { token } from '../store/auth';

export default {
  name: 'Main',
  computed: {
    token() {
      return token.value;
    }
  },
};
</script>

<style scoped>

.container {
  width: 400px;
  padding: 30px;
  text-align: center;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  border: 3px solid #e64a90d1; 
}

h1 {
  color: #e64a90;
  font-size: 26px;
  margin-bottom: 10px;
  font-weight: bold;
  text-transform: uppercase;
}


p {
  color: #555;
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 20px;
}


button {
  background: #f58aba; 
  color: white;
  width: 170px;
  height: 50px;
  border: none;
  border-radius: 10px;
  font-size: 18px;
  font-weight: bold;
  text-decoration: none;
}

button:hover {
  background: #e64a90; 

}
</style>

