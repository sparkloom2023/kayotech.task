<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Tasks</h1>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2">Title</th>
                    <th class="border border-gray-300 p-2">Description</th>
                    <th class="border border-gray-300 p-2">Status</th>
                    <th class="border border-gray-300 p-2">Due Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <td class="border border-gray-300 p-2">{{ task.title }}</td>
                    <td class="border border-gray-300 p-2">{{ task.description || 'N/A' }}</td>
                    <td class="border border-gray-300 p-2">{{ task.status }}</td>
                    <td class="border border-gray-300 p-2">{{ task.due_date || 'No due date' }}</td>
                </tr>
                <tr v-if="tasks.length === 0">
                    <td colspan="4" class="border border-gray-300 p-2 text-center">No tasks found.</td>
                </tr>
            </tbody>
        </table>
        <button
            @click="showModal = true"
            class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            + Create
        </button>
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Create Task</h2>
                <form @submit.prevent="createTask">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full border rounded p-2"
                            required
                        />
                        <span v-if="form.errors.title" class="text-red-500 text-sm">{{
                            form.errors.title
                        }}</span>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            v-model="form.description"
                            class="w-full border rounded p-2"
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select v-model="form.status" class="w-full border rounded p-2" required>
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                        <span v-if="form.errors.status" class="text-red-500 text-sm">{{
                            form.errors.status
                        }}</span>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Due Date</label>
                        <input
                            v-model="form.due_date"
                            type="date"
                            class="w-full border rounded p-2"
                        />
                        <span v-if="form.errors.due_date" class="text-red-500 text-sm">{{
                            form.errors.due_date
                        }}</span>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="mr-2 px-4 py-2 text-gray-600 hover:text-gray-800"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

export default {
    props: {
        tasks: Array,
    },
    setup() {
        const showModal = ref(false);
        const form = useForm({
            title: '',
            description: '',
            status: 'todo',
            due_date: null,
        });

        function createTask() {
            form.post(route('tasks.store'), {
                onSuccess: () => {
                    form.reset();
                    showModal.value = false;
                },

            });
        }

        return { form, createTask, showModal };
    },
};
</script>
