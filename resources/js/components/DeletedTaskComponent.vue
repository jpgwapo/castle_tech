<template>
    <div>
        <FlashMessage></FlashMessage>
        <h1>Deleted Task</h1>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <th>Task Name</th>
                <th>Task Description</th>
                <th>Status</th>
                <th>Deleted at</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(item, index) in postingList" :key="item.id">
                    <td>{{ item.task_name }}</td>
                    <td>{{ item.task_description }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ format_date(item.deleted_at) }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" @click="restore(item,index)">Restore</button>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</template>

<script>
import * as moment from "moment";

export default {
    props: ['data'],
    data() {
        return {
            postingList: this.data,
            form: {
                task_name: null,
                task_description: null,
                status: 'Pending'
            },
            formEdit: {
                task_name: null,
                task_description: null,
                status: null
            },
            selectedId: null,
            selectCategories: {Complete: 'Complete', Pending: 'Pending', Cancelled: 'Cancelled', Custom: 'Custom'},
        }
    },
    methods: {
        restore(item,index) {
            const vm = this;
            axios.patch(`/tasks/restore/${item.id}`)
            .then(function (response) {
                vm.postingList.splice(index,1)
                vm.flashMessage.success({title: 'Success', message: 'Task restored!'});
            })
            .catch(function (error) {
                console.log(error)
                vm.flashMessage.error({title: 'Error', message: 'Not able to restore.'});
            });
        },
        format_date(value){
            if (value) {
                return moment(String(value)).format('YYYY-MM-DD')
            }
        },
    }
}
</script>
