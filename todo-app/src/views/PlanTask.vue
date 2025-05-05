<template>
  <div class="container">
    <AddTask/>
</div>
  
</template>

<script>
import AddTask from "../components/AddTask.vue"
export default {
  name: 'PlanTask',
  components:{
    AddTask,
  },
}
</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
}
</style>