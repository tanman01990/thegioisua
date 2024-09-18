<template>
    <div>
      <input type="file" @change="handleFileUpload" ref="fileInput" />
      <button @click="submitFile" :disabled="!file">Upload File</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'FileUploadComponent',
    data() {
      return {
        file: null,
      };
    },
    methods: {
      handleFileUpload() {
        this.file = this.$refs.fileInput.files[0];
      },
      submitFile() {
        // You can implement your file upload logic here
        // For example, you can use axios or fetch to send the file to your server
        const formData = new FormData();
        formData.append('file', this.file);
  
        // Here's an example using axios
        axios.post('/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
          .then((response) => {
            console.log('File uploaded successfully:', response.data);
            // Handle successful upload response
          })
          .catch((error) => {
            console.error('Error uploading file:', error);
            // Handle upload error
          });
      },
    },
  };
  </script>