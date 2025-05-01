<template>
    <div class="p-6">
        <!-- بخش نمایش اعلان‌ها -->
        <div v-if="flash.success || flash.error" class="mb-4">
            <div v-if="flash.success" class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                {{ flash.success }}
            </div>
            <div v-if="flash.error" class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                {{ flash.error }}
            </div>
        </div>

        <h1 class="text-2xl font-bold mb-4">Tasks</h1>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2">Title</th>
                    <th class="border border-gray-300 p-2">Description</th>
                    <th class="border border-gray-300 p-2 cursor-pointer" @click="sortBy('status')">
                        Status
                        <span v-if="sortKey === 'status'" class="ml-1">
                            {{ sortOrder === 'asc' ? '↑' : '↓' }}
                        </span>
                    </th>
                    <th class="border border-gray-300 p-2 cursor-pointer" @click="sortBy('due_date')">
                        Due Date
                        <span v-if="sortKey === 'due_date'" class="ml-1">
                            {{ sortOrder === 'asc' ? '↑' : '↓' }}
                        </span>
                    </th>
                    <th class="border border-gray-300 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="task in sortedTasks" :key="task.id">
                    <!-- Task Row -->
                    <tr>
                        <td class="border border-gray-300 p-2">{{ task.title || 'N/A' }}</td>
                        <td class="border border-gray-300 p-2">{{ task.description || 'N/A' }}</td>
                        <td class="border border-gray-300 p-2">{{ task.status || 'N/A' }}</td>
                        <td class="border border-gray-300 p-2">{{ task.due_date || 'No due date' }}</td>
                        <td class="border border-gray-300 p-2">
                            <button @click="editTask(task)" class="text-blue-500 hover:text-blue-700 mr-2">
                                Edit
                            </button>
                            <button @click="deleteTask(task)" class="text-red-500 hover:text-red-700 mr-2">
                                Delete
                            </button>
                            <button @click="toggleSubtasks(task.id)" class="text-green-500 hover:text-green-700">
                                {{ expandedTaskId === task.id ? 'Hide Subtasks' : 'View Subtasks' }}
                            </button>
                        </td>
                    </tr>
                    <!-- Subtasks Dropdown Row (Table Format) -->
                    <tr v-if="expandedTaskId === task.id">
                        <td colspan="5" class="border border-gray-300 p-2">
                            <div class="pl-4">
                                <h3 class="text-lg font-semibold mb-2">Subtasks</h3>
                                <table v-if="task.subtasks && Array.isArray(task.subtasks) && task.subtasks.length > 0"
                                    class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 p-2">Title</th>
                                            <th class="border border-gray-300 p-2">Status</th>
                                            <th class="border border-gray-300 p-2">Due Date</th>
                                            <th class="border border-gray-300 p-2">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(subtask, index) in task.subtasks" :key="subtask.id">
                                            <td class="border border-gray-300 p-2">
                                                Subtask {{ index + 1 }}: {{ subtask.title || 'N/A' }}
                                            </td>
                                            <td class="border border-gray-300 p-2">{{ subtask.status || 'N/A' }}</td>
                                            <td class="border border-gray-300 p-2">{{ subtask.due_date || 'No due date'
                                                }}</td>
                                            <td class="border border-gray-300 p-2">
                                                <!-- <button @click="editSubtask(task, index)"
                                                    class="text-blue-500 hover:text-blue-700 mr-2">
                                                    Edit
                                                </button> -->
                                                <button @click="deleteSubtask(task.id, subtask.id)"
                                                    class="text-red-500 hover:text-red-700">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p v-else class="text-gray-500">No subtasks found.</p>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr v-if="!sortedTasks || sortedTasks.length === 0">
                    <td colspan="5" class="border border-gray-300 p-2 text-center">No tasks found.</td>
                </tr>
            </tbody>
        </table>
        <button @click="openCreateModal" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Create
        </button>
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">
                    {{ modalType === 'create' ? 'Create Task' : modalType === 'edit' ? 'Edit Task' : 'Edit Subtask' }}
                </h2>
                <form @submit.prevent="submitForm">
                    <!-- Task/Subtask Fields -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input v-model="form.title" type="text" class="w-full border rounded p-2" required />
                        <span v-if="form.errors.title" class="text-red-500 text-sm">{{
                            form.errors.title
                        }}</span>
                    </div>
                    <div v-if="modalType !== 'editSubtask'" class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
                        <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description
                            }}</span>
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
                        <input v-model="form.due_date" type="date" class="w-full border rounded p-2" />
                        <span v-if="form.errors.due_date" class="text-red-500 text-sm">{{
                            form.errors.due_date
                        }}</span>
                    </div>
                    <!-- Subtasks Section (Only for Task Create/Edit) -->
                    <div v-if="modalType !== 'editSubtask'" class="mb-4">
                        <label class="block text-sm font-medium mb-1">Subtasks</label>
                        <div v-for="(subtask, index) in form.subtasks" :key="index" class="mb-2">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <!-- Add Subtask Number -->
                                    <label class="block text-sm font-medium mb-1">Subtask {{ index + 1 }}</label>
                                    <input v-model="subtask.title" type="text" placeholder="Subtask title"
                                        class="w-full border rounded p-2 mb-1" />
                                    <span v-if="form.errors[`subtasks.${index}.title`]" class="text-red-500 text-sm">
                                        {{ form.errors[`subtasks.${index}.title`] }}
                                    </span>
                                    <label class="block text-sm font-medium mb-1">Status</label>
                                    <select v-model="subtask.status" class="w-full border rounded p-2 mb-1">
                                        <option value="not_done">Not done</option>
                                        <option value="done">Done</option>
                                    </select>
                                    <span v-if="form.errors[`subtasks.${index}.status`]" class="text-red-500 text-sm">
                                        {{ form.errors[`subtasks.${index}.status`] }}
                                    </span>
                                    <label class="block text-sm font-medium mb-1">Due Date</label>
                                    <input v-model="subtask.due_date" type="date"
                                        class="w-full border rounded p-2 mb-1" />
                                    <span v-if="form.errors[`subtasks.${index}.due_date`]" class="text-red-500 text-sm">
                                        {{ form.errors[`subtasks.${index}.due_date`] }}
                                    </span>
                                </div>
                                <button type="button" @click="removeSubtask(index)"
                                    class="ml-2 text-red-500 hover:text-red-700">
                                    Remove
                                </button>
                            </div>
                        </div>
                        <button type="button" @click="addSubtask" class="text-blue-500 hover:text-blue-700 text-sm">
                            + Add Subtask
                        </button>
                    </div>
                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button type="button" @click="showModal = false"
                            class="mr-2 px-4 py-2 text-gray-600 hover:text-gray-800">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            {{ modalType === 'create' ? 'Save' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

export default {
    props: {
        tasks: {
            type: Array,
            default: () => [],
            validator: (value) => {
                return Array.isArray(value) && value.every(task =>
                    task && typeof task === 'object' && 'id' in task
                );
            },
        },
        flash: {
            type: Object,
            default: () => ({
                success: null,
                error: null,
            }),
        },
    },
    setup(props) {
        const showModal = ref(false);
        const modalType = ref('create');
        const expandedTaskId = ref(null);
        const currentTaskId = ref(null);
        const currentSubtaskIndex = ref(null);

        // متغیرهای مرتب‌سازی
        const sortKey = ref(''); // ستون فعلی برای مرتب‌سازی ('status' یا 'due_date')
        const sortOrder = ref('asc'); // جهت مرتب‌سازی ('asc' یا 'desc')

        const form = useForm({
            id: null,
            title: '',
            description: '',
            status: 'todo',
            due_date: null,
            subtasks: [],
        });

        // Computed property برای مرتب‌سازی تسک‌ها
        const sortedTasks = computed(() => {
            if (!sortKey.value) {
                return props.tasks; // اگر مرتب‌سازی انتخاب نشده، داده‌ها بدون تغییر برگردانده می‌شن
            }

            const tasksCopy = [...props.tasks]; // کپی از داده‌ها برای جلوگیری از تغییر داده‌های اصلی

            return tasksCopy.sort((a, b) => {
                let valueA = a[sortKey.value];
                let valueB = b[sortKey.value];

                // مدیریت مقادیر null برای due_date
                if (sortKey.value === 'due_date') {
                    valueA = valueA ? new Date(valueA) : new Date('9999-12-31'); // تسک‌های بدون تاریخ در آخر
                    valueB = valueB ? new Date(valueB) : new Date('9999-12-31');
                }

                // مدیریت مقادیر null برای status
                if (sortKey.value === 'status') {
                    valueA = valueA || '';
                    valueB = valueB || '';
                }

                // مقایسه مقادیر
                if (valueA < valueB) {
                    return sortOrder.value === 'asc' ? -1 : 1;
                }
                if (valueA > valueB) {
                    return sortOrder.value === 'asc' ? 1 : -1;
                }
                return 0;
            });
        });

        // متد برای تغییر ستون و جهت مرتب‌سازی
        function sortBy(key) {
            if (sortKey.value === key) {
                // اگر روی همان ستون کلیک شده، جهت مرتب‌سازی رو تغییر بده
                sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
            } else {
                // اگر ستون جدیدی انتخاب شده، مرتب‌سازی رو از صعودی شروع کن
                sortKey.value = key;
                sortOrder.value = 'asc';
            }
        }

        function openCreateModal() {
            modalType.value = 'create';
            form.reset();
            form.id = null;
            form.subtasks = [];
            showModal.value = true;
        }

        function editTask(task) {
            modalType.value = 'edit';
            currentTaskId.value = task.id;
            form.id = task.id;
            form.title = task.title || '';
            form.description = task.description || '';
            form.status = task.status || 'todo';
            form.due_date = task.due_date || null;

            console.log('Task being edited:', task);
            console.log('Subtasks received:', task.subtasks);

            form.subtasks = task.subtasks && Array.isArray(task.subtasks)
                ? task.subtasks.map(subtask => {
                    const mappedSubtask = {
                        id: subtask.id,
                        title: subtask.title || '',
                        status: subtask.status || 'not_done',
                        due_date: subtask.due_date || null,
                    };
                    console.log('Mapping subtask:', subtask, 'to', mappedSubtask);
                    return mappedSubtask;
                })
                : [];
            showModal.value = true;
        }

        function editSubtask(task, subtaskIndex) {
            modalType.value = 'editSubtask';
            currentTaskId.value = task.id;
            currentSubtaskIndex.value = subtaskIndex;
            const subtask = task.subtasks[subtaskIndex];

            form.reset();
            form.id = subtask.id;
            form.title = subtask.title || '';
            form.status = subtask.status || 'not_done';
            form.due_date = subtask.due_date || null;
            form.subtasks = [];
            showModal.value = true;
        }

        function addSubtask() {
            form.subtasks.push({
                title: '',
                status: 'not_done',
                due_date: null,
            });
        }

        function removeSubtask(index) {
            form.subtasks.splice(index, 1);
        }

        function toggleSubtasks(taskId) {
            if (typeof taskId === 'undefined') {
                console.error('toggleSubtasks called with undefined taskId');
                return;
            }
            expandedTaskId.value = expandedTaskId.value === taskId ? null : taskId;
        }

        function deleteSubtask(taskId, subtaskId) {
            if (confirm('Are you sure you want to delete this subtask?')) {
                useForm({}).delete(route('subtasks.destroy', subtaskId) || `/subtasks/${subtaskId}`, {
                    onSuccess: () => {
                        console.log('Subtask deleted successfully');
                    },
                    onError: (errors) => {
                        console.error('Delete subtask errors:', errors);
                    },
                });
            }
        }

        function submitForm() {
            if (modalType.value === 'editSubtask') {
                form.put(route('subtasks.update', form.id) || `/subtasks/${form.id}`, {
                    onSuccess: () => {
                        form.reset();
                        showModal.value = false;
                        currentTaskId.value = null;
                        currentSubtaskIndex.value = null;
                    },
                    onError: (errors) => {
                        console.error('Update subtask errors:', errors);
                    },
                });
            } else if (modalType.value === 'edit') {
                form.put(route('tasks.update', form.id) || `/Task/${form.id}`, {
                    onSuccess: () => {
                        form.reset();
                        showModal.value = false;
                    },
                    onError: (errors) => {
                        console.error('Update errors:', errors);
                    },
                });
            } else {
                form.post(route('tasks.store') || '/Task', {
                    onSuccess: () => {
                        form.reset();
                        showModal.value = false;
                    },
                    onError: (errors) => {
                        console.error('Create errors:', errors);
                    },
                });
            }
        }

        function deleteTask(task) {
            if (confirm('Are you sure you want to delete this task?')) {
                useForm({}).delete(route('tasks.destroy', task.id) || `/Task/${task.id}`, {
                    onSuccess: () => {
                        console.log('Task deleted successfully');
                        expandedTaskId.value = null;
                    },
                    onError: (errors) => {
                        console.error('Delete errors:', errors);
                    },
                });
            }
        }

        return { form, submitForm, deleteTask, editTask, editSubtask, deleteSubtask, openCreateModal, showModal, modalType, addSubtask, removeSubtask, toggleSubtasks, expandedTaskId, sortedTasks, sortBy, sortKey, sortOrder };
    },
};
</script>
