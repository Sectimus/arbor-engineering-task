<script setup>
    import { ref, onMounted } from 'vue';

    import { useRoute } from 'vue-router';
    import FieldError from '../components/EntityForm/FieldError.vue';
    import { useChallengeStore } from '../stores/ChallengeStore.js';
    import { useRouter } from 'vue-router';
    import { createChallenge } from '../models/Challenge.js';
    
    const challengeStore = useChallengeStore();

    const route = useRoute();
    const router = useRouter();

    const challengeId = parseInt(route.params.id);

    const challenge = createChallenge();
    const formData = createChallenge();

    const errors = ref([]);

    onMounted(async () => {
        challenge.value = await challengeStore.getChallenge(challengeId);
        formData.value = { ...challenge.value };
    });

    async function handleSave(challenge) {
        try {
            let resp = await challengeStore.updateChallenge(formData);
            if(resp.success) {
                console.log('Challenge created successfully');
                router.push('/');
            } else {
                errors.value = resp.errors;
            }
        } catch (error) {
            console.error('Failed to edit:', error);
        }
    }
</script>

<template>
    <div>
        <!-- <EntityFormEditor
        :entity="formData"
        :errors="errors"
        previousPageTitle="challenge list"
        @save="handleSave">
            <template #head>
                <h1>Edit Challenge: {{ challenge.title }}</h1>
            </template>

            <template #content>
                <fieldset>
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" v-model.lazy="formData.title" />
                        <FieldError :errors="errors" fieldName="title" />
                    </div>
                    <div>
                        <label for="artist">Artist:</label>
                        <input type="text" v-model.lazy="formData.artist" />
                        <FieldError :errors="errors" fieldName="artist" />
                    </div>
                    <div>
                        <label for="duration">Duration:</label>
                        <input type="number" v-model.lazy.number="formData.duration" />
                        <FieldError :errors="errors" fieldName="duration" />
                    </div>
                    <div>
                        <label for="isrc">ISRC:</label>
                        <input type="text" v-model.lazy="formData.isrc" />
                        <FieldError :errors="errors" fieldName="isrc" />
                    </div>
                </fieldset>
            </template>
        </EntityFormEditor> -->
    </div>
</template>