<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue'
import useApi from '@/composables/useApi'
import LoadingOverlay from './LoadingOverlay.vue'

const props = defineProps(['product', 'newProduct'])

const emits = defineEmits(['close-modal'])

const { update, error, create } = useApi('api/products')

const defaultNoImage = `http://localhost:8000/images/no-image.jpg`
const selectedFiles = ref([])
const currentIndex = ref(0)
const fileInput = ref(null)
const isLoading = ref(false)

// Method to handle file selection
const onFileChange = (event) => {
    selectedFiles.value = Array.from(event.target.files)
    Object.assign(props.product, {images:selectedFiles.value})
};

// Method to trigger the hidden file input
const triggerFileInput = () => {
    fileInput.value.click();
};


function nextSlide() {
  if (currentIndex.value < props.product.images.length - 1) {
    currentIndex.value++
  }
}

function prevSlide() {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

async function submitUpdate () {
    isLoading.value = true;

    const formData = new FormData()
    formData.append('_method', 'PUT')
    formData.append('title', props.product.title)
    formData.append('description', props.product.description)
    formData.append('sale_price', props.product.sale_price)
    formData.append('cost', props.product.cost)
    formData.append('active', props.product.active)

    selectedFiles.value.forEach(file => {
        formData.append('images[]', file)
    })

    await update(props.product.id, formData).then(res => {
        if(error.value)
            alert(error.value.message)
        else
            Object.assign(props.product, res)
    })

    isLoading.value = false;
}

async function createProduct () {

    const formData = new FormData()
    formData.append('title', props.product.title)
    formData.append('description', props.product.description)
    formData.append('sale_price', props.product.sale_price)
    formData.append('cost', props.product.cost)
    formData.append('active', props.product.active)

    selectedFiles.value.forEach(file => {
        formData.append('images[]', file)
    })

    await create(formData).then(res => {
        if(error.value)
            alert(error.value.message)
        else
            Object.assign(props.product, res)
    })
}

const title = computed({
  get() {
    return props.product.title
  },
  set(newValue) {
    Object.assign(props.product, {title: newValue})
  }
})

const description = computed({
  get() {
    return props.product.description
  },
  set(newValue) {
    Object.assign(props.product, {description: newValue})
  }
})

const cost = computed({
  get() {
    return props.product.cost
  },
  set(newValue) {
    Object.assign(props.product, {cost: newValue})
  }
})

const sale_price = computed({
  get() {
    return props.product.sale_price
  },
  set(newValue) {
    Object.assign(props.product, {sale_price: newValue})
  }
})
</script>

<template>
    <div id="div-container-modal" class="modal">
        <div id="div-user-content" class="modal-content">

            <div class="div-product-card">
                <div class="close-modal">
                    <span @click="emits('close-modal')">X</span>
                </div>

                <div class="carousel">
                    <div class="carousel-window">
                        <div class="carousel-track" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                            <div class="carousel-slide" v-for="(image, index) in product.images" :key="index">
                                <img :src="`http://localhost:8000/${image?.path?.replace('public/', '')}` ?? defaultNoImage" :alt="'Image ' + (index + 1)" />
                            </div>
                        </div>
                    </div>
                    
                    <button class="nav prev" @click="prevSlide" :disabled="!product.images || currentIndex === 0">‹</button>
                    <button class="nav next" @click="nextSlide" :disabled="!product.images || currentIndex === product.images?.length - 1">›</button>
                </div>

                <!-- Hidden input for file selection -->
                <input
                    ref="fileInput"
                    type="file"
                    multiple
                    accept="image/*"
                    style="display: none"
                    @change="onFileChange"
                />

                <div class="div-btns">
                    <button @click="triggerFileInput">Acrescentar Imagens</button>
                    <button class="btn-status" @click="Object.assign(props.product, {active: !props.product.active})">{{props.product.active ? 'Desativar' : 'Ativar'}}</button>
                </div>

                <div class="div-product-info">
                    <input class="h4-product-name" type="text" v-model="title">

                    <div class="span-product-description">
                        <textarea v-model="description"></textarea>
                    </div>
                    
                    <div class="div-values">
                        <div class="div-left">
                            <span>Custo:</span>
                            <input type="text" v-model="cost">
                        </div>
                        <div class="div-right">
                            <span>Preço:</span>
                            <input type="text" v-model="sale_price">
                        </div>
                    </div>
                </div>

                <button v-if="props.newProduct" class="btn-save-info" @click="createProduct()">Criar</button>
                <button v-else class="btn-save-info" @click="submitUpdate()">Gravar</button>
            </div>
        </div>
        <LoadingOverlay v-if="isLoading"/>
    </div>
</template>

<style scoped>

    input, 
    textarea {
        border-color: rgba(119, 136, 153, 0.26);
    }

    .modal-content {
        position: absolute;
        width: 500px;
        height: 600px;
    }

    .close-modal span {
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal {
        display: flex;
        justify-content: flex-end;
        position: absolute;
        right: 3px;
        top: -3px;
        color: var(--default-app-color);
    }

    .close-modal i {
        font-size: 1.5em;
        cursor: pointer;
    }

    .div-product-card {
        width: 100%;
        height: 100%;
        background: white;
        padding: 1em;
        box-shadow: 0 5px 5px #e1e1e1;
    }

    .div-img-carousel {
        width: 100%;
        text-align: center;
    }

    .div-product-info {
        justify-content: space-between;
        align-items: center;
    }

    .h4-product-name {
        font-weight: 600;
        margin: .5em 0 0px;
        font-size: 1.3em;
        display: flex;
        text-align: center;
        width: 100%;
    }

    .span-product-description {
        height: 65px;
        margin: 20px 0px;
        font-size: 1em;
    }

    .span-product-description textarea {
        width: 100%;
        height: 100%;
        resize: none;
        overflow: auto;
    }

    .div-values {
        display: flex;
        justify-content: space-between;
        margin: 10px 0px;
        font-size: .85em;
    }

    .div-btns { 
        display: flex;
        justify-content: space-between;
        margin: 10px 0 0;
    }

    .btn-status {
        width: 80px;
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

    .btn-save-info {
        background: var(--default-app-color);
        color: white;
        width: 100%;
        font-weight: 600;
        margin-top: 12px
    }

    .btn-save-info:hover {
        background: white;
        color: var(--default-app-color);
    }














    .carousel {
        position: relative;
        width: 100%;
        height: 45%;
        margin: auto;
        overflow: hidden;
    }

    .carousel-window {
        width: 100%;
        height: 100%;
        overflow: hidden;
        border: 2px solid var(--default-app-color);
    }

    .carousel-track {
        display: flex;
        height: 100%;
        transition: transform 0.4s ease;
    }

    .carousel-slide {
        min-width: 100%;
        box-sizing: border-box;
    }

    .carousel-slide img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: contain;
    }

    .nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.4);
        color: white;
        border: none;
        font-size: 2rem;
        padding: 0.25em 0.6em;
        cursor: pointer;
        z-index: 10;
        user-select: none;
    }

    .nav.prev {
        left: 10px;
    }

    .nav.next {
        right: 10px;
    }

    .nav:disabled {
        opacity: 0.5;
        cursor: default;
    }

</style>