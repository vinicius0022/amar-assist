<script setup>
import { onMounted, ref } from 'vue'

const products = ref(null)


onMounted(async () => {
   products.value = await fetch('http://localhost:8000/api/products')
      .then((response) => response.json())
})
</script>

<template>
    <div class="div-page">
        <div>
          Products header
        </div>
      
        <div class="div-list">
            <div class="div-product-card" v-for="product in products">
                <div class="div-img-carousel">
                    <img :src="`http://localhost:8000/${product.images[0]?.path.replace('public/', '')}`" />
                </div>

                <div class="div-product-info">
                    <span class="span-product-name">{{ product.title }}</span>
                    <span class="span-description line-clamp">{{ product.description }}</span>
                    <div class="div-values">
                        <span>Preço: {{ product.sale_price }}</span>
                        <span>Custo: {{ product.cost }}</span>
                    </div>
                </div>
            </div>
          </div>
    </div>
</template>

<style scoped>

    .div-page {
        width: 100%;
    }

    .div-list {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        gap: 10px;
    }

    .div-product-card {
        height: 250px;
        width: 250px;
        border: 2px solid white;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
    }

    .div-img-carousel {
        width: 100%;
        text-align: center;
    }

    .div-img-carousel img {
        width: 50%;
    }

    .span-product-name {
        text-align: center;
        font-weight: 600;
        display: block;
        margin: 5px 0px;
        font-size: 1.1em;
    }

    .span-description {
        display: block;
        height: 40px;
    }

    .div-values {
        display: flex;
        justify-content: space-evenly;
        margin-top: 10px;
        font-size: .9em;
    }
</style>