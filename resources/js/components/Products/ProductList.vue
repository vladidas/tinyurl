<template>
  <div class="container mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Products</h1>
      <button 
        @click="openCreateModal"
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors duration-200"
      >
        <span class="flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Product
        </span>
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <div class="relative">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search products..."
              class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
              @input="handleSearch"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
        </div>
        <div class="flex gap-4">
          <select 
            v-model="filters.sortBy"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
            @change="loadProducts"
          >
            <option value="created_at">Date</option>
            <option value="name">Name</option>
            <option value="price">Price</option>
            <option value="rating">Rating</option>
          </select>
          <select 
            v-model="filters.direction"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
            @change="loadProducts"
          >
            <option value="desc">Descending</option>
            <option value="asc">Ascending</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr 
              v-for="product in products.data" 
              :key="product.id"
              class="hover:bg-gray-50 transition-colors duration-150"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <router-link 
                  :to="{ name: 'product-show', params: { id: product.id }}"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-900"
                >
                  {{ product.name }}
                </router-link>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-500 line-clamp-2">{{ product.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <div class="text-sm font-medium text-gray-900">${{ product.price.toFixed(2) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center justify-center">
                  <div class="w-full max-w-[100px]">
                    <div class="h-2 bg-gray-200 rounded-full">
                      <div
                        class="h-full rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500"
                        :style="{ width: `${product.rating}%` }"
                      ></div>
                    </div>
                    <div class="text-xs text-center mt-1 text-gray-500">
                      {{ product.rating }}%
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <div class="flex items-center justify-center space-x-3">
                  <button 
                    @click="openEditModal(product)"
                    class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button 
                    @click="deleteProduct(product.id)"
                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="!products.data.length" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
      </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4 rounded-lg shadow-sm">
      <div class="flex-1 flex justify-between sm:hidden">
        <button
          @click="goToPage(products.meta?.current_page - 1)"
          :disabled="products.meta?.current_page === 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': products.meta?.current_page === 1 }"
        >
          Previous
        </button>
        <button
          @click="goToPage(products.meta?.current_page + 1)"
          :disabled="products.meta?.current_page === products.meta?.last_page"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': products.meta?.current_page === products.meta?.last_page }"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ products.meta?.from || 0 }}</span>
            to
            <span class="font-medium">{{ products.meta?.to || 0 }}</span>
            of
            <span class="font-medium">{{ products.meta?.total || 0 }}</span>
            results
          </p>
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <button
              v-for="page in products.meta?.last_page"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                page === products.meta?.current_page
                  ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">
          {{ editingProduct ? 'Edit Product' : 'Create Product' }}
        </h2>
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Name
            </label>
            <input
              v-model="form.name"
              type="text"
              class="w-full px-3 py-2 border rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Description
            </label>
            <textarea
              v-model="form.description"
              class="w-full px-3 py-2 border rounded"
              rows="3"
            ></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Price
            </label>
            <input
              v-model="form.price"
              type="number"
              step="0.01"
              class="w-full px-3 py-2 border rounded"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Rating (0-100)
            </label>
            <input
              v-model="form.rating"
              type="number"
              min="0"
              max="100"
              class="w-full px-3 py-2 border rounded"
            />
          </div>
          <div class="flex justify-end gap-2">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-gray-600 hover:text-gray-800"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
              {{ editingProduct ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, reactive, onMounted } from 'vue';

export default {
  setup() {
    const products = ref({ data: [], meta: {} });
    const showModal = ref(false);
    const editingProduct = ref(null);
    const filters = reactive({
      search: '',
      sortBy: 'created_at',
      direction: 'desc',
      page: 1
    });

    const form = reactive({
      name: '',
      description: '',
      price: '',
      rating: 0
    });

    const loadProducts = async () => {
      try {
        const response = await axios.get('/api/v1/products', {
          params: filters
        });
        products.value = response.data;
      } catch (error) {
        console.error('Error loading products:', error);
      }
    };

    const resetForm = () => {
      form.name = '';
      form.description = '';
      form.price = '';
      form.rating = 0;
      editingProduct.value = null;
    };

    const openCreateModal = () => {
      resetForm();
      showModal.value = true;
    };

    const openEditModal = (product) => {
      editingProduct.value = product;
      form.name = product.name;
      form.description = product.description;
      form.price = product.price;
      form.rating = product.rating;
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      resetForm();
    };

    const handleSubmit = async () => {
      try {
        if (editingProduct.value) {
          await axios.put(`/api/v1/products/${editingProduct.value.id}`, form);
        } else {
          await axios.post('/api/v1/products', form);
        }
        closeModal();
        loadProducts();
      } catch (error) {
        console.error('Error saving product:', error);
      }
    };

    const deleteProduct = async (id) => {
      if (!confirm('Are you sure you want to delete this product?')) return;
      
      try {
        await axios.delete(`/api/v1/products/${id}`);
        loadProducts();
      } catch (error) {
        console.error('Error deleting product:', error);
      }
    };

    const handleSearch = () => {
      filters.page = 1;
      loadProducts();
    };

    const goToPage = (page) => {
      filters.page = page;
      loadProducts();
    };

    onMounted(loadProducts);

    return {
      products,
      showModal,
      editingProduct,
      filters,
      form,
      loadProducts,
      openCreateModal,
      openEditModal,
      closeModal,
      handleSubmit,
      deleteProduct,
      handleSearch,
      goToPage
    };
  }
};
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 