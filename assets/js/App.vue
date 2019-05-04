<template>
    <workhours-report :dataFetchUrl="dataFetchUrl"
                      :calendarStartDate="calendarStartDate"
                      :calendarEndDate="calendarEndDate"
                      :searchFirstName="searchFirstName"
                      :searchLastName="searchLastName"
    ></workhours-report>
</template>

<script>
    import WorkhoursReport from "./component/WorkhoursReport";

    export default {
        data() {
            return {
                dataFetchUrl: '/report-data',
                calendarStartDate: new Date(1514764800000),
                calendarEndDate: new Date(1519862400000),
                searchFirstName: '',
                searchLastName: ''
            }
        },
        name: "App",
        components: {
            WorkhoursReport,
        }
    };
</script>