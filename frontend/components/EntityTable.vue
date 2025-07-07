<script setup>
    const emit = defineEmits(['delete']);
    defineProps({
        slug: {
            type: String,
            required: true,
            default: 'entity'
        },
        entities: {
            type: Array,
            required: true,
            default: []
        },
        isLoading: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: ''
        }
    });

    const confirmDelete = (entity) => {
        if(confirm("Are you really sure you want to delete this entity?")){
            emit('delete', entity);
        }
    };
</script>

<template>
    <div>
        <div v-if="isLoading">Loading...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else>
            <table>
                <thead>
                    <tr>
                        <slot name="head">
                            <th>{{ slug ?? 'Entity' }}</th>
                        </slot>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="entity in entities" 
                        :key="entity.id" 
                    >
                            <slot name="row" :entity="entity">
                                <td>{{ entity.id }}</td>
                            </slot>
                            <td>
                                <div><router-link :to="`/${slug}/edit/${entity.id}`">Edit</router-link></div>
                                <div><a href=# @click="confirmDelete(entity)">Delete</a></div>
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.table-row {
    cursor: pointer;
    transition: background-color 0.2s;
}
.table-row:hover {
    background-color: #f0f0f0;
}
</style>