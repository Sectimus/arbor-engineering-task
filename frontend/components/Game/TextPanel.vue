
<script setup>

defineProps({
    letter: {
        type: String,
        required: true,
    },
    // previousPageTitle: {
    //     type: String,
    //     default: null,
    // },
    // hasErrors: {
    //     type: Array,
    //     default: [],
    // },
});

// defineEmits(['save']);
</script>

<template>
    <div class="centered-container">
        <div class="boxed-panel">
            <h1 class="character">{{letter ?? '?'}}</h1>
        </div>
    </div>
</template>


<style scoped>
.centered-container {
    display: flex;
    justify-content: center;
}

.boxed-panel {
    background-color: #f5f5f5;
    border: 2px solid #666666;
    border-radius: 8px;
    padding: 1rem;
    max-width: 100px;
    max-height: 100px;
}

.character {
    margin: 0px;
}
</style>