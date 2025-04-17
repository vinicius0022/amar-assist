<template>
    <div class="loading-overlay">
        <div class="loader">
            <img src="/camera-icon.svg" alt="Loading" />
            <span>Carregando...</span>
        </div>
    </div>
</template>

<style scoped>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(3px);
        background: rgba(255, 255, 255, 0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loader {
        text-align: center;
        font-family: 'Courier New', Courier, monospace;
        animation: pulse 1.5s infinite ease-in-out;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .loader img {
        width: 60px;
        filter: sepia(0.5) saturate(2) contrast(0.8);
    }

    @keyframes pulse {
        0% { opacity: 0.7; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
        100% { opacity: 0.7; transform: scale(1); }
    }

</style>