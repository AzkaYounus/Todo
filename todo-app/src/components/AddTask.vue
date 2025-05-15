<template>
  <div class="taskapp">
    
    <!-- Left side -->
    <div class="left">
      <form @submit.prevent="isEditing ? updateTask() : addTask()" class="add-task">
        
        <!-- Label according to state -->
        <label>{{ isEditing ? 'Edit Task' : 'Add Your Task' }}</label>
        
        <!-- Text input field -->
        <input type="text" v-model="text" placeholder="Add your task ..." />
        
        <!-- Button for add-task -->
        <button v-if="!isEditing" type="button" @click="setCalender = true" >Select date</button>
        <!--option 1-->
        <!-- <CalenderModel v-if="setCalender" :calenderDate="calenderDate" @date="SetDate" @close="setCalender = false" /> -->
         <!--option 2-->
        <CalenderModel v-if="setCalender" :displaydate="selectedDate" @date="SetDate" @close="setCalender = false" />



        <!-- Button for edit-task -->
        <button v-if="isEditing" type="button" @click="updateCalender = true">Update date</button>
        <CalenderModel v-if="updateCalender" :displaydate="selectedDate" @date="SetDate" @close="updateCalender = false"/>

        <p>Selected Date: {{ selectedDate }}</p>


        <!-- Submit button -->
        <button type="submit">
          {{ isEditing ? 'Update Task' : 'Add Task' }}
        </button>
      </form>
    </div>

    <!-- Right: Task List -->
    <div class="right">
      <div class="edit-task">
        <h1>Manage your task</h1>

        <div v-if="newtask.length > 0" class="newtask">
          <ul>
            <li v-for="(task, index) in newtask" :key="task.id">
              <p :class="{ completed: task.is_complete }">
                {{ task.text }}<br>
                Scheduled on: {{ task.date }}
              </p>

              <div class="buttons">
                <!-- Edit button -->
                <button @click="editTask(index)">
                  <i class="fas fa-edit"></i>
                </button>

                <button @click="deleteTask(index)">
                  <i class="fas fa-trash"></i>
                </button>

                <button @click="completeTask(index)">
                  <i class="fas fa-check"></i>
                </button>

                <button @click="openCalender(index)">
                  <i class="fas fa-calendar"></i>
                </button>
              </div>
              <CalenderModel v-if="task.updateCalender" :displaydate="task.date" @date="updateDate" @close="task.updateCalender = false"/>
            </li>
          </ul>
        </div>

        <div v-else class="newtask">
          <h1>Task list is empty!!</h1>
        </div>

        <div v-if="newtask.length > 0" class="btn">
          <button @click="deleteAll()">Delete all</button>
          <button @click="completeAll()">Complete All</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import CalenderModel from './CalenderModel.vue'
import {token} from '../store/auth';

