<template>
  <div>
    <h2>Main Inventory</h2>
    <div>
      <AddInventory @inventory-added="handleNewInventory" />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import AddInventory  from './AddInventory.vue';

export default {
  components: {
    AddInventory
  },

  data() {
    return {

    };
  },

  methods: {
    handleNewInventory(inventory) {
      // Do something with the new product
      console.log('In:', inventory)
    }
  },
};
</script>

<style scoped>
.ag-theme-alpine {
  width: 100%;
  height: 500px;
}
</style>
