
<script setup>
import { computed, ref } from 'vue';
import TextPanel from './TextPanel.vue';
import FlashyForm from '../Input/FlashyForm.vue';
import { useChallengeStore } from '../../stores/ChallengeStore.js';
const challengeStore = useChallengeStore();

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

const emit = defineEmits(['submitAnswer', 'complete']);
const answer = ref('');
const name = ref('');
const complete = ref(false);

//every letter in the challenge, mapped by it's character index. Ex. (baac) [{'char': 'b', 'i': 1}, {'char': 'a', 'i': 1}, {'char': 'a', 'i': 2}, {'char': 'c', 'i': 1},] 
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

function handleSubmit() {
    if(!complete.value){
        emit('submitAnswer', answer.value);
    }
}

function handleComplete() {
    if(!challengeStore.complete.value){
        emit('complete', name.value);
    }
    
    challengeStore.complete = true;
}

function handleReset() {
    emit('reset');
}
</script>

<template>
    <div style="min-width: 300px;">
        <div class="d-flex flex-wrap justify-content-center">
            <TextPanel v-for="obj in challengeLetters" class="mx-1"
                :challengeLetter="obj.char" 
                :used="challenge.used && obj.i <= challenge.used[obj.char] || false"
            />
            
            
            <form @submit.prevent="handleSubmit" 
                class="d-flex flex-wrap justify-content-center input-group mb-3 flex-column flex-sm-row">
                <button 
                    @click="handleReset" 
                    class="btn btn-outline-secondary mb-2 mb-sm-0 me-0" 
                    type="button" 
                    id="btn-new"
                >
                    <i class="bi bi-arrow-clockwise fs-1"></i>
                </button>
                <input 
                  v-model="answer"
                  type="text" 
                  class="form-control-lg text-center text-uppercase fs-2 mb-2 mb-sm-0 me-0" 
                  placeholder="Word..." aria-label="Word..." aria-describedby="btn-submit"
                  :disabled="!challenge.isSolvable || challengeStore.complete"
                />
                <button 
                  class="btn btn-outline-primary" 
                  type="submit" 
                  id="btn-submit"
                  :disabled="!challenge.isSolvable || challengeStore.complete">
                    Guess
                </button>
                <div v-if="challengeStore.error" class="invalid-feedback d-block text-center">
                    {{ challengeStore.error }}
                </div>
            </form>

            <form 
                @submit.prevent="handleComplete" 
                class="d-flex flex-wrap justify-content-center input-group mb-3" 
                :class="{'d-none': challenge.score == 0 }"
            >
                <input 
                    id="input-guess"
                    v-model="name"
                    type="text" 
                    class="form-control-lg text-center text-uppercase" 
                    placeholder="Your name..." aria-label="Your name..." aria-describedby="btn-complete"
                    :disabled="challengeStore.complete"
                />
                <button 
                    class="btn btn-outline-success" 
                    type="submit" 
                    id="btn-complete"
                    :disabled="challengeStore.complete">
                        Complete
                </button>
            </form>

            <div v-if="challengeStore.complete">
                <div v-if="challengeStore.solutions.length > 0">
                    <h2 class="text-danger">Possible solutions:</h2>
                    <ul class="list-group list-group-flush">
                        <li v-for="solution in challengeStore.solutions" class="list-group-item text-centre">
                            
                            <p class="text-center mb-0">{{ solution.term }}</p>
                        </li>
                    </ul>
                </div>
                <div v-else>
                    <h2 class="text-success">You found all possible solutions!</h2>
                </div>
            </div>
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