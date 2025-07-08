<script setup>
import { defineModel, defineEmits } from 'vue';

const model = defineModel();
defineEmits(['submit']);
</script>

<template>
    <form @submit.prevent="$emit('submit', model)" class="d-flex flex-wrap justify-content-center input-group mb-3">
        <input 
            v-model="model"
            type="text" 
            class="form-control-lg text-center text-uppercase fs-2" 
            placeholder="Word..." aria-label="Word..." aria-describedby="btn-submit"
        />
        <button class="btn btn-outline-primary" type="submit" id="btn-submit">Guess</button>
    </form>
</template>
