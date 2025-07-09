import { ref } from 'vue';

export function createChallenge(data = {}) {
  return ref({
    puzzle: data.puzzle || '???',
    used: data.used || [],
    score: data.score || 0,
    solutions: data.solutions || [],
    solvable: data.solvable || [],
  });
}