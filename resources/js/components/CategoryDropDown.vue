<template>
  <div class="form-group">
    <label for="category_id">Category</label>
    <select
      class="form-control"
      name="category_id"
      id="category_id"
      v-model="category"
    >
      <option value="">Select Category</option>
      <option
        v-for="data in categories"
        :key="data.id"
        :value="data.id"
      >
        {{ data.name }}
      </option>
    </select>
  </div>
</template>

<script>
export default {
  data() {
    return {
      category: '',
      categories: [],
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    getCategories() {
      axios
        .get('/api/category')
        .then((response) => {
          this.categories = response.data;
        })
        .catch((error) => {
          console.error('Error loading categories:', error);
        });
    },
  },
};
</script>
