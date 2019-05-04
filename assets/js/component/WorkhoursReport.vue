<template>
    <div class="container">
        <div class="py-4 text-center">
            <h2>Employees workhours report</h2>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <form @submit="onSubmit">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" v-model="firstName" class="form-control" id="firstName">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" v-model="lastName" class="form-control" id="lastName">
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label>Dates range</label><br/>
                            <date-range-picker @update="onDateRangeUpdated" :start-date="startDate"
                                               :end-date="endDate"></date-range-picker>
                        </div>
                        <button class="btn col-md-3 btn-primary btn-lg btn-block" type="submit"
                                style="margin-left: 15px;" :disabled="loadingInp">Build report
                            <spinner v-if="loadingInp"></spinner>
                        </button>
                    </div>
                    <div class="row">
                        <div class="alert alert-danger col-12 my-3" role="alert" v-if="showError">
                            {{ errorMessage }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br/>
        <md-tabs>
            <md-tab id="employee-report" md-label="Employee report">
                <div>
                    <md-table v-model="employees" md-card md-fixed-header>
                        <md-table-toolbar>
                            <h1 class="md-title">Employees</h1>
                        </md-table-toolbar>

                        <md-table-row slot="md-table-row" slot-scope="{ item }">
                            <md-table-cell md-label="Employee" md-sort-by="employee">{{ item.employee }}</md-table-cell>
                            <md-table-cell md-label="Month" md-sort-by="month">{{ formatMonth(item.month) }}
                            </md-table-cell>
                            <md-table-cell md-label="Hours" md-sort-by="hours" md-numeric>
                                {{ parseFloat(item.hours).toFixed(2) }}
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                </div>
            </md-tab>
            <md-tab id="company-report" md-label="Company report">
                <div>
                    <md-table v-model="companies" md-sort="hours" md-sort-order="desc" md-card md-fixed-header>
                        <md-table-toolbar>
                            <h1 class="md-title">Companies</h1>
                        </md-table-toolbar>

                        <md-table-row slot="md-table-row" slot-scope="{ item }">
                            <md-table-cell md-label="Company name" md-sort-by="companyName">{{ item.companyName }}
                            </md-table-cell>
                            <md-table-cell md-label="Hours" md-sort-by="hours" md-numeric>
                                {{ parseFloat(item.hours).toFixed(2) }}
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                </div>
            </md-tab>
        </md-tabs>
    </div>
</template>

<script>
    import DateRangePicker from 'vue2-daterange-picker';
    import 'vue2-daterange-picker/dist/lib/vue-daterange-picker.min.css'
    import ProgressSpinnerIndeterminate from './ProgressSpinnerIndeterminate'
    import axios from 'axios';

    export default {
        name: 'workhours-report',
        components: {
            'date-range-picker': DateRangePicker,
            'spinner': ProgressSpinnerIndeterminate,
        },
        data() {
            return {
                startDate: this.calendarStartDate,
                endDate: this.calendarEndDate,
                firstName: this.searchFirstName,
                lastName: this.searchLastName,
                loadingInp: false,
                showError: false,
                errorMessage: null,
                employees: [],
                companies: []
            };
        },
        props: ['dataFetchUrl', 'calendarStartDate', 'calendarEndDate', 'searchFirstName', 'searchLastName'],
        methods: {
            async fetchData() {
                this.loadingInp = true;
                this.showError = false;
                let url = this.dataFetchUrl;
                url += '?startDate=' + parseInt(this.startDate.getTime() / 1000);
                url += '&endDate=' + parseInt(this.endDate.getTime() / 1000);
                url += '&firstName=' + this.firstName;
                url += '&lastName=' + this.lastName;

                try {
                    const response = await axios.get(url);

                    let companies = response.data.data.companyReport;
                    companies = companies.sort(function (a, b) {
                            if (a.hours < b.hours) {
                                return -1;
                            }
                            if (a.hours > b.hours) {
                                return 1;
                            }

                            return 0;
                        }
                    );

                    this.employees = response.data.data.employeeReport;
                    this.companies = companies;
                } catch (error) {
                    this.employees = [];
                    this.companies = [];
                    this.showError = true;
                    this.errorMessage = error;
                }

                this.loadingInp = false;
            },
            onSubmit(event) {
                event.preventDefault();
                this.fetchData();
            },
            onDateRangeUpdated(event) {
                this.startDate = event.startDate;
                this.endDate = event.endDate;
            },
            formatMonth(month) {
                let date = new Date(month * 1000);

                return date.getFullYear() + '-' + (date.getMonth() + 1);
            }
        }
    }
</script>