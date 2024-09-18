<template>
    <button @click="editProduct">Edit1</button>
  </template>
  
  <script>
  export default {
    props: ['value', 'data', 'node'],
    methods: {
      editProduct() {
        this.$emit('edit-product', this.data);
        alert('edit');
      },
    },
  };
  </script>
  