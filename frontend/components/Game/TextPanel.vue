
<script setup>
import { computed } from 'vue';

let props = defineProps({
    challengeLetter: {
        type: String,
        required: true,
    },
    used: {
        type: Boolean,
        required: true
    }
    // previousPageTitle: {
    //     type: String,
    //     default: null,
    // },
    // hasErrors: {
    //     type: Array,
    //     default: [],
    // },
});

let challengeLetters = computed(() => {
    const puzzleText = props.challenge.puzzle ?? '?';
    return puzzleText.toUpperCase().split('');
})

// defineEmits(['save']);
</script>

<template>
    <div class="centered-container">
        <div
            class="boxed-panel"
            :class="{ 'used-outline': used }"
        >
            <h1 class="character">{{challengeLetter ?? '?'}}</h1>
        </div>
    </div>
</template>

<style scoped>
.used-outline {
    border-color: red !important;
}
</style>


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
    width: 30;
    height: 30;
}

.character {
    margin: 0px;
}
</style>