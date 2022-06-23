<template>
    <a-select class="company-select" @change="handleChange" v-model="selectedCompanyId">
        <a-select-option v-for="company in companies" :key="company.id" :value="company.id">{{ company.title }}</a-select-option>
    </a-select>
</template>

<script>
import { message, Select } from "ant-design-vue";

export default {
    components: {
        "a-select": Select,
        "a-select-option": Select.Option,
    },
    props: ["props"],
    mounted() {
        this.initialize();
    },
    data: () => {
        return {
            selectEl: null,
            companies: [],
            selectedCompanyId: null,
            styles: {
                select: {
                    background: 'red',
                },
            }
        };
    },
    methods: {
        initialize() {
            this.companies = window.App.companies;
            this.selectedCompanyId = this.companies.find(c => c.active).id;
        },
        async handleChange(value) {
            console.log('Active company changed', value);
            try {
                const response = await axios.post("/api/v1/admin/switch-user-group", {
                    userGroupId: value,
                });

                if (response.data.success) {
                    message.loading('Switching company...');
                    window.location.reload();
                }
            } catch (error) {
                message.error(error.message);
            }
        },
    },
};
</script>
