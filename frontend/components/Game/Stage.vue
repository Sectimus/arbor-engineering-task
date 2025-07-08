
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
    <div class="centered-container" style="min-width: 300px;">
        <th>{{ challenge.puzzle ?? 'loading' }}</th>
        <TextPanel v-for="letter in letters"
            :letter="letter" 
        />
        <slot name="content" >
            <p>[Entity Form Content]</p>
        </slot>
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