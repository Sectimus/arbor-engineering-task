<script setup>
    import { ref } from 'vue';
    import { useChallengeStore } from '../stores/ChallengeStore.js';
    import { useRouter } from 'vue-router';
    import { createChallenge } from '../models/Challenge.js';
    
    const challengeStore = useChallengeStore();
    const router = useRouter();

    const formData = createChallenge();
    const durationDisplay = ref('');

    const errors = ref([]);

    async function handleSave(challenge) {
        try {
            let resp = await challengeStore.addChallenge(formData);
            if(resp.success) {
                console.log('Challenge created successfully');
                router.push('/');
            } else {
                errors.value = resp.errors;
            }
        } catch (error) {
            console.error('Failed to save:', error);
        }
    }

    function handleDurationInput(event){
        let text = event.target.value;

        // Remove the extra colon
        if (text.length == 3 && text.charAt(2) == ':') {
            text = text.slice(0, 2);
        // Add the missing colon
        } else if (text.length == 3){
            text = text.slice(0, 2) + ':' + text.slice(2);
        } else if (text.length == 2){
            text = text+':';
        }

        durationDisplay.value = text
    }

    // try to convert the mm:ss to an int representing the total seconds
    function handleDurationChange(event){
        const durationText = event.target.value;
        const parts = durationText.split(':');

        if (parts.length === 2) {
            const minutes = parseInt(parts[0], 10) || 0;
            const seconds = parseInt(parts[1], 10) || 0;
            
            formData.value.duration = (minutes*60)+seconds;
        }
    }
</script>

<template>

    <div>
        <EntityFormCreator 
        :entity="formData"
        :errors="errors"
        
        @save="handleSave">
            <template #head>
                <h1>Create New Challenge</h1>
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
                        <input 
                            type="text" 
                            :value="durationDisplay"
                            @input="handleDurationInput"
                            @change="handleDurationChange"
                            placeholder="00:00"
                            maxlength="5"
                        />
                        <FieldError :errors="errors" fieldName="duration" />
                    </div>
                    <div>
                        <label for="isrc">ISRC:</label>
                        <input type="text" v-model.lazy="formData.isrc" />
                        <FieldError :errors="errors" fieldName="isrc" />
                    </div>
                </fieldset>
            </template>
        </EntityFormCreator>
    </div>
</template>