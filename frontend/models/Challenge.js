import { ref } from 'vue';

export function createChallenge(data = {}) {
  return ref({
    id: data.id || null,
    title: data.title || '',
    artist: data.artist || '',
    duration: data.duration || 0,
    isrc: data.isrc || '',
  });
}
