<template>
  <div>
    <button @click="fetchTasks">Get All Tasks</button>
    <ul v-if="tasks.length">
      <li v-for="task in tasks" :key="task.id">
        {{ task.text }} - {{ task.is_complete ? 'Completed' : 'Incomplete' }}
      </li>
    </ul>
    <p v-else>No tasks available.</p>

   <div class="date-container">
    <h3>Select a Date</h3>
    <input type="date" v-model="date" />
    <p>Selected Date: {{ date }}</p>
    <button>Save Date</button>
  </div>
  </div> 
</template>

<script>
import axios from 'axios';

export default {
  name: 'Test',
  data() {
    return {
      tasks: [],
    };
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await axios.get('http://localhost:8000/api/tasks');
        this.tasks = response.data;
      } catch (error) {
        console.error('Error fetching tasks:', error);
      }
    },
  },
};
</script>

<style scoped>
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
