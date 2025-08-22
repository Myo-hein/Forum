<script setup>
import Container from '@/Components/Container.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    posts: Array,
})

</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y divide-gray-200">
                <li v-for="post in posts"
                    :key="post.id"
                    class="py-4 flex items-center justify-between space-x-4"
                >
                    <span class="text-lg font-medium text-gray-900">
                        {{ post.title }}
                    </span>
                </li>
            </ul>
        </Container>
    </AppLayout>
</template>
