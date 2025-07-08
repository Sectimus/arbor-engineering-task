
<script setup>
import { computed, ref } from 'vue';
import TextPanel from './TextPanel.vue';

const props = defineProps({
    challenge: {
        type: [Object],
        required: true,
        default: () => ({
            puzzle: "???",
            used: [],
        }),
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
// let challengeLetters = computed(() => {
//     const puzzleText = props.challenge.puzzle ?? '?';

//     let charFreq = {};
//     for (let char of puzzleText) {
//         if (!props.challenge.used || !(char in props.challenge.used)){
//             charFreq[char] = 0;
//             continue;
//         }

//         charFreq[char] = props.challenge.used[char];
//     }
//     return charFreq;
// })


let challengeLetters = computed(() => {
    const arr = [];

    const puzzleText = props.challenge.puzzle ?? '?';
    // Create a shallow copy to avoid mutating the original prop
    let usedChars = { ...props.challenge.used };

    let charCounts = {};
    let obj = {}

    const charsInPuzzle = puzzleText.split('');
    charsInPuzzle.forEach(charInPuzzle => {
        charCounts[charInPuzzle] = (charCounts[charInPuzzle] ?? 0) + 1

        const obj = {'char': charInPuzzle, 'i': charCounts[charInPuzzle]};

        arr.push(obj);
    });

    return arr;
});

// {char: 'a', used: true/false}
// {char: 'b', used: true/false} etc... (duplicate chars allowed)
// let challengeLetters = computed(() => {
//     const arr = [];

//     const puzzleText = props.challenge.puzzle ?? '?';
//     // Create a shallow copy to avoid mutating the original prop
//     let usedChars = { ...props.challenge.used };

//     const charsInPuzzle = puzzleText.split('');
//     charsInPuzzle.forEach(charInPuzzle => {
//         let obj = { char: charInPuzzle, used: false };
//         if (charInPuzzle in usedChars) {
//             obj.used = true;
//             // Now safe to delete from the local copy
//             delete usedChars[charInPuzzle];
//         }
//         arr.push(obj);
//     });

//     return arr;
// });

// let usedCharacters = computed(() => {
//     const usedChars = props.challenge.used;
//     return usedChars;

//     // const puzzleText =  ?? '?';
//     // return puzzleText.toUpperCase().split('');
// })

const emit = defineEmits(['submitAnswer']);

// Guess input model
const answer = ref('');
</script>

<template>
    <div style="min-width: 300px;">
        <div class="d-flex flex-wrap justify-content-center">
            <TextPanel v-for="(v, k) in challengeLetters" class="mx-1"
                :challengeLetter="v.char" 
                :used="challenge.used && v.i <= challenge.used[v.char]"
            />
        </div>
        <form @submit.prevent="$emit('submitAnswer', answer)" class="d-flex flex-wrap justify-content-center input-group mb-3">
            <input 
                v-model="answer"
                type="text" 
                class="form-control-lg text-center text-uppercase fs-2" 
                placeholder="Word..." aria-label="Word..." aria-describedby="btn-submit"
            />
            <button class="btn btn-outline-primary" type="submit" id="btn-submit">Guess</button>
        </form>
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