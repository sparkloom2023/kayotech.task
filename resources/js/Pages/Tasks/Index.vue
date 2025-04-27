<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold">Tasks Page</h1>
        <p>Tasks: {{ tasks.length }}</p>
    </div>
</template>

<script>
export default {
    props: {
        tasks: Array,
    },
};
</script>
