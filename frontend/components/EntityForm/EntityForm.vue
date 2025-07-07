
<script setup>
defineProps({
    entity: {
        type: [Object],
        required: true,
    },
    previousPageTitle: {
        type: String,
        default: null,
    },
    hasErrors: {
        type: Array,
        default: [],
    },
});

defineEmits(['save']);
</script>

<template>
    <div>
        <slot name="head">
            <h1>[Entity Form Title]</h1>
        </slot>
        <div v-if="hasErrors">
            <p>There was a problem with your submission. Please check the form and try again.</p>
        </div>
        <div v-else>
            <p><em>Remember to press save!</em></p>
        </div>
    </div>
    <div class="centered-container" style="min-width: 300px;">
        <slot name="content" >
            <p>[Entity Form Content]</p>
        </slot>
    </div>
    <hr/>
    <div>
        <div class="centered-container">
            <button @click="$router.back()">Back to {{ previousPageTitle ?? 'previous page' }}</button>
            <button @click="$emit('save', entity)">Save</button>
        </div>
    </div>
</template>


<style scoped>
.centered-container {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin: 1rem 0;
}
</style>