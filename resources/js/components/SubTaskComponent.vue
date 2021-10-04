<template>
    <div>
        <main id="app">
            <FlashMessage></FlashMessage>
            <h1>Sub Task Lists</h1>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Task Name</th>
                        <th>Task Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in subList" :key="item.id"  rowspan="2">
                        <td>{{ item.id }}</td>
                        <td>{{ item.task_name }}</td>
                        <td>{{ item.task_description }}</td>
                    </tr>
                </tbody>
                <tfoot>
            </tfoot>
            </table>
        </main>
    </div>
</template>

<script>
export default {
    props: ['data'],
    data() {
        return {
            subList: this.data,
        }
    },

    methods: {
    }
}
</script>

<style scoped>

</style>
