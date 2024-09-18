<template>
    <div class="add-inventory-form">
      <h2>Add New Product</h2>
      <form @submit.prevent="addInventory">
        <div class="form-group">
          <label for="inventoryName">Tên Kho:</label>
          <input
            type="text"
            id="inventoryName"
            v-model="inventoryName"
            required
          >
        </div>
        
        <div class="form-group">
          <label for="address">Địa chỉ:</label>
          <textarea
            id="address"
            v-model="address"
          ></textarea>
        </div>
        
        <div class="form-group">
          <label for="phone">SĐT:</label>
          <input
            type="text"
            id="phone"
            v-model="phone"
            required
          >
        </div>
        
       
        <button type="submit">Thêm Kho</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        inventoryName: '',
        address: '',
        phone: ''
      }
    },
    methods: {
      addInventory() {
        // Create a new product object
        const newInventory = {
          name: this.inventoryName,
          address: this.address,
          phone: this.phone,
        }
        
        // Here you would typically send this data to your backend API
        console.log('New product:', newInventory)
        
        // Reset form fields
        this.inventoryName = ''
        this.address = ''
        this.phone = ''
        
        // Optionally, emit an event to notify the parent component
        this.$emit('inventory-added', newInventory)
      }
    }
  }
  </script>
  
  <style scoped>
  .add-product-form {
    max-width: 500px;
    margin: 0 auto;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  input, textarea, select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #45a049;
  }
  </style>