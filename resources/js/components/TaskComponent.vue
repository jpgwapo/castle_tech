<template>
    <div>
        <main id="app">
            <FlashMessage></FlashMessage>
            <h1>Task Lists</h1>
            <download-excel
                class="excel"
                :data="tasks2"
                type="csv"
                name="tasks.xls"
                >
                Download CSV
            </download-excel>

            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th :class="sortedClass('id')" @click="sortBy('id')">ID</th>
                        <th :class="sortedClass('sub_task')" @click="sortBy('sub_task')" style="width: 84px;">Sub Task</th>
                        <th :class="sortedClass('user')" @click="sortBy('user')">User Assign</th>
                        <th :class="sortedClass('task_name')" @click="sortBy('task_name')">Task Name</th>
                        <th :class="sortedClass('task_description')" @click="sortBy('task_description')">Task Description</th>
                        <th :class="sortedClass('status')" @click="sortBy('status')">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in sortedItems" :key="item.id"  rowspan="2">
                        <td>{{ item.id }}</td>
                        <td class="numberCircle">
                            <a :href="'/sub-tasks-list/' + item.id" target="_blank">{{ item.subtask.length }}</a>
                        </td>
                        <td v-if="item.user">{{item.user.name}}</td>
                        <td v-else>unassign</td>
                        <td>{{ item.task_name }}</td>
                        <td>{{ item.task_description }}</td>
                        <td>{{ item.status }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" @click="showSubTaskModal(item)"><i class="fa fa-edit"></i>Add sub task</button>
                            <button class="btn btn-secondary btn-sm" @click="edit(item)"><i class="fa fa-edit"></i>Edit</button>
                            <button class="btn btn-danger btn-sm" @click="remove(item,index)"><i class="fa fa-trash"></i>Delete</button>
                        </td>
                    </tr>

                </tbody>
                <tfoot>
                <tr>
                    <td>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Input Task Name" v-model="form.task_name">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Input Task Description" v-model="form.task_description">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" disabled class="form-control" placeholder="Pending" v-model="form.status">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" @click="submit"><i class="fa fa-plus"></i>Submit</button>
                        </div>
                    </td>
                </tr>
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
                                <label for="">Assign task to:</label>
                                <select name="categories" v-model="formEdit.user_id" class="form-control">
                                    <option v-for="(name, key) in userList" 
                                        :key="key" 
                                        v-bind:value="name.id">{{name.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Task Name</label>
                                <input type="text" class="form-control" placeholder="Input Task Name" v-model="formEdit.task_name">
                            </div>
                            <div class="form-group">
                                <label for="">Task Description</label>
                                <textarea class="form-control" placeholder="Input Task Description" rows="4" cols="50" v-model="formEdit.task_description">
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
            
            <div class="modal fade" id="addSubTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add sub task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" v-model="formSub.task_id">
                            <div class="form-group">
                                <label for="">Sub Task Name</label>
                                <input type="text" class="form-control" placeholder="Input Sub Task Name" v-model="formSub.task_name">
                            </div>
                            <div class="form-group">
                                <label for="">Sub Task Description</label>
                                <textarea class="form-control" placeholder="Input Sub Task Description" rows="4" cols="50" v-model="formSub.task_description">
                                </textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" @click="saveSubTask()">Add</button>
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
import JsonExcel from "vue-json-excel";
import VueApexCharts from 'vue-apexcharts'

Vue.use(VueApexCharts)
Vue.use(FlashMessage);
Vue.component('apexchart', VueApexCharts)
Vue.component("downloadExcel", JsonExcel);
Vue.use(SortedTablePlugin);

export default {
    props: ['data', 'users', 'tasks'],
    data() {
        return {
            sort: {
                key: '',
                isAsc: false
            },
            postingList: this.data,
            userList: this.users,
            tasks2: this.tasks,
            
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
            formSub: {
                task_name: null,
                task_description: null,
                task_id: null
            },
            selectedId: null,
            selectCategories: {Complete: 'Complete', Pending: 'Pending', Cancelled: 'Cancelled', Custom: 'Custom Message'},
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
        submit() {
            const vm = this;
            axios.post('/tasks', this.form)
            .then(function (response) {
                vm.postingList.push(response.data.data);
                vm.form.task_name = null
                vm.form.task_description = null
                vm.form.status = 'Pending'
                location.reload()
            })
            .catch(function (error) {
                console.log(error)
            });
        },
        remove(item,index) {
            const vm = this;
            axios.delete(`/tasks/softDelete/${item.id}`)
            .then(function (response) {
                vm.flashMessage.success({title: 'Success', message: 'Task remove!'});
                vm.postingList.splice(index,1)
            })
            .catch(function (error) {
                console.log(error)
            });
        },
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
        showSubTaskModal(item) {
            console.log(item)
            this.formSub.task_id = item.id
            $('#addSubTaskModal').modal('show');
        },
        saveSubTask() {
            const vm = this;
            axios.post('/sub-tasks', this.formSub)
            .then(function (response) {
                vm.flashMessage.success({title: 'Success', message: 'Sub task added!'});
                $('#addSubTaskModal').modal('hide');
                setTimeout(function(){
                    window.location.reload();
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

.excel {
    float: right;
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 12px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 12px;
}

.excel:hover {
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}

.numberCircle {
    width: 2em;
    height: 2em;
    box-sizing: initial;
    background: #fff;
    border: 0.1em solid #666;
    color: #666;
    text-align: center;
    border-radius: 50%;    
    line-height: 2em;
    box-sizing: content-box;   
}
</style>
