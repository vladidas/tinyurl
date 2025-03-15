<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
          Product Management System
        </h1>
        <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
          A scalable Laravel-based product management system built with Domain-Driven Design principles.
        </p>
      </div>

      <!-- Menu Cards -->
      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Products Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
              </div>
              <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Products</h3>
                <p class="mt-2 text-sm text-gray-500">
                  Manage your product catalog with advanced features.
                </p>
              </div>
            </div>
            <div class="mt-6">
              <router-link
                to="/products"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                View Products
                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </router-link>
            </div>
          </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
              </div>
              <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Categories</h3>
                <p class="mt-2 text-sm text-gray-500">
                  Organize products into categories.
                </p>
              </div>
            </div>
            <div class="mt-6">
              <button
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Coming Soon
              </button>
            </div>
          </div>
        </div>

        <!-- Reports Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
              <div class="ml-5">
                <h3 class="text-lg font-medium text-gray-900">Reports</h3>
                <p class="mt-2 text-sm text-gray-500">
                  View analytics and generate reports.
                </p>
              </div>
            </div>
            <div class="mt-6">
              <button
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Coming Soon
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <dl class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">12,345</dd>
        </div>
        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">25</dd>
        </div>
        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
          <dt class="text-sm font-medium text-gray-500 truncate">Avg Rating</dt>
          <dd class="mt-1 text-3xl font-semibold text-gray-900">4.8</dd>
        </div>
      </dl>

      <div class="mb-4">
        <label class="mr-2">Sort by:</label>
        <select 
          v-model="sortBy" 
          class="border rounded p-1"
          :disabled="loading"
        >
          <option value="name-asc">Name (A-Z)</option>
          <option value="name-desc">Name (Z-A)</option>
          <option value="price-asc">Price (Low to High)</option>
          <option value="price-desc">Price (High to Low)</option>
          <option value="category-asc">Category (A-Z)</option>
          <option value="category-desc">Category (Z-A)</option>
        </select>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-if="loading" class="col-span-full text-center">
          Loading...
        </div>
        <template v-else>
          <div v-for="product in sortedProducts" :key="product.id">
            <!-- ... existing product display code ... -->
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home',
  data() {
    return {
      products: [],
      sortBy: 'name-asc',
      loading: false
    }
  },
  computed: {
    sortedProducts() {
      if (!this.products.length) return [];
      
      // Create a new array only when needed
      const [field, direction] = this.sortBy.split('-');
      
      return [...this.products].sort((a, b) => {
        let comparison = 0;
        
        switch (field) {
          case 'name':
            comparison = (a.name || '').localeCompare(b.name || '');
            break;
          case 'price':
            comparison = (Number(a.price) || 0) - (Number(b.price) || 0);
            break;
          case 'category':
            const catA = a.category?.name || '';
            const catB = b.category?.name || '';
            comparison = catA.localeCompare(catB);
            break;
        }
        
        return direction === 'asc' ? comparison : -comparison;
      });
    }
  },
  methods: {
    async fetchProducts() {
      if (this.loading) return;
      
      this.loading = true;
      try {
        const response = await axios.get('/api/products');
        this.products = response.data;
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        this.loading = false;
      }
    }
  },
  created() {
    this.fetchProducts();
  }
}
</script> 