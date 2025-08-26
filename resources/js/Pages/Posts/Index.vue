<script setup>
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { relativeDate } from '@/Utilities/date';
import { Link } from '@inertiajs/vue3';

defineProps([
    'posts'
]);

const formattedDate = (date) => relativeDate(date);

</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y divide-gray-200">
                <li v-for="post in posts.data"
                    :key="post.id"
                >
                    <Link :href="route('posts.show', post.id)" class="block px-2 py-3 group">
                        <span class="text-lg font-medium text-gray-900 group-hover:text-indigo-500 block">
                            {{ post.title }}
                        </span>

                        <span class="text-sm text-gray-600 pt-2">{{ formattedDate(post.created_at) }} ago by {{ post?.user?.name }}</span>

                    </Link>
                </li>
            </ul>

            <Pagination :meta="posts.meta" />
        </Container>
    </AppLayout>
</template>
