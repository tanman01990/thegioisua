<template>
  <div>
    <h2>Export file</h2>
    <div>
      <div class='wrapper'>
        <select v-model="selectedOption" @change="handleSelection">
        <option value="" disabled>---Chọn file để download---</option>
        <option v-for="option in options" :key="option" :value="option">
          {{ option }}
        </option>
      </select>
        <button @click="exportExcel">Xuất file</button>
        <img v-if="isLoading" src="@/assets/giphy.webp" alt="Loading..." />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
const baseURL = import.meta.env.VITE_BASE_API_URL;

export default {
  components: {
  },

  data() {

    return {
      selectedFile: null,
      error: null,
      options: [],
      selectedOption: '',
    };
  },
  created() {
    this.fetchOptions();
  },
  methods: {
    async exportExcel() {
      try {
        this.isLoading = true;
        const fileName = this.selectedOption.split('/').pop();
        const response = await axios.get(`${baseURL}/export/${fileName}`, {
          responseType: 'blob'
        });

        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = `${fileName}.xlsx`;
        link.click();
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.isLoading = false;
      }
    },
    handleSelection() {
      console.log('Selected option:', this.selectedOption);
      // You can perform additional actions here based on the selection
    },
    async fetchOptions() {
      try {
        const response = await axios.get(`${baseURL}/list-file`);
        console.log("response"+response.data.files);
        this.options = response.data.files;
      } catch (error) {
        console.error('Error fetching options:', error);
      }
    },
  },
};
</script>

<style scoped>

</style>
