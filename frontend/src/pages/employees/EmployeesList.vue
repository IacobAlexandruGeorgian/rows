<template>
    <div>
        <div class="d-flex justify-content-start mb-2">
            <b-button variant="success" @click="goToCreateEmployee">
                <b-icon icon="plus"></b-icon>Employee
            </b-button>
        </div>

        <div v-if="loading" class="d-flex justify-content-center my-3">
            <b-spinner variant="primary"></b-spinner>
        </div>

        <b-table v-else :items="paginatedEmployees" :fields="employeefields" striped responsive="sm">
            <template #cell(projects)="data">
                <b-button @click="showProjects(data.item)" variant="primary"><b-icon icon="eye"></b-icon></b-button>
            </template>
        </b-table>

        <div v-if="employees.length && !loading" class="d-flex justify-content-center my-3">
            <b-pagination
                v-model="currentPage"
                :total-rows="employees.length"
                :per-page="perPage"
                aria-controls="employee-table"
            ></b-pagination>
        </div>

        <b-modal id="project-modal" ref="projectModal" title="Employee projects" hide-header-close ok-only>
            <ul v-if="selectedEmployeeProjects.length">
                <b-table :items="selectedEmployeeProjects" :fields="projectFields" striped responsive="sm" small>

                </b-table>
            </ul>
            <p v-else>No projects assigned to this employee.</p>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'EmployeesList',
    data() {
        return {
            employeefields: ['name', 'email', 'phone', 'projects'],
            projectFields: ['name', 'domain', 'role'],
            employees: [],
            selectedEmployeeProjects: [],
            currentPage: 1,
            perPage: 5,
            loading: false
        }
    },

    created() {
        this.getEmployees();

        if (this.$route.query.message) {
            this.$toastr.s(this.$route.query.message, "Success", {
                position: "toast-top-right"
            });
        }
    },

    computed: {
        paginatedEmployees() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.employees.slice(start, start + this.perPage);
        },
    },

    methods: {
        getEmployees() {
            this.loading = true;
            axios.get('/employees').then((response) => {
                this.employees = response.data.employees;
            }).catch(() => {
                this.$toastr.s("An error occurred while fetching employees.", "Error", {
                    position: "toast-top-right"
                });
            }).finally(() => {
                this.loading = false;
            });
        },

        showProjects(employee) {
            this.selectedEmployeeProjects = employee.projects || [];
            this.selectedEmployeeProjects.forEach((project) => {
                project.role = project.assignment.role;
            });
            this.$refs.projectModal.show();
        },

        goToCreateEmployee() {
            this.$router.push('/employees/create');
        }
    }
}
</script>
<style scoped>
</style>