<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>RENTAR HABITACIÓN</span></li>
            </ol>
        </div>
        <div class="card mb-0 tab-content-default row-new">
            <!-- <div class="card-header bg-info">
                <h3 class="my-0">RENTAR HABITACIÓN</h3>
            </div> -->
            <div class="card-body">
                <div class="card">
                    <div class="card-header">Datos de la habitación</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 form-group">
                                <div class="row">
                                    <div class="col-4">Nombre</div>
                                    <div class="col-8">
                                        <strong>{{ room.name }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <div class="row">
                                    <div class="col-4">Detalles</div>
                                    <div class="col-8">
                                        <strong>{{ room.description }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <div class="row">
                                    <div class="col-4">Categoría</div>
                                    <div class="col-8">
                                        <strong>{{ room.category.description }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <div class="row">
                                    <div class="col-4">Estado</div>
                                    <div class="col-8">
                    <span
                        class="badge badge-pill"
                        :class="onGetStatus(room.status)"
                    >{{ room.status }}</span
                    >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Datos del cliente</div>
                    <div class="card-body">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.customer_id }"
                        >
                            <label class="control-label font-weight-bold text-info">
                                Cliente
                                <a href="#" @click.prevent="showDialogNewPerson = true"
                                >[+ Nuevo]</a
                                >
                            </label>
                            <el-select
                                v-model="form.customer_id"
                                filterable
                                remote
                                class="border-left rounded-left border-info"
                                popper-class="el-select-customers"
                                placeholder="Escriba el nombre o número de documento del cliente"
                                :remote-method="searchRemoteCustomers"
                                :loading="loading"
                                @change="changeCustomer"
                            >
                                <el-option
                                    v-for="option in customers"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.description"
                                ></el-option>
                            </el-select>
                            <el-checkbox v-model="search_item_by_barcode"
                                         :disabled="recordItem != null">Buscar por
                                código de
                                barras
                            </el-checkbox>
                            <small
                                class="form-control-feedback"
                                v-if="errors.customer_id"
                                v-text="errors.customer_id[0]"
                            ></small>
                        </div>
                        <div class="row">
                            <div
                                class="form-group col-12 col-md-6"
                                :class="{ 'has-danger': errors['customer.name'] }"
                            >
                                <label class="control-label">Nombres</label>
                                <el-input v-model="form.customer.name"></el-input>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors['customer.name']"
                                    v-text="errors['customer.name'][0]"
                                ></small>
                            </div>
                            <div
                                class="form-group col-12 col-md-6"
                                :class="{ 'has-danger': errors['customer.address'] }"
                            >
                                <label class="control-label">Dirección</label>
                                <el-input v-model="form.customer.address"></el-input>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors['customer.address']"
                                    v-text="errors['customer.address'][0]"
                                ></small>
                            </div>
                            <div
                                class="form-group col-12 col-md-10"
                                :class="{ 'has-danger': errors.notes }"
                            >
                                <label class="control-label" for="notes">Notas</label>
                                <el-input v-model="form.notes"></el-input>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.notes"
                                    v-text="errors.notes[0]"
                                ></small>
                            </div>
                            <div
                                class="form-group col-12 col-md-2"
                                :class="{ 'has-danger': errors.towels }"
                            >
                                <label class="control-label" for="notes">Toallas</label>
                                <el-input v-model="form.towels" type="number"></el-input>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.towels"
                                    v-text="errors.towels[0]"
                                ></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Datos del alojamiento</div>
                    <div class="card-body">
                        <div class="row">
                            <div
                                class="col-12 col-md-3 form-group"
                                :class="{ 'has-danger': errors.hotel_rate_id }"
                            >
                                <label class="control-label" for="rate">Tarifa</label>
                                <el-select
                                    v-model="form.hotel_rate_id"
                                    @change="onSelectedRate"
                                >
                                    <el-option
                                        v-for="option in room.rates"
                                        :key="option.hotel_rate_id"
                                        :value="option.hotel_rate_id"
                                        :label="option.rate.description"
                                    ></el-option>
                                </el-select>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.hotel_rate_id"
                                    v-text="errors.hotel_rate_id[0]"
                                ></small>
                            </div>

                            <!-- afectación igv -->
                            <div
                                class="col-12 col-md-3 form-group"
                                :class="{ 'has-danger': errors.affectation_igv_type_id }"
                            >
                                <label class="control-label" for="rate">Tipo de afectación</label>

                                <el-select
                                    v-model="form.affectation_igv_type_id"
                                >
                                    <el-option
                                        v-for="option in getAllowedAffectationIgvTypes"
                                        :key="option.id"
                                        :value="option.id"
                                        :label="option.description"
                                    ></el-option>
                                </el-select>

                                <small
                                    class="form-control-feedback"
                                    v-if="errors.affectation_igv_type_id"
                                    v-text="errors.affectation_igv_type_id[0]"
                                ></small>
                            </div>
                            <!-- afectación igv -->

                            <div
                                class="col-12 col-md-2 form-group"
                                :class="{ 'has-danger': errors.rate_price }"
                                v-if="rate"
                            >
                                <label class="control-label" for="rate">Precio</label>
                                <el-input-number
                                    v-model="form.rate_price"
                                    controls-position="right"
                                    :min="0"
                                    @change="onUpdateTotalToPay"
                                ></el-input-number>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.rate_price"
                                    v-text="errors.rate_price[0]"
                                ></small>
                            </div>


                            <div
                                class="col-12 col-md-2 form-group"
                                :class="{ 'has-danger': errors.duration }"
                                v-if="rate"
                            >
                                <label class="control-label" for="rate">Cant. noches</label>
                                <el-input-number
                                    v-model="form.duration"
                                    controls-position="right"
                                    @change="onUpdateTotalToPay"
                                    :min="1"
                                ></el-input-number>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.duration"
                                    v-text="errors.duration[0]"
                                ></small>
                            </div>
                            <div class="col-12 col-md-2 text-center">
                                <h6>
                                    Total a pagar:
                                    <br/>
                                    <span class="h5">{{ form.total_to_pay }}</span>
                                </h6>
                            </div>
                            <div
                                class="col-6 col-md-3 form-group"
                                :class="{ 'has-danger': errors.quantity_persons }"
                            >
                                <label class="control-label">Cant. de personas ({{ form.quantity_persons }})</label>
                                <el-button icon="el-icon-edit-outline"
                                    size="small"
                                    type="success"
                                    @click.prevent="clickAddPerson">Personas
                                </el-button>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.quantity_persons"
                                    v-text="errors.quantity_persons[0]"
                                ></small>
                            </div>
                            <div>
                                <div :class="{'has-danger': errors.lot_code}"
                                        class="form-group">
                                    
                                    <small v-if="errors.lot_code"
                                            class="form-control-feedback"
                                            v-text="errors.lot_code[0]"></small>
                                </div>
                            </div>
                            <div
                                class="col-6 col-md-3 form-group"
                                :class="{ 'has-danger': errors.payment_status }"
                            >
                                <label class="control-label">Estado de pago</label>
                                <el-select
                                    v-model="form.payment_status"
                                    @change="onChangeStatusPayment"
                                >
                                    <el-option value="PAID" label="Pagado"></el-option>
                                    <el-option value="DEBT" label="Falta pagar"></el-option>
                                </el-select>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.payment_status"
                                    v-text="errors.payment_status[0]"
                                ></small>
                            </div>
                            <div
                                class="col-6 col-md-3 form-group"
                                :class="{ 'has-danger': errors.output_date }"
                            >
                                <label class="control-label">Fecha de salida</label>
                                <el-date-picker
                                    v-model="form.output_date"
                                    type="date"
                                    placeholder="Seleccione una fecha"
                                    value-format="yyyy-MM-dd"
                                ></el-date-picker>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.output_date"
                                    v-text="errors.output_date[0]"
                                ></small>
                            </div>
                            <div
                                class="col-6 col-md-3 form-group"
                                :class="{ 'has-danger': errors.output_time }"
                            >
                                <label class="control-label">Hora de salida</label>
                                <el-input v-model="form.output_time" placeholder="HH:MM">
                                </el-input>
                                <small
                                    class="form-control-feedback"
                                    v-if="errors.output_time"
                                    v-text="errors.output_time[0]"
                                ></small>
                            </div>
                            
                            <!-- mostrar campos adicionales para pago, si tiene estado pagado -->
                            <template v-if="isPaid">
                                
                                <div
                                    class="col-12 col-md-4 form-group"
                                    :class="{ 'has-danger': errors['rent_payment.payment_method_type_id'] }"
                                >
                                    <label class="control-label" for="rate">Método de pago</label>

                                    <el-select
                                        v-model="form.rent_payment.payment_method_type_id"
                                        filterable
                                    >
                                        <el-option
                                            v-for="option in payment_method_types"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"
                                        ></el-option>
                                    </el-select>

                                    <small
                                        class="form-control-feedback"
                                        v-if="errors['rent_payment.payment_method_type_id']"
                                        v-text="errors['rent_payment.payment_method_type_id'][0]"
                                    ></small>
                                </div>

                                <div
                                    class="col-12 col-md-4 form-group"
                                    :class="{ 'has-danger': errors['rent_payment.payment_destination_id'] }"
                                >
                                    <label class="control-label" for="rate">Destino</label>

                                    <el-select
                                        v-model="form.rent_payment.payment_destination_id"
                                        filterable
                                    >
                                        <el-option
                                            v-for="option in payment_destinations"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"
                                        ></el-option>
                                    </el-select>

                                    <small
                                        class="form-control-feedback"
                                        v-if="errors['rent_payment.payment_destination_id']"
                                        v-text="errors['rent_payment.payment_destination_id'][0]"
                                    ></small>
                                </div>
                                
                                <div
                                    class="col-12 col-md-4 form-group"
                                >
                                    <label class="control-label" for="rate">Referencia</label>

                                    <el-input
                                        v-model="form.rent_payment.reference"
                                    ></el-input>
                                </div>
                            </template>

                        </div>
                        <div class="d-flex justify-content-between pt-5">
                            <el-button
                                type="primary"
                                :loading="loading"
                                :disabled="loading"
                                @click="onSubmit"
                            >Guardar
                            </el-button
                            >
                            <el-button class="second-buton" @click="onToBackPage">Cancelar</el-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <person-form
            :showDialog.sync="showDialogNewPerson"
            type="customers"
            :external="true"
            :input_person="input_person"
            :document_type_id="form.document_type_id"
        ></person-form>

        <quantity-persons
            :persons="form.data_persons"
            :showDialog.sync="showDialogPersons"
            :quantity="form.quantity_persons"
            @addRowPerson="addRowPerson">
        </quantity-persons>
    </div>
</template>

<script>

import PersonForm from "../../../../../../../resources/js/views/tenant/persons/form.vue";
import moment from "moment";
import {calculateRowItem} from "../../../../../../../resources/js/helpers/functions";
import {functions} from "../../../../../../../resources/js/mixins/functions";
import {mapState} from "vuex/dist/vuex.mjs";
import QuantityPersons from './partials/QuantityPersons.vue';

export default {
    components: {
        PersonForm,
        QuantityPersons,
    },
    mixins: [
        functions
    ],
    props: {
        room: {
            type: Object,
            required: true,
            default: {},
        },
        affectationIgvTypes: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            customers: [],
            customer: {},
            customerId: null,
            customer_barcode: null,
            form: {
                customer: {},
                towels: 1,
                rate: {},
                duration: 1,
                total_to_pay: 0,
                output_time: "12:00",
                output_date: null,
                payment_status: 'PAID',
                quantity_persons: 1,
                data_persons:[],
                affectation_igv_type_id: null,
                date_of_issue: moment().format("YYYY-MM-DD"),
                establishment_id: null,
                rent_payment: {
                    payment_method_type_id: null,
                    payment_destination_id: null,
                    reference: null,
                    payment: 0,
                },
            },
            rate: null,
            loading: false,
            showDialogNewPerson: false,
            showDialogPersons: false,
            search_item_by_barcode: false,
            input_person: {},
            configuration: {},
            errors: {
                customer: {},
            },
            recordItem: null,
            payment_method_types: [],
            payment_destinations: [],
        };
    },
    async mounted() {
        await this.onFetchTables();
        this.onUpdateOutputDate();
    },
    async created() {
        await this.$eventHub.$on("reloadDataPersons", (customerId) => {
            this.reloadDataCustomers(customerId);
        });
    },
    computed: {
        isPaid()
        {
            return this.form.payment_status === 'PAID'
        },
        ...mapState([
            'config',
        ]),
        getAllowedAffectationIgvTypes: function () {
            return this.affectationIgvTypes.filter((item) => {
                return ['10', '20'].includes(item.id)
            })
        },
    },
    methods: {
        async onSubmit() {
            this.loading = true;
            await this.$http
                .get(`/documents/search/item/${this.room.item_id}`)
                .then((response) => {

                    const payload = {};
                    const item = response.data.items[0];

                    payload.item = item;
                    payload.discounts = [];
                    payload.charges = [];
                    payload.attributes = [];
                    payload.item_unit_types = item.item_unit_types;
                    payload.unit_price_value = this.form.rate_price;
                    payload.has_igv = item.has_igv;
                    payload.has_plastic_bag_taxes = item.has_plastic_bag_taxes;

                    // payload.affectation_igv_type_id = item.sale_affectation_igv_type_id;

                    payload.affectation_igv_type_id = this.form.affectation_igv_type_id
                    payload.affectation_igv_type = _.find(this.affectationIgvTypes, {
                        id: payload.affectation_igv_type_id,
                    });


                    payload.quantity = this.form.duration;
                    const unit_price = item.has_igv
                        ? payload.unit_price_value
                        : payload.unit_price_value * (1 + this.percentage_igv);
                    payload.input_unit_price_value = payload.unit_price_value;
                    payload.unit_price = unit_price;
                    payload.item.unit_price = unit_price;
                    const currencyTypeIdActive = "PEN";
                    const exchangeRateSale = 0;
                    const product = calculateRowItem(
                        payload,
                        currencyTypeIdActive,
                        exchangeRateSale,
                        this.percentage_igv
                    );
                    this.form.product = product;
                    this.$http
                        .post(`/hotels/reception/${this.room.id}/rent/store`, this.form)
                        .then((response) => {
                            this.$message({
                                message: response.data.message,
                                type: "success",
                            });
                            this.onToBackPage();
                        })
                        .catch((error) => {
                            this.axiosError(error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                })
                .finally(() => (this.loading = false));
        },
        onToBackPage() {
            window.location.href = "/hotels/reception";
        },
        onChangeStatusPayment() {
            if (this.form.payment_status === "DEBT") {
                this.form.payment_type = "CASH";
            }
        },
        onUpdateTotalToPay() {
            this.form.total_to_pay = this.form.rate_price * this.form.duration;
            this.onUpdateOutputDate();
            this.setTotalPayment()
        },
        setTotalPayment()
        {
            this.form.rent_payment.payment = this.form.total_to_pay
        },
        onUpdateOutputDate() {
            const newDate = moment().add(this.form.duration, "days");
            this.form.output_date = newDate.format("YYYY-MM-DD");
        },
        onSelectedRate() {
            const rate = this.room.rates
                .filter((r) => r.hotel_rate_id === this.form.hotel_rate_id)
                .reduce((r) => r);
            this.rate = rate.rate;
            this.rate.price = rate.price;
            this.form.rate_price = rate.price;
            this.onUpdateTotalToPay();
        },
        async reloadDataCustomers(customerId) {
            await this.$http
                .get(`/persons/search/${customerId}`)
                .then((response) => {
                    this.customers = response.data.customers;
                    this.form.customer_id = customerId;
                    this.changeCustomer();
                });

        },
        keyupCustomer() {
            if (this.input_person.number) {
                if (!isNaN(parseInt(this.input_person.number))) {
                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = "1";
                            this.showDialogNewPerson = true;
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = "6";
                            this.showDialogNewPerson = true;
                            break;
                        default:
                            this.input_person.identity_document_type_id = "6";
                            this.showDialogNewPerson = true;
                            break;
                    }
                }
            }
        },
        async onFetchTables() {
            this.loading = true;
            await this.$http
                .get("/hotels/reception/tables")
                .then((response) => {
                    this.customers = response.data.customers;
                    this.configuration = response.data.configuration
                    
                    this.payment_method_types = response.data.payment_method_types
                    this.payment_destinations = response.data.payment_destinations
                    this.setDefaultDataPayments()

                    this.setAffectationIgvType()
                })
                .finally(() => {
                    this.loading = false;
                });
            this.form.establishment_id = this.config.establishment.id;
            await this.getPercentageIgv();
        },
        setDefaultDataPayments()
        {
            this.form.rent_payment.payment_method_type_id = this.payment_method_types.length > 0 ? this.payment_method_types[0].id : null
        },
        setAffectationIgvType() {

            let affectation_igv_type = _.find(this.getAllowedAffectationIgvTypes, {id: this.configuration.affectation_igv_type_id})
            this.form.affectation_igv_type_id = (affectation_igv_type) ? affectation_igv_type.id : '10'

        },
        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading = true;

                const params = {
                    'input': input,
                    'search_by_barcode': this.search_item_by_barcode ? 1 : 0
                }
                this.$http
                    .get(`/hotels/reception/tables/customers`, {params})
                    .then((response) => {
                        this.customers = response.data.customers;

                        this.input_person.number = null;

                        if (this.customers.length == 0) {
                            this.input_person.number = input;
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            } else {
                this.input_person.number = null;
            }
        },
        changeCustomer() {
            this.customer = this.customers
                .filter((c) => c.id === this.form.customer_id)
                .reduce((c) => c);

            this.form.customer = {...this.customer};
            this.form.data_persons = [];
            this.form.data_persons.push({
                number: this.form.customer.number,
                name: this.form.customer.name
            });
        },
        onGetStatus(status) {
            if (status === "DISPONIBLE") {
                return "badge-success";
            }
            if (status === "OCUPADO") {
                return "badge-danger";
            }
            if (status === "MANTENIMIENTO") {
                return "badge-warning";
            }
            if (status === "LIMPIEZA") {
                return "badge-info";
            }
        },
        clickAddPerson() {
            this.showDialogPersons = true
        },
        addRowPerson(persons) {
            this.form.quantity_persons = persons.length
            this.form.data_persons = persons
        },

    },
};
</script>
