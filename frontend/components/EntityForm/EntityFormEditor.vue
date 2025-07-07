<script setup>
import EntityForm from './EntityForm.vue';
import { computed } from 'vue';

const props = defineProps({
    entity: {
        type: Object,
        required: true,
    },
    previousPageTitle: {
        type: String,
        default: 'entity list',
    },
    errors: {
        type: Array,
        default: [],
    },
});

const hasErrors = computed(() => props.errors.length > 0);

defineEmits(['save']);
</script>

<template>
    <EntityForm 
        :entity=entity
        :previousPageTitle=previousPageTitle
        :hasErrors=hasErrors
        @save="$emit('save', entity)"
    >
        <template #head>
            <slot name="head">
                <h1>Edit Entity: {{ entity.id }}</h1>
            </slot>
        </template>
        <template #content>
            <slot name="content">
                <h1>[Edit Form Content]</h1>
            </slot>
        </template>
    </EntityForm>
</template>