<template>
    <div>
      <ul>
        <li v-for="item in items" :key="item.id">
          {{ item.name }}
          <DynamicButton
            action="edit"
            :item="item"
            @editItem="handleEditItem"
          />
          <DynamicButton
            action="delete"
            :item="item"
            @deleteItem="handleDeleteItem"
          />
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import DynamicButton from './DynamicButton.vue';
  
  export default {
    components: {
      DynamicButton,
    },
    data() {
      return {
        items: [
          { id: 1, name: 'Item 1' },
          { id: 2, name: 'Item 2' },
          { id: 3, name: 'Item 3' },
        ],
      };
    },
    methods: {
      handleEditItem(item) {
        console.log('Edit item:', item);
        // Implement your edit logic here
      },
      handleDeleteItem(item) {
        console.log('Delete item:', item);
        // Implement your delete logic here
      },
    },
  };
  </script>