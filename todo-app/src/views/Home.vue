<template>
  <div class="main-container">
    <Nav/>
    <div class="main-content">
      <Main />
    </div>
  </div>
</template>

<script>
import Nav from "../components/Nav.vue";
import Main from "../components/Main.vue";

export default {
  name: 'Home',
  components: {
    Nav,
    Main,
  },
  props: {
  },
};
</script>

<style scoped>
.main-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: white;
}

.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}
</style>
