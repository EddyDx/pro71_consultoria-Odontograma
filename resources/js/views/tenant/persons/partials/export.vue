<template>
    <el-dialog :title="title" :visible="showDialog" @close="close" class="dialog-import">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Periodo</label>
                        <el-select v-model="form.period" @change="changePeriod">
                            <el-option key="all" value="all" label="Todos"></el-option>
                            <el-option key="month" value="month" label="Por mes"></el-option>
                            <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                            <el-option key="seller" value="seller" label="Vendedor"></el-option>
                        </el-select>
                    </div>
                    <template v-if="form.period === 'month' || form.period === 'between_months'">
                        <div class="col-md-4">
                            <label class="control-label">Mes de</label>
                            <el-date-picker v-model="form.month_start" type="month"
                                            @change="changeDisabledMonths"
                                            value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'between_months'">
                        <div class="col-md-4">
                            <label class="control-label">Mes al</label>
                            <el-date-picker v-model="form.month_end" type="month"
                                            :picker-options="pickerOptionsMonths"
                                            value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                        </div>
                    </template>
                    <template v-if="form.period === 'seller' && sellers && sellers.length > 0">

                        <div class="col-lg-4 col-md-4">

                            <div :class="{'has-danger': errors.seller_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Vendedor
                                </label>
                                <el-select v-model="form.seller_id"
                                           clearable>
                                    <el-option v-for="option in sellers"
                                               :key="option.id"
                                               :label="option.name"
                                               :value="option.id">{{ option.name }}
                                    </el-option>
                                </el-select>
                            </div>
                        </div>

                    </template>

                </div>
                <div class="form-actions text-right mt-4">
                    <el-button class="second-buton" @click.prevent="close()">Cancelar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    import queryString from 'query-string'

    export default {
        props: [
            'showDialog',
            'type',
        ],
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                resource: 'persons',
                errors: {},
                sellers: [],
                form: {},
                title:'',
                pickerOptionsMonths: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM')
                        return this.form.month_start > time
                    }
                },
            }
        },
        created() {
            this.title = (this.type == 'customers') ? 'Exportar Clientes':'Exportar Proveedores'
            this.initForm()
        },
        mounted() {
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.sellers = response.data.sellers

                    /*
                    this.api_service_token = response.data.api_service_token
                    this.countries = response.data.countries
                    this.zones = response.data.zones
                    this.all_departments = response.data.departments;
                    this.all_provinces = response.data.provinces;
                    this.all_districts = response.data.districts;
                    this.identity_document_types = response.data.identity_document_types;
                    this.locations = response.data.locations;
                    this.person_types = response.data.person_types;
                    */
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    period: 'month',
                    month_start: moment().format('YYYY-MM'),
                    month_end: moment().format('YYYY-MM'),
                }
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            changeDisabledMonths() {
                if (this.form.month_end < this.form.month_start) {
                    this.form.month_end = this.form.month_start
                }
            },
            changePeriod() {

                if(this.form.period === 'between_months') {
                    this.form.month_start = moment().startOf('year').format('YYYY-MM'); //'2019-01';
                    this.form.month_end = moment().endOf('year').format('YYYY-MM');;
                }

            },
            submit() {
                this.loading_submit = true

                let query = queryString.stringify({
                    ...this.form
                });
                window.open(`/${this.resource}/${this.type}/exportation/?${query}`, '_blank');

                this.loading_submit = false
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>
