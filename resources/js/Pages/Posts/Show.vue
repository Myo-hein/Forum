<script setup lang="ts">
import Comment from '@/Components/Comment.vue';
import Container from '@/Components/Container.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import MarkdownEditor from '@/Components/MarkdownEditor.vue';
import Pagination from '@/Components/Pagination.vue';
import Pill from '@/Components/Pill.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useConfirm } from '@/Composables/useConfirm';
import AppLayout from '@/Layouts/AppLayout.vue';
import { relativeDate } from '@/Utilities/date';
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps([
    'post',
    'comments'
]);

console.log(props.post);

const formattedDate = (date) => {
    return relativeDate(date);
}

const commentForm =  useForm({
    body: ''
});

const commentTextAreaRef = ref(null);

const commentIdBeingEdited = ref(null);

const commentBeingEdited = computed(
    () => props.comments.data.find(
        comment => comment.id === commentIdBeingEdited.value
    ));

const { confirmation } = useConfirm();

const cancelEdit = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdited.value?.body;
    commentTextAreaRef.value?.focus();
};

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.data.id), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset()
    });
}

const updateComment = () => {
    commentForm.put(route('comments.update', {
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page
    }), {
        preserveScroll: true,
        onSuccess: () => cancelEdit()
    });
}

const deleteComment = async (commentID) => {
    if (! await confirmation('Are you sure you want to delete this comment?')) {
        return;
    }

    router.delete(route('comments.destroy', {
        comment: commentID,
        page: props.comments.data.length > 1
            ? props.comments.meta.current_page
            : Math.max(props.comments.meta.current_page - 1, 1)
    }), {
        preserveScroll: true,
    });
}

</script>

<template>
    <AppLayout>
        <Container>
             <Pill :href="route('posts.index', {topic: post.data.topic.slug})">{{ post.data.topic.name }}</Pill>

            <PageHeading class="block mt-2">{{ post.data.title }}</PageHeading>

            <h2 class="text-sm text-gray-600">{{ formattedDate(post.data.created_at) }} ago by {{ post.data.user.name }}</h2>

            <article class="mt-6 prose prose-sm max-w-none" v-html="post.data.html">
            </article>

            <div class="mt-10">
                <h2>Comments</h2>

                <form @submit.prevent v-if="$page.props.auth.user" class="mt-4">
                    <div>
                        <InputLabel class="sr-only" for="body" value="Add a comment"/>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" class="mt-1 block w-full" v-model="commentForm.body" editorClass="min-h-[160px]" :disabled="commentForm.processing" placeholder="Speak your mind Spock..." />
                        <InputError :message="commentForm.errors.body" class="mt-2"/>
                    </div>
                    <PrimaryButton
                        class="mt-2"
                        :disabled="commentForm.processing"
                        @click="() => commentIdBeingEdited ? updateComment() : addComment()"
                        v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"
                    ></PrimaryButton>
                    <SecondaryButton
                        v-if="commentIdBeingEdited"
                        class="mt-2 ml-2"
                        type="button"
                        :disabled="commentForm.processing"
                        @click="cancelEdit"
                    >
                        Cancel
                    </SecondaryButton>
                </form>

                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="py-4">
                        <Comment
                            :comment="comment"
                            :page="comments.meta.current_page"
                            :editDisabled="commentIdBeingEdited === comment.id"
                            @delete="deleteComment"
                            @edit="editComment"
                        />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" class="mt-4"/>
            </div>
        </Container>

    </AppLayout>
</template>
