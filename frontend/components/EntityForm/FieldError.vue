<script setup>
    import { computed } from 'vue';
    const props = defineProps({
        fieldName: {
            type: String,
            required: true
        },
        errors: {
            type: Array,
            default: [],
            required: true
        },
    });

    const fieldErrors = computed(() => {
        return props.errors.filter(error => error.field === props.fieldName);
    });
</script>

<template>
    <ul v-if="fieldErrors.length > 0">
        <li v-for="error in fieldErrors" :key="error.message" class="error-message">
            {{ error.message }}
        </li>
    </ul>
</template>


<style scoped>
.error-message {
    color: red;
    margin-left: 5px;
}
</style>