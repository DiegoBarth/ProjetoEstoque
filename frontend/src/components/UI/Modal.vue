<template>
    <div class="overlay">
        <div v-bind="$attrs" class="modal">
            <slot></slot>
        </div>        
    </div>
</template>
<script setup>

</script>
<style scoped>
    .overlay {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        background: rgba(61, 61, 61, 0.377);
    }

    .modal {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        position: absolute;
        min-width: 30vw;
        min-height: 20vh;
        background: white;
        z-index: 11;
        opacity: 1;
    }
</style>