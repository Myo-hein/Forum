<script setup lang="ts">
import { useConfirm } from '@/Composables/useConfirm';
import ConfirmationModal from './ConfirmationModal.vue';
import { nextTick, ref, watchEffect } from 'vue';
import SecondaryButton from './SecondaryButton.vue';
import PrimaryButton from './PrimaryButton.vue';

const {state, confirm, cancel} = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if (state.show) {
        await nextTick();
        cancelButtonRef.value?.$el.focus();
    }
});

</script>

<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>

        <template #content>
            {{ state.message }}
        </template>

        <template #footer>
            <SecondaryButton ref="cancelButtonRef" @click="cancel">Cancel</SecondaryButton>
            <PrimaryButton @click="confirm" class="ml-3">Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>
