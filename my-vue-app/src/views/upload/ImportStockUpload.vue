<template>
  <div>
    <h2>Nhập Vào Kho</h2>
    <div>
      <select v-model="selectedOption" @change="handleSelection">
        <option value="" disabled>---Chọn kho---</option>
        <option v-for="option in options" :key="option.id" :value="option.id">
          {{ option.name }}
        </option>
      </select>
    </div>
    <br/>
    <input type="file" @change="onFileChange" />
    <button @click="uploadFile" :disabled="!selectedFile">Upload</button>
    <p v-if="uploading">Uploading...</p>
    <p v-if="error">{{ error }}</p>
    <p v-if="uploadResponse">{{ uploadResponse }}</p>
  </div>
</template>

<script>
const baseURL = import.meta.env.VITE_BASE_API_URL;
import axios from 'axios';

export default {
  data() {
    return {
      selectedFile: null,
      uploading: false,
      error: null,
      uploadResponse: null,
      options: [],
      selectedOption: '',
    };
  },
  methods: {
    async fetchOptions() {
      try {
        const response = await axios.get(`${baseURL}/inventory`);
        this.options = response.data;
      } catch (error) {
        console.error('Error fetching options:', error);
      }
    },
    handleSelection() {
      console.log('Selected option:', this.selectedOption);
      // You can perform additional actions here based on the selection
    },
    getSelectedOptionName() {
      const selected = this.options.find(option => option.id === this.selectedOption);
      return selected ? selected.name : '';
    },
    onFileChange(event) {
      this.selectedFile = event.target.files[0];
    },
    async uploadFile() {
      if (!this.selectedFile) return;

      this.uploading = true;
      this.error = null;
      this.uploadResponse = null;

      const formData = new FormData();
      formData.append('file', this.selectedFile);
      formData.append('type', 'import_transaction');
      formData.append('inventory', this.selectedOption);

      try {
        const response = await  axios.post(`${baseURL}/upload` , formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        this.uploadResponse = `Nhập Kho Thành Công : ${response.data.filename}`;
      } catch (err) {
        this.error = `Error uploading file: ${err.response?.data?.message || err.message}`;
      } finally {
        this.uploading = false;
      }
    }
  },
  mounted() {
    this.fetchOptions();
  }
};
</script>

<style scoped>
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>