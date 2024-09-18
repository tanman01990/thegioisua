<!-- DynamicButton.vue -->
<template>
    <button @click="handleClick" :class="buttonClass">
      {{ buttonText }}
    </button>
  </template>
  
  <script>
  export default {
    name: 'DynamicButton',
    props: {
      action: {
        type: String,
        required: true,
        validator: (value) => ['edit', 'delete'].includes(value),
      },
      item: {
        type: Object,
        required: true,
      },
    },
    computed: {
      buttonText() {
        return this.action.charAt(0).toUpperCase() + this.action.slice(1);
      },
      buttonClass() {
        return `btn btn-${this.action === 'edit' ? 'primary' : 'danger'}`;
      },
    },
    methods: {
      handleClick() {
        this.$emit(`${this.action}Item`, this.item);
      },
    },
  };
  </script>
  
  <style scoped>
  .btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    color: #fff;
    cursor: pointer;
  }
  
  .btn-primary {
    background-color: #007bff;
  }
  
  .btn-danger {
    background-color: #dc3545;
  }
  </style>