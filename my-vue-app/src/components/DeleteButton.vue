<template>
    <button @click="deleteProduct">Delete</button>
  </template>
  
  <script>
  export default {
    props: ['value', 'data', 'node'],
    methods: {
      deleteProduct() {
        this.$emit('delete-product', this.data);
      },
    },
  };
  </script>
  