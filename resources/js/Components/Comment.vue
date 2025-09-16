<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full" />
        </div>
        <div>
            <div class="mt-1 prose prose-sm max-w-none" v-html="comment.html"></div>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{ comment.user.name }} {{ relativeDate(comment.created_at) }} ago</span>
        </div>
    </div>

    <div class="mt-2 flex-1">
        <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', (comment.id))" class="sm:flex sm:justify-end">
            <PrimaryButton type="button" class="mr-2" @click="$emit('edit', (comment.id))" :disabled="editDisabled">
                Edit
            </PrimaryButton>
            <DangerButton type="submit" class="ml-2" :disabled="false">
                Delete
            </DangerButton>
        </form>
    </div>
</template>

<script setup>
import {relativeDate} from "@/Utilities/date.js";
import DangerButton from "./DangerButton.vue";
import PrimaryButton from "./PrimaryButton.vue";

const props = defineProps(['comment', 'page', 'editDisabled']);

const emit = defineEmits(['delete', 'edit']);

</script>
