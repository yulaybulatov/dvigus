<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

const blockedInput = ref(null);
const isBlocked = ref(false); // Используем булево значение для состояния

const form = useForm({
    is_blocked: isBlocked.value ? 1 : 0, // Устанавливаем начальное значение
});

const toggleBlockUser = () => {
    // Обновляем значение в форме перед отправкой
    form.is_blocked = isBlocked.value ? 0 : 1; // Меняем значение на противоположное

    form.patch(route('profile.update'), {
        preserveScroll: false,
        onSuccess: () => {
            isBlocked.value = !isBlocked.value; // Инвертируем состояние блокировки после успешного запроса
            closeModal();
        },
        onError: () => blockedInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

// Обновляем значение is_blocked при изменении isBlocked
watchEffect(() => {
    form.is_blocked = isBlocked.value ? 1 : 0;
});

const closeModal = () => {
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Block/Unblock Account</h2>
        </header>

        <DangerButton @click="toggleBlockUser">
            {{ isBlocked ? 'Unblock Account' : 'Block Account' }}
        </DangerButton>
    </section>
</template>
