<script setup>
import { onMounted, ref } from 'vue'
import useApi from '@/composables/useApi'
import Product from '@/components/Product.vue'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'
import LoadingOverlay from '@/components/LoadingOverlay.vue'

const products = ref(null)
const showModal = ref(false)
const productModal = ref([])
const newProduct = ref(false)
const { fetchAll, data, loading } = useApi('api/products')

const defaultNoImage = `http://localhost:8000/images/no-image.jpg`

const { user } = useAuth()
const router = useRouter()

function ShowModal(product, isNew = false) {
    showModal.value = true
    productModal.value = product

    newProduct.value = false
    if(isNew)
        newProduct.value = true
}

async function Logout() {

    await fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });

    router.push('/login')
}

function ProductCreated(product) {
    products.value.push(product)
    
    console.log(products.value)
}

onMounted(async () => {
    await fetchAll()
    products.value = data.value
})

</script>

<template>
    <div class="div-page">
        <h1 class="page-header">
          Bem-vindo {{ user.name }}
        </h1>
        
        <div class="div-inner-header">
            <button @click="ShowModal([], true)">Novo produto</button>
            <button @click="Logout()">Sair</button>
        </div>

        <div class="div-list">
            <div class="div-product-card" :class="{inactive: !product.active}" v-for="(product, i) in products" :key="product.length" @click="ShowModal(product)">
                <div class="div-img-container">
                    <img :src="`http://localhost:8000/${product.images[0]?.path.replace('public/', '')}` ?? defaultNoImage" @error="$event.target.src = defaultNoImage"/>
                </div>

                <div class="div-product-info">
                    <h4 class="h4-product-name">{{ product.title }}</h4>
                    <div class="span-product-description">
                        <span class="line-clamp-2" v-html="product.description"></span>
                    </div>
                    <div class="div-values">
                        <div class="div-left">
                            <span>Custo:</span>
                            <span>{{ product.cost }}</span>
                        </div>
                        <div class="div-right">
                            <span>Preço:</span>
                            <span>{{ product.sale_price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal">
            <Product :product="productModal" @close-modal="showModal = !showModal" @product-created="ProductCreated($event)" :newProduct="newProduct" />
        </div>

        <LoadingOverlay v-if="loading"/>
</div>
</template>

<style scoped>

    .div-page {
        width: 100%;
        color: black;
    }

    .page-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .div-inner-header {
        margin-bottom: 20px;
        text-align: end;
        display: flex;
        justify-content: space-between;
    }

    .div-list {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 50px;
    }

    .div-product-card {
        max-width: 280px;
        height: 350px;
        background: white;
        padding: 1em;
        box-shadow: 0 5px 5px #e1e1e1;
        cursor: pointer;
    }

    .div-product-card:hover {
        background-color: var(--default-app-color);
        color: white;
    }

    .inactive {
        opacity: .5;
    }

    .div-product-info {
        justify-content: space-between;
        align-items: center;
    }

    .div-img-container {
        width: 100%;
        height: 50%;
        border: 2px solid var(--default-app-color);
        background: white;
    }

    .div-img-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .h4-product-name {
        text-align: center;
        font-weight: 600;
        margin: .5em 0 0px;
        font-size: 1.3em;
    }

    .span-product-description {
        height: 65px;
        padding-top: 5px;
        font-size: 1em;
    }

    .div-values {
        display: flex;
        justify-content: space-between;
        margin: 10px 0px;
        font-size: .85em;
    }

    .div-left,
    .div-right {
        display: flex;
        flex-direction: column;
    }

    .div-left span:first-of-type {
        color: orangered;
    }

    .div-right span:first-of-type {
        color: green;
    }
</style>