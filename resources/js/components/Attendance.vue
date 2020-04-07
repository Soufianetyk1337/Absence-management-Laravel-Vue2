<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills ml-auto p-2">
                            <li>
                                <a v-on:click="reset()"><button class="btn btn-primary">Add Absence <i class="fas fa-user-check"></i> </button></a>
                            </li>

                         
                            <li  v-if="isActive" class="nav-item">
                                <a class="nav-link" v-on:click="formToggle()"><i class="fas fa-lock-open"></i></a>
                            </li>
                            <li v-else class="nav-item">
                                <a class="nav-link" v-on:click="formToggle()"><i class="fas fa-lock"></i></a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <form @submit.prevent="editMode? updateAbsence() : createAbsence()" id='form'>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Student Id" v-model="form.student_id" :class="{'is-invalid': form.errors.has('student_id')}" value="form.student_id">
                                        </div>                                     
                                       <div class="form-group">
                                            <select name="modules" class="form-control" :class="{'is-invalid': form.errors.has('module_id')}" v-model="form.module_id" >
                                            <option selected="form.module_id" v-for="module in modules.data" 
                                            :value="module.id">{{module.name}}</option>
                                            </select>   
                                        </div>


                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="justification" v-model="form.justification" :class="{'is-invalid': form.errors.has('justification')}" value="form.justification">
                                        </div>
                                        <div class="form-group">
                                            <input type="date" class="form-control" placeholder="Date" v-model="form.date" :class="{'is-invalid': form.errors.has('date')}" value="form.date">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="hours" v-model="form.hours" :class="{'is-invalid': form.errors.has('hours')}" value="form.hours">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Type" v-model="form.type" :class="{'is-invalid': form.errors.has('type')}" value="form.type">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button v-show="!editMode" type="submit" class="btn btn-primary btn-md">Add attendance</button>
                                        <button type="submit" v-show="editMode" class="btn btn-warning btn-md">Edit attendance</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Search form -->
                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    <form>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="First name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="First name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="First name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="First name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="First name">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Last name">
                                            </div>
                                            <div class="col">
                                                <button type="submit" class="btn btn-outline-primary btn-md">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- ./card -->
            </div>
            <!-- /.col -->
        </div>
        <!--Row-->
        <div class="row">
            <div class="card-body table-responsive p-0 mt-5">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full name</th>
                            <th>Module name</th>
                            <th>Justification</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="absence in absences.data" :key="absence.id">
                            <td>{{absence.id}}</td>
                            <td>{{absence.first_name}} {{absence.last_name}}</td>
                            <td>{{absence.name}}</td>
                            <td>{{absence.justification}}</td>
                            <td>{{absence.type}}</td>
                            <td>{{absence.date}}</td>
                            <td>{{absence.hours}}</td>
                            <td>
                                <button class="btn btn-info btn-warning btn-md" @click="editAbsence(absence)"><i class="fas fa-edit fa-fw"></i></button>
                                <button class="btn btn-info btn-danger btn-md" @click="deleteAbsence(absence.id)"><i class="fas fa-trash "></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class=" container d-flex justify-content-around">
                <pagination :data="absences" @pagination-change-page="getResults">
                </pagination>

    <a class type="button" href="/api/export" download="file.xlsx"><button class="btn-success">Export<i class="fas fa-download"></i></button></a>
                        
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isActive: false,
            editMode: false,
            absences: {},
            modules: {},
            form: new Form({
                id: '',
                justification: '',
                hours: '',
                type: '',
                date: '',
                module_id: '',
                student_id: '',
            })
        }
    },
    methods: {
        getModules(){
            axios.get('api/module')
            .then(response => {
                this.modules = response.data;
            })
        },
        getResults(page = 1) {
            axios.get('api/attendance?page=' + page)
                .then(response => {
                    this.absences = response.data
                });
        },
        updateAbsence() {
            this.$Progress.start();
            this.form.put('api/attendance/' + this.form.id)
                .then(() => {
                    Swal.fire(
                        'Update!',
                        'Absence information has been Updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('absence Created');
                    this.reset();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

        },
        reset() {
            this.form.reset();
            this.editMode = false;
        },
        deleteAbsence(id) {

            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    this.form.delete('api/attendance/' + id)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('absenceCreated');
                        })
                        .catch(() => {
                            Swal.fire("Failed", "Something went wrong.", "warning");
                        });
                })
        },
        editAbsence(absence) {
            this.isActive = true;
            this.formToggle();
            this.editMode = true;
            this.form.reset();
            this.form.fill(absence);
        },
        formToggle() {
            if (this.isActive) {
                document.getElementById('form').style.display = 'block';
                this.isActive = !this.isActive;
            } else {
                document.getElementById('form').style.display = 'none';
                this.isActive = !this.isActive;
            }
        },
        createAbsence() {
            this.$Progress.start();
            this.form.post('api/attendance')
                .then(() => {
                    Fire.$emit('absenceCreated');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Absence added successfully'
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                })
        },
        getAbsences() {
            axios.get("api/attendance")
                .then(({ data }) => (this.absences = data))

        }
    },
    created() {
        this.getAbsences();
        this.getModules();
        Fire.$on('absenceCreated', () => this.getAbsences());
    }
}

</script>
