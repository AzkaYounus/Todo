import { ref } from 'vue';

//ref() is a function that creates a reactive reference to a value. 
// That means Vue will track changes to that value, 
// automatically update the UI or trigger reactivity wherever that value is used.

export const token = ref(localStorage.getItem('authToken'));