export default {
  name: "AddTask",
  components: { CalenderModel },

  data() {
    return {
      text: "",
      selectedDate: "",
      newtask: [],
      isEditing: false,
      setCalender: false,
      updateCalender: false,
      editIndex: null,
      error: "",
    };
  },
  computed: {
    token() {
      return token.value;
    }
  },
  mounted() {
    this.fetchTasks();
  },

  methods: {
    /*************** Loading all tasks  ***************/
  async fetchTasks() {
  try {
    const response = await axios.get('https://ac44-110-39-134-182.ngrok-free.app/api/tasks', {
      headers: {
        Authorization: `Bearer ${this.token}`
      }
    });
    this.newtask = response.data;
  } catch (error) {
    console.error('Error fetching tasks:', error);
  }
},

    // When calendar emits a date
    SetDate(date) {
      if (!date) return alert('No date selected!');
      this.selectedDate = date;
      //settig date in calender//
      // this.calenderDate=date;

      this.setCalender = false;
    },

    /********** Add Task  ***************/
    async addTask() {
      if (!this.text.trim() || !this.selectedDate) {
        return alert('Enter both description and date.');
      }

      try {
        const payload = { text: this.text, date: this.selectedDate };

        const headers = {
          headers: {
            Authorization: `Bearer ${this.token}`, 
            'Content-Type': 'application/json'
          }
        };

        const response = await axios.post('https://e106-110-39-38-126.ngrok-free.app/api/tasks', payload, headers);
        this.newtask.push(response.data);
        this.text = "";
        this.selectedDate = "";
      } catch (err) {
        console.error('Add failed', err);
        alert('Failed to add task.');
      }
    },

    /********** Edit Task  ***************/
    async editTask(index) {
      const task = this.newtask[index];
      this.selectedDate = task.date;
      this.text = task.text;
      this.isEditing = true;
      this.editIndex = index;
      this.newtask[index].isEditing = true;
    },

    async updateTask() {
      const index = this.editIndex;
      const task = this.newtask[index];

      if (!task.text || task.text.trim() === "") {
        this.error = "Add your Task!";
        return;
      }

      this.error = "";

      try {

        const payload = {
          text: this.text,
          date: this.selectedDate
        };

        const headers = {
          headers: {
            Authorization: `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          }
        };

        const response = await axios.put(`https://e106-110-39-38-126.ngrok-free.app/api/tasks/${task.id}`, payload, headers);
        this.newtask[index] = {
          ...response.data,
          isEditing: false,
          updateCalender: false
        };

        this.text = "";
        this.selectedDate = "";
        this.isEditing = false;
        this.editIndex = null;
      } catch (error) {
        console.error("Error editing task:", error);
        this.error = "Failed to edit task. Try again.";
      }
    },

    /********** Delete Task  ***************/
    async deleteTask(index) {
      const task = this.newtask[index];

      try {
        
        const headers = {
          headers: {
            Authorization: `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          }
        };

        await axios.delete(`https://e106-110-39-38-126.ngrok-free.app/api/tasks/${task.id}`, headers);
        this.newtask.splice(index, 1); 
      } catch (error) {
        console.error("Error deleting task:", error);
        this.error = "Failed to delete task. Try again.";
      }
    },

    /********** Complete Task  ***************/
    async completeTask(index) {
      const task = this.newtask[index];
      const newStatus = !task.is_complete;

      try {
 
        const payload = {
          is_complete: newStatus
        };

        const headers = {
          headers: {
           Authorization: `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          }
        };

        const response = await axios.put(`https://e106-110-39-38-126.ngrok-free.app/api/tasks/${task.id}`, payload, headers);
        this.newtask[index] = {
          ...response.data,
          is_complete: newStatus
        };
      } catch (error) {
        console.error("Error completing task:", error);
        this.error = "Failed to complete task. Try again.";
      }
    },

    /********** Open Calendar to update date  ***************/
    openCalender(index) {
      this.selectedTaskIndex = index;
      this.newtask[index].updateCalender = true;
    },

    // Update date for a specific task
    async updateDate(date) {
      if (this.selectedTaskIndex !== null) {
        const task = this.newtask[this.selectedTaskIndex];

        try {

          const payload = {
            date: date
          };

          const headers = {
            headers: {
             Authorization: `Bearer ${this.token}`,
              'Content-Type': 'application/json'
            }
          };

          const response = await axios.put(`https://e106-110-39-38-126.ngrok-free.app/api/tasks/${task.id}`, payload, headers);
          this.newtask[this.selectedTaskIndex].date = response.data.date;
          this.newtask[this.selectedTaskIndex].updateCalender = false;
          this.selectedTaskIndex = null;
        } catch (error) {
          console.error("Error updating task date:", error);
          this.error = "Failed to update task date. Try again.";
        }
      }
    },

    /********** Delete All Tasks  ***************/
    async deleteAll() {
      if (!confirm("Are you sure you want to delete all tasks?")) {
        return;
      }

      try {
   
        const headers = {
          headers: {
            Authorization: `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          }
        };

        await axios.delete('https://e106-110-39-38-126.ngrok-free.app/api/delete-all', headers);
        this.newtask = [];
      } catch (error) {
        console.error("Error deleting all tasks:", error);
        this.error = "Failed to delete all tasks. Please try again.";
      }
    },

    /********** Complete All Tasks  ***************/
async completeAll() {
  try {
    
    const headers = {
      headers: {
       Authorization: `Bearer ${this.token}`,
        'Content-Type': 'application/json'
      }
    };

    const response = await axios.put('https://e106-110-39-38-126.ngrok-free.app/api/complete-all', null, headers);

    const newStatus  = response.data.newStatus;  

    this.newtask = this.newtask.map(task => ({
      ...task,
      is_complete: newStatus  
    }));

  } catch (error) {
    console.error("Error completing all tasks:", error);
    this.error = "Failed to complete all tasks. Please try again.";
  }
}

  }
};
</script>

<style scoped>
.taskapp{
    display: flex;
    flex-direction: row;
    gap:10px;
}
.add-task {
  width: 500px;
  height: 600px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #f9ebf1;
  padding: 20px;
  border-radius: 8px;
  border: #0f0b0d solid 3px;
}

.add-task label {
  font-size: 30px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #e64a90;
}

.add-task input {
  width: 100%;
  max-width: 400px;
  height: 200px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  resize: none;
  font-size:18px;
  color:#0f0b0d;
}

.add-task  button {
  margin-top: 20px;
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

.add-task  button:hover {
  background: #e64a90;
}
.edit-task{
  width: 500px;
  height: 600px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #f9ebf1;
  padding: 20px;
  border-radius: 8px;
  border: #0f0b0d solid 3px;
}
h1{
  font-size: 30px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #e64a90;
}
.newtask {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-height: 400px; 
  overflow-y: auto; 
  width: 100%; 
  padding: 10px;
}

.newtask ul {
  width: 100%;
  padding: 0;
  margin:0;
  
}

.newtask li {

  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  padding: 15px;
  border-radius: 8px;
  width: 90%;
  gap:10px;
  margin-bottom: 5px;
  text-align: left;
}

.newtask p{
  width: 100%;
  max-width: 350px;
  font-size: 16px;
  color: #0f0b0d;
  border-radius: 8px;
  height: auto;
  font-weight: 400px;
  
}
.newtask input {
  width: 65%;
  max-width: 350px;
  font-size: 16px;
  color: #0f0b0d;
  padding: 10px;
  border-radius: 8px;
  height: 70px;
}

.newtask p {
  background:none;
}

.newtask textarea {
  resize: none;
  background: #fff;
}

.newtask .buttons {
  display: flex;
  gap: 10px;
}

.newtask button {
  background: #f58aba;
  color: white;
  width: 50px;
  height: 30px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: bold;
}

.newtask button:hover {
  background: #e64a90;
}
.newtask .completed {
  text-decoration: line-through;
  color: rgb(149, 145, 145);
}
.btn button{
  margin-top: 20px;
  background: #f58aba;
  color: white;
  width: 170px;
  height: 50px;
  border: none;
  border-radius: 10px;
  font-size: 18px;
  font-weight: bold;
  text-decoration: none;
  margin:5px;
}
.btn  button:hover {
  background: #e64a90;
}
</style>