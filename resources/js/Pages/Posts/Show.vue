<script setup lang="ts">
import Comment from '@/Components/Comment.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { relativeDate } from '@/Utilities/date';

const props = defineProps([
    'post',
    'comments'
]);

const formattedDate = (date) => {
    return relativeDate(date);
}

</script>

<template>
    <AppLayout>
        <Container>
            <h1 class="text-2xl font-bold">{{ post.data.title }}</h1>

            <h2 class="text-sm text-gray-600">{{ formattedDate(post.data.created_at) }} ago by {{ post.data.user.name }}</h2>

            <article class="mt-6 ">
                <pre class="whitespace-pre-wrap font-sans">{{ post.data.body }}</pre>
            </article>

            <div class="mt-10">
                <h2>Comments</h2>

                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="py-4">
                        <Comment :comment="comment"/>
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" class="mt-4" />
            </div>
        </Container>

    </AppLayout>
</template>
