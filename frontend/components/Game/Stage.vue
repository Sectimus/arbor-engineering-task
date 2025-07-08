
<script setup>
import { computed, ref } from 'vue';
import TextPanel from './TextPanel.vue';
import FlashyForm from '../Input/FlashyForm.vue';

const props = defineProps({
    challenge: {
        type: [Object],
        required: true,
        default: () => ({
            puzzle: "???",
            used: [],
        }),
    },
});

//every letter in the challenge, mapped by it's character index. Ex. [{'char': 'b', 'i': 1}, {'char': 'a', 'i': 1}, {'char': 'a', 'i': 2}, {'char': 'c', 'i': 1},] 
let challengeLetters = computed(() => {
    const arr = [];
    const puzzleText = props.challenge.puzzle ?? '?';
    let charCounts = {};

    const charsInPuzzle = puzzleText.split('');
    charsInPuzzle.forEach(charInPuzzle => {
        if(!charCounts[charInPuzzle]){
            charCounts[charInPuzzle] = 0;
        }
        charCounts[charInPuzzle] += 1

        const obj = {'char': charInPuzzle, 'i': charCounts[charInPuzzle]};

        arr.push(obj);
    });

    return arr;
});

const emit = defineEmits(['submitAnswer']);

const answer = ref('');

function handleSubmit() {
    emit('submitAnswer', answer.value);
}
</script>

<template>
    <div style="min-width: 300px;">
        <div class="d-flex flex-wrap justify-content-center">
            <TextPanel v-for="obj in challengeLetters" class="mx-1"
                :challengeLetter="obj.char" 
                :used="challenge.used && obj.i <= challenge.used[obj.char] || false"
            />
            <FlashyForm v-model="answer" @submit="handleSubmit"/>
            <button class="btn btn-outline-success" type="submit" id="btn-submit">Complete</button>
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