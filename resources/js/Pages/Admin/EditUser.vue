<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import EditUserHeader from '@/Components/Admin/EditUserHeader.vue';
import EditUserForm from '@/Components/Admin/EditUserForm.vue';
import UserOrderHistory from '@/Components/Admin/UserOrderHistory.vue';

const props = defineProps({
    editingUser: Object,
    isSuperAdmin: Boolean,
});

const form = useForm({
    name: props.editingUser.name,
    email: props.editingUser.email,
    password: '',
    password_confirmation: '',
});

const updateUser = () => {
    const data = {
        name: form.name,
        email: form.email,
    };

    if (form.password) {
        data.password = form.password;
        data.password_confirmation = form.password_confirmation;
    }

    form.patch(`/admin/users/${props.editingUser.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Edit User" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <EditUserHeader />

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <EditUserForm
                :editing-user="editingUser"
                :form="form"
                @submit="updateUser"
            />

            <UserOrderHistory :orders-count="editingUser.orders_count" />
        </div>
    </div>
</template>
