<template>
    <AppLayout>
        <Container>
             <div>
                <Link v-if="selectedTopic" :href="route('posts.index')" class="text-indigo-500 hover:text-indigo-700 block mb-2">Back to all Posts</Link>
                <PageHeading
                    v-text="selectedTopic ? selectedTopic.name : 'All Posts'"
                />
                <p v-if="selectedTopic" class="mt-1 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>
            </div>

            <ul class="divide-y mt-4">
                <li
                    v-for="post in posts.data"
                    :key="post.id"
                    class="flex flex-col items-baseline justify-between md:flex-row"
                >
                    <Link
                        :href="post.routes.show"
                        class="group block px-2 py-4"
                    >
                        <span
                            class="text-lg font-bold group-hover:text-indigo-500"
                            >{{ post.title }}</span
                        >
                        <span
                            class="block pt-1 text-sm text-gray-600 first-letter:uppercase"
                            >{{ formattedDate(post) }} ago by
                            {{ post.user.name }}</span
                        >
                    </Link>
                    <Link
                        :href="route('posts.index', {'topic' : post.topic.slug})"
                        class="mb-2 rounded-full border border-pink-500 px-2 py-0.5 text-pink-500 hover:bg-indigo-500 hover:text-indigo-50"
                    >
                        {{ post.topic.name }}
                    </Link>
                </li>
            </ul>

            <Pagination :meta="posts.meta" :only="['posts']" class="mt-2" />
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/vue3";
import { relativeDate } from "@/Utilities/date.js";

defineProps(["posts", "selectedTopic"]);

const formattedDate = (post) => relativeDate(post.created_at);
</script>
