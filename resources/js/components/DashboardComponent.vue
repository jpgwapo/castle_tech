<template>
    <div>
        <main id="app">
            <FlashMessage></FlashMessage>
            <h1>Task Lists</h1>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th :class="sortedClass('id')" @click="sortBy('id')">ID</th>
                        <th :class="sortedClass('user')" @click="sortBy('user')">User Assign</th>
                        <th :class="sortedClass('task_name')" @click="sortBy('task_name')">Task Name</th>
                        <th :class="sortedClass('task_description')" @click="sortBy('task_description')">Task Description</th>
                        <th :class="sortedClass('status')" @click="sortBy('status')">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in sortedItems" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td v-if="item.user">{{item.user.name}}</td>
                        <td v-else>unassign</td>
                        <td>{{ item.task_name }}</td>
                        <td>{{ item.task_description }}</td>
                        <td>{{ item.status }}</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" @click="edit(item)"><i class="fa fa-edit"></i>Edit</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>

            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Edit task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Task Name</label>
                                <input type="text" disabled class="form-control" placeholder="Input Task Name" v-model="formEdit.task_name">
                            </div>
                            <div class="form-group">
                                <label for="">Task Description</label>
                                <textarea disabled class="form-control" placeholder="Input Task Description" rows="4" cols="50" v-model="formEdit.task_description">
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="categories" v-model="formEdit.status" class="form-control">
                                    <option v-for="(category, key) in selectCategories" 
                                        :key="key" 
                                        v-bind:value="key">{{category}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="save()">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import FlashMessage from '@smartweb/vue-flash-message';
import SortedTablePlugin from "vue-sorted-table";

Vue.use(FlashMessage);
Vue.use(SortedTablePlugin);

export default {
    props: ['data', 'users'],
    data() {
        return {
            sort: {
                key: '',
                isAsc: false
            },
            postingList: this.data,
            userList: this.users,
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
    computed: {
        sortedItems () {
        const list = this.postingList.slice();
        if (!!this.sort.key) {
            list.sort((a, b) => {
                a = a[this.sort.key]
                b = b[this.sort.key]
                return (a === b ? 0 : a > b ? 1 : -1) * (this.sort.isAsc ? 1 : -1)
            });
        }
        console.log(list)
        return list;
        },
    },
    methods: {
        edit(item) {
            console.log(item)
            this.formEdit.user_id = item.user_id;
            this.formEdit.task_name = item.task_name;
            this.formEdit.task_description = item.task_description;
            this.formEdit.status = item.status;
            this.selectedId = item.id
            $('#editModal').modal('show');
        },
        save() {
            const vm = this;
            axios.put(`/tasks/${vm.selectedId}`, this.formEdit)
            .then(function (response) {
                vm.flashMessage.success({title: 'Success', message: 'Task save!'});
                console.log('Task has been sucessfully saved')
                console.log(response)
                $('#editModal').modal('hide');
                setTimeout(function(){
                    window.location.reload(1);
                }, 1500);
            })
            .catch(function (error) {
                console.log(error)
            });
        },
        sortedClass (key) {
            return this.sort.key === key ? `sorted ${this.sort.isAsc ? 'asc' : 'desc' }` : '';
        },
        sortBy(key) {
            this.sort.isAsc = this.sort.key === key ? !this.sort.isAsc : false;
            this.sort.key = key;
        },
    }
}
</script>

<style scoped>
table {
    th.sorted {
        &.asc::after {
        display: inline-block;
        content: '▼';
        }
        &.desc::after {
        display: inline-block;
        content: '▲';
        }
    }
}
</style>