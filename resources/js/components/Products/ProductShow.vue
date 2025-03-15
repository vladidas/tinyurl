<template>
  <div class="container mx-auto p-4">
    <div v-if="product" class="bg-white rounded-lg shadow-sm overflow-hidden">
      <!-- Product Header -->
      <div class="p-6 border-b">
        <div class="flex justify-between items-start">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ product.name }}</h1>
            <div class="mt-2 flex items-center">
              <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-2">Rating:</span>
                <div class="bg-gray-200 rounded-full h-2 w-24">
                  <div
                    class="bg-indigo-600 rounded-full h-2"
                    :style="{ width: `${product.rating}%` }"
                  ></div>
                </div>
                <span class="ml-2 text-sm text-gray-600">{{ product.rating }}%</span>
              </div>
              <span class="mx-4 text-gray-300">|</span>
              <span class="text-2xl font-bold text-indigo-600">${{ product.price.toFixed(2) }}</span>
            </div>
          </div>
          <router-link
            :to="{ name: 'products' }"
            class="text-gray-600 hover:text-gray-900"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </router-link>
        </div>
      </div>

      <!-- Product Content -->
      <div class="p-6">
        <div class="mb-6">
          <h2 class="text-lg font-medium text-gray-900 mb-2">Description</h2>
          <p class="text-gray-600">{{ product.description }}</p>
        </div>

        <div class="mb-6" v-if="product.categories?.length">
          <h2 class="text-lg font-medium text-gray-900 mb-2">Categories</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="category in product.categories"
              :key="category.id"
              class="px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800"
            >
              {{ category.name }}
            </span>
          </div>
        </div>

        <!-- Related Products -->
        <div v-if="relatedProducts?.length" class="mb-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Related Products</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="related in relatedProducts"
              :key="related.id"
              class="border rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <router-link :to="{ name: 'product-show', params: { id: related.id }}">
                <h3 class="font-medium text-gray-900">{{ related.name }}</h3>
                <div class="mt-2 flex justify-between items-center">
                  <span class="text-indigo-600 font-medium">${{ related.price.toFixed(2) }}</span>
                  <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full h-1.5 w-16">
                      <div
                        class="bg-indigo-600 rounded-full h-1.5"
                        :style="{ width: `${related.rating}%` }"
                      ></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-500">{{ related.rating }}%</span>
                  </div>
                </div>
              </router-link>
            </div>
          </div>
        </div>

        <!-- Recently Viewed -->
        <div v-if="recentlyViewed?.length" class="mb-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Recently Viewed</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="viewed in recentlyViewed"
              :key="viewed.id"
              class="border rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <router-link :to="{ name: 'product-show', params: { id: viewed.id }}">
                <h3 class="font-medium text-gray-900">{{ viewed.name }}</h3>
                <div class="mt-2 flex justify-between items-center">
                  <span class="text-indigo-600 font-medium">${{ viewed.price.toFixed(2) }}</span>
                  <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full h-1.5 w-16">
                      <div
                        class="bg-indigo-600 rounded-full h-1.5"
                        :style="{ width: `${viewed.rating}%` }"
                      ></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-500">{{ viewed.rating }}%</span>
                  </div>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-else class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
      <p class="mt-4 text-gray-600">Loading product...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  setup() {
    const route = useRoute();
    const product = ref(null);
    const relatedProducts = ref([]);
    const recentlyViewed = ref([]);

    const loadProduct = async () => {
      try {
        const response = await axios.get(`/api/v1/products/${route.params.id}`);
        const responseData = response.data;

        product.value = responseData.data.product;
        relatedProducts.value = responseData.data.related_products;
        recentlyViewed.value = responseData.data.recently_viewed;
      } catch (error) {
        console.error('Error loading product:', error);
      }
    };

    onMounted(loadProduct);

    return {
      product,
      relatedProducts,
      recentlyViewed
    };
  }
};
</script>
