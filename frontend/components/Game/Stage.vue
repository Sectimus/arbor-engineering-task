
<script setup>
defineProps({
    // entity: {
    //     type: [Object],
    //     required: true,
    // },
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
    <div class="centered-container" style="min-width: 300px;">
        <slot name="content" >
            <p>[Entity Form Content]</p>
        </slot>
    </div>
    <hr/>
</template>


<style scoped>
.centered-container {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin: 1rem 0;
}
</style>