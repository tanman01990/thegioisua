<template>
  <div>
    <button @click="handleEditClick">Edit</button>
    <button @click="handleDeleteClick">Delete</button>
  </div>
</template>

<script>
export default {
  name: "ActionButtonRenderer",
  props: {
    params: {
      type: Object,
      required: true,
    },
    // editAction: {
    //   type: Function,
    //   required: true,
    // },
    deleteAction: {
      type: Function,
      required: true,
    },
  },
  methods: {
    handleEditClick() {
      console.log('Edit button clicked', this.params.data);
    },
    handleDeleteClick() {
      this.deleteAction(this.params);
    },
  },
};
</script>