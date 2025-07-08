
<script setup>
import { computed } from 'vue';
import TextPanel from './TextPanel.vue';

const props = defineProps({
    challenge: {
        type: [Object],
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

// Uppercase letter array
let letters = computed(() => {
    const puzzleText = props.challenge.puzzle ?? '?';
    return puzzleText.toUpperCase().split('');
})

// defineEmits(['save']);
</script>

<template>
    <div style="min-width: 300px;">
        <div class="d-flex flex-wrap justify-content-center">
            <TextPanel v-for="letter in letters" class="mx-1"
                :letter="letter" 
            />
        </div>
        <div class="d-flex flex-wrap justify-content-center input-group mb-3">
            <input type="text" class="form-control-lg" placeholder="Guess..." aria-label="Guess..." aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2">Button</button>
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