<script setup lang="ts">
import Comment from '@/Components/Comment.vue';
import Container from '@/Components/Container.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextArea from '@/Components/TextArea.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { relativeDate } from '@/Utilities/date';
import { useForm } from '@inertiajs/vue3';

const props = defineProps([
    'post',
    'comments'
]);

const formattedDate = (date) => {
    return relativeDate(date);
}

const commentForm =  useForm({
    body: ''
});

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.data.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset()
    });
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

                <form @submit.prevent v-if="$page.props.auth.user" class="mt-4">
                    <div>
                        <InputLabel class="sr-only" for="body" value="Add a comment"/>
                        <TextArea id="body" class="mt-1 block w-full" rows="3" v-model="commentForm.body" :disabled="commentForm.processing" placeholder="How do you think?"/>
                        <InputError :message="commentForm.errors.body" class="mt-2"/>
                    </div>
                    <PrimaryButton class="mt-2" :disabled="commentForm.processing" @click="addComment">
                        Post Comment
                    </PrimaryButton>
                </form>

                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="py-4">
                        <Comment :comment="comment"/>
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" class="mt-4"/>
            </div>
        </Container>

    </AppLayout>
</template>
