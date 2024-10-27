<template>
    <div class="justify-content-center">
        <b-card>
            <b-form @submit.prevent="submit">
                <b-row class="my-1">
                    <b-col sm="1">
                        <label for="name">Name:</label>
                    </b-col>
                    <b-col sm="5">
                        <b-form-input id="name" v-model="employee.name" :state="errors.name.state" size="sm" placeholder="Enter name"></b-form-input>
                        <b-form-invalid-feedback v-if="errors.name.message">{{ errors.name.message }}</b-form-invalid-feedback>
                    </b-col>
                    <b-col sm="1">
                        <label for="email">Email:</label>
                    </b-col>
                    <b-col sm="5">
                        <b-form-input id="email" v-model="employee.email" :state="errors.email.state" size="sm" placeholder="Enter email"></b-form-input>
                        <b-form-invalid-feedback v-if="errors.email.message">{{ errors.email.message }}</b-form-invalid-feedback>
                    </b-col>
                </b-row>
                <b-row class="my-5">
                    <b-col sm="1">
                        <label for="phone">Phone:</label>
                    </b-col>
                    <b-col sm="5">
                        <b-form-input id="phone" v-model="employee.phone" :state="errors.phone.state" size="sm" placeholder="Enter phone number"></b-form-input>
                        <b-form-invalid-feedback v-if="errors.phone.message">{{ errors.phone.message }}</b-form-invalid-feedback>
                    </b-col>
                    <b-col sm="1">
                        <label for="input-small">Projects:</label>
                    </b-col>
                    <b-col sm="1">
                        <b-button
                            :disabled="buttonDisabled"
                            @click="addProject"
                            variant="outline-primary"
                            size="sm"
                        ><b-icon icon="plus"></b-icon>
                        </b-button>
                    </b-col>
                    <b-col sm="4">
                        <div v-for="(project, index) in newProjects" :key="index" class="d-flex">
                            <b-row>
                                <b-col sm="5">
                                    <b-form-select
                                        v-model="project.id"
                                        :options="projects"
                                        size="sm"
                                        :state="errorsProjects[index].state"
                                    ></b-form-select>
                                </b-col>
                                <b-col sm="5">
                                    <b-form-input
                                        v-model="project.role"
                                        size="sm"
                                        placeholder="Enter role"
                                        :state="errorsProjects[index].state"
                                    ></b-form-input>
                                </b-col>
                                <b-col sm="2">
                                    <b-button @click="removeProject(index)" class="mb-2" variant="outline-danger" size="sm">
                                        <b-icon icon="trash"></b-icon>
                                    </b-button>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col sm="12">
                                    <b-form-invalid-feedback v-if="errorsProjects[index].message">{{ errorsProjects[index].message }}</b-form-invalid-feedback>
                                </b-col>
                            </b-row>
                        </div>
                    </b-col>
                </b-row>
                <b-card-footer class="d-flex justify-content-between mt-4">
                    <b-button @click="cancel" variant="secondary">Cancel</b-button>
                    <b-button type="submit" variant="primary" class="d-flex justify-content-end">Submit</b-button>
                </b-card-footer>
            </b-form>
        </b-card>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'EmployeeCreate',
    data() {
        return {
            employee: {
                name: '',
                email: '',
                phone: '',
                projects: []
            },
            projects: [],
            newProjects: [],
            buttonDisabled: false,
            selectedProject: null,
            role: '',
            errors: {
                name: {state: null, message: null},
                email: {state: null, message: null},
                phone: {state: null, message: null}
            },
            errorsProjects: []
        }
    },

    created() {
        this.getProjects();
    },

    methods: {
        submit() {
            this.validateForm();
            if (!this.isFormValid()) {
                this.employee.projects = this.newProjects;
                
                axios.post('/employee/store', this.employee).then(() => {
                    this.$router.push({ 
                        path: '/employees', 
                        query: { message: `The employee ${this.employee.name} was created successfully!` } 
                    });
                }).catch(() => {
                    this.$toastr.e("An error occurred while creating the employee.", "error", {
                        position: "toast-top-right"
                    });
                })
            }
        },

        cancel() {
            this.$router.push('/employees');
        },

        getProjects() {
            axios.get('/projects').then((response) => {
                this.projects = response.data.projects;
                this.projects.forEach((project) => {
                    project.value = project.id;
                    project.text = project.name;
                });
            }).catch(() => {
                this.$refs.toastr.s("An error occurred while fetching the projects.", "Error", {
                    position: "toast-top-right"
                });
            });
        },

        addProject() {
            this.newProjects.push({ id: null, role: '' });
            this.errorsProjects.push({ state: null, message: null });
        },

        removeProject(index) {
            this.newProjects.splice(index, 1);
            this.errorsProjects.splice(index, 1);
        },

        validateForm() {
            if (!this.employee.name) {
                this.errors.name = { state: false, message: "Name is required" };
            } else {
                this.errors.name = { state: null, message: null };
            }

            const emailPattern = /^.+@[a-zA-Z0-9]+\.[a-zA-Z]/;
            if (!emailPattern.test(this.employee.email)) {
                this.errors.email = { state: false, message: "Valid email is required" };
            } else {
                this.errors.email = { state: null, message: null };
            }

            const phonePattern = /^[0-9]+$/;
            if (!phonePattern.test(this.employee.phone)) {
                this.errors.phone = { state: false, message: "Valid phone number is required" };
            } else {
                this.errors.phone = { state: null, message: null };
            }

            this.errorsProjects = this.newProjects.map((project) => {
                if (!project.id) {
                    return { state: false, message: "Project selection is required" };
                } else if (!project.role) {
                    return { state: false, message: "Role is required" };
                } else {
                    return { state: null, message: null };
                }
            });
        },

        isFormValid() {
            return (
                this.errors.name.state &&
                this.errors.email.state &&
                this.errors.phone.state &&
                this.errorsProjects.every((error) => error.state)
            );
        }
    }
}
</script>