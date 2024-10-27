<template>
   <div>
        <b-form @submit.prevent="searchProjects">
            <b-row class="d-flex mb-2">
                <b-col sm="3">
                    <b-form-input id="role" v-model="search" :state="errorSearch" placeholder="Find projects with specific role"></b-form-input>
                    <b-form-invalid-feedback>Role cannot be empty</b-form-invalid-feedback>
                </b-col>
                <b-button type="submit" variant="primary" style="max-height: 2.5rem;">Search</b-button>
            </b-row>
        </b-form>

        <div v-if="loading" class="d-flex justify-content-center my-3">
            <b-spinner variant="primary"></b-spinner>
        </div>

        <b-table v-else :items="paginatedProjects" :fields="projectfields" striped responsive="sm">
            <template #cell(employees)="data">
                <b-button @click="showEmployees(data.item)" variant="primary"><b-icon icon="eye"></b-icon></b-button>
            </template>
        </b-table>

        <div v-if="projects.length && !loading" class="d-flex justify-content-center my-3">
            <b-pagination
                v-model="currentPage"
                :total-rows="projects.length"
                :per-page="perPage"
                aria-controls="employee-table"
            ></b-pagination>
        </div>

        <b-modal id="project-modal" ref="employeeModal" title="Employees project" hide-header-close ok-only size="lg">
            <ul v-if="selectedProjectEmployees.length">
                <b-table :items="selectedProjectEmployees" :fields="employeeFields" striped responsive="sm" small>
                    <template #cell(role)="data">
                        <a href="#" @click.prevent="editRole(data.item)">{{ data.item.role }}</a>
                    </template>
                    <template #cell(delete)="data">
                        <b-button @click="deleteEmployee(data.item)" variant="danger" size="sm">
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                    </template>
                </b-table>
            </ul>
            <p v-else>No employees assigned to this project.</p>
        </b-modal>

        <b-modal id="role-edit-modal" ref="roleEditModal" title="Edit Role" hide-footer>
            <b-form @submit.prevent="saveRole">
                <b-row>
                    <b-col sm="2" class="mt-1">
                        <label for="role">Role:</label>
                    </b-col>
                    <b-col sm="5">
                        <b-form-input id="role" v-model="role"  :state="role.trim().length > 0 ? null : false"></b-form-input>
                        <b-form-invalid-feedback>Role cannot be empty</b-form-invalid-feedback>
                    </b-col>
                    <b-col sm="5">
                        <b-button type="submit" variant="primary">Save</b-button>
                    </b-col>
                </b-row>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProjectsList',
    data() {
        return {
            projectfields: ['name', 'domain', 'employees'],
            employeeFields: ['name', 'email', 'phone', 'role', 'delete'],
            projects: [],
            selectedProjectEmployees: [],
            currentPage: 1,
            perPage: 5,
            loading: false,
            employeesToRemove: null,
            role: '',
            selectedEmployee: null,
            search: '',
            errorSearch: null,
            changeRole: null
        }
    },

    created() {
        this.getProjects();
    },

    computed: {
        paginatedProjects() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.projects.slice(start, start + this.perPage);
        },
    },

    watch: {
        employeesToRemove(newVal) {
            this.selectedProjectEmployees = this.selectedProjectEmployees.filter(
                (employee) => !newVal.includes(employee.id)
            );
        },

        changeRole(newVal) {
            const index = this.selectedProjectEmployees.findIndex(emp => emp.id === this.selectedEmployee.id);
            const updatedEmployees = [...this.selectedProjectEmployees];
            updatedEmployees[index] = { ...updatedEmployees[index], role: newVal };
            this.selectedProjectEmployees = updatedEmployees;
        }
    },

    methods: {
        getProjects() {
            this.loading = true;
            axios.get('/projects-with-employees').then((response) => {
                this.projects = response.data.projects;
            }).catch(() => {
                this.$toastr.s("An error occurred while fetching projects.", "Error", {
                    position: "toast-top-right"
                });
            }).finally(() => {
                this.loading = false;
            });
        },

        showEmployees(project) {
            this.selectedProjectEmployees = project.employees || [];
            this.selectedProjectEmployees.forEach((employee) => {
                employee.role = employee.assignment.role;
            });
            this.$refs.employeeModal.show();
        },

        deleteEmployee(employee) {
            axios.delete(`/project/${employee.assignment.project_id}/employee/${employee.id}`).then((response) => {

                this.employeesToRemove = response.data.employee_id;

                this.$toastr.s("The employee was deleted from the project.", "Success", {
                    position: "toast-top-right"
                });
            }).catch(() => {
                this.$toastr.s("An error occurred while deleteting the employee.", "Error", {
                    position: "toast-top-right"
                });
            });
        },

        editRole(employee) {
            this.selectedEmployee = employee;
            this.role = employee.role;
            this.$refs.roleEditModal.show();
        },

        closeRoleModal() {
            this.$refs.roleEditModal.hide();
        },

        saveRole() {
            axios.put(`/project/${this.selectedEmployee.assignment.project_id}/employee/${this.selectedEmployee.id}/role/update`,{
                    role: this.role
                }).then((response) => {

                    this.changeRole = response.data.role;

                    this.$refs.roleEditModal.hide();

                    this.$toastr.s("The employee was deleted from the project.", "Success", {
                        position: "toast-top-right"
                    });
                }).catch(() => {
                    this.$toastr.s("An error occurred while deleteting the employee.", "Error", {
                        position: "toast-top-right"
                });
            });
        },

        searchProjects() {
            if (!this.search) {
                this.errorSearch = false;
                return;
            }

            this.loading = true;
            axios.post('/project/search', {search: this.search}).then((response) => {
                this.projects = response.data.projects;
                this.currentPage = 1;
                
            }).catch(() => {
                this.$toastr.s("An error occurred while searching for the projects.", "Error", {
                    position: "toast-top-right"
                });
            }).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>