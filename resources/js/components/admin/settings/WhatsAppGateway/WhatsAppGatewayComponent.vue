<template>
    <LoadingComponent :props="loading" />
    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">WhatsApp Gateway Settings</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <div class="row">
                    <div class="form-col-12">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" v-model="form.whatsapp_enabled" id="whatsapp_enabled" class="mr-2">
                            <label for="whatsapp_enabled" class="db-field-title">
                                Enable WhatsApp Notifications
                            </label>
                        </div>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="whatsapp_api_url" class="db-field-title required">
                            API URL
                        </label>
                        <input v-model="form.whatsapp_api_url" 
                               v-bind:class="errors.whatsapp_api_url ? 'invalid' : ''"
                               type="url" 
                               id="whatsapp_api_url" 
                               class="db-field-control"
                               placeholder="https://dev-iptv-wa.appdewa.com/message/send-text" />
                        <small class="db-field-alert" v-if="errors.whatsapp_api_url">
                            {{ errors.whatsapp_api_url[0] }}
                        </small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="whatsapp_session" class="db-field-title required">
                            Session
                        </label>
                        <input v-model="form.whatsapp_session" 
                               v-bind:class="errors.whatsapp_session ? 'invalid' : ''"
                               type="text" 
                               id="whatsapp_session" 
                               class="db-field-control"
                               placeholder="mysession" />
                        <small class="db-field-alert" v-if="errors.whatsapp_session">
                            {{ errors.whatsapp_session[0] }}
                        </small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="whatsapp_phone" class="db-field-title required">
                            Phone Number or Group ID
                        </label>
                        <input v-model="form.whatsapp_phone" 
                               v-bind:class="errors.whatsapp_phone ? 'invalid' : ''"
                               type="text" 
                               id="whatsapp_phone" 
                               class="db-field-control"
                               placeholder="62812345678 atau 120363304142052316@g.us" />
                        <small class="db-field-alert" v-if="errors.whatsapp_phone">
                            {{ errors.whatsapp_phone[0] }}
                        </small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="whatsapp_company_name" class="db-field-title">
                            Company Name
                        </label>
                        <input v-model="form.whatsapp_company_name" 
                               v-bind:class="errors.whatsapp_company_name ? 'invalid' : ''"
                               type="text" 
                               id="whatsapp_company_name" 
                               class="db-field-control"
                               placeholder="Canting Food" />
                        <small class="db-field-alert" v-if="errors.whatsapp_company_name">
                            {{ errors.whatsapp_company_name[0] }}
                        </small>
                    </div>

                    <div class="form-col-12">
                        <label for="whatsapp_message_template" class="db-field-title">
                            Message Template
                        </label>
                        <textarea v-model="form.whatsapp_message_template" 
                                  v-bind:class="errors.whatsapp_message_template ? 'invalid' : ''"
                                  id="whatsapp_message_template" 
                                  class="db-field-control" 
                                  rows="8"
                                  placeholder="Enter your message template with placeholders like {company_name}, {table_name}, {items}, {subtotal}, {tax}, {total}"></textarea>
                        <small class="db-field-alert" v-if="errors.whatsapp_message_template">
                            {{ errors.whatsapp_message_template[0] }}
                        </small>
                        <small class="text-gray-600 mt-1 block">
                            Available placeholders: {company_name}, {table_name}, {items}, {subtotal}, {tax}, {total}
                        </small>
                    </div>

                    <div class="form-col-12">
                        <div class="flex gap-2">
                            <button type="submit" class="db-btn text-white bg-primary">
                                <i class="lab lab-save"></i>
                                <span>Save Settings</span>
                            </button>
                            <button type="button" 
                                    @click="testConnection" 
                                    class="db-btn text-white bg-green-500"
                                    :disabled="!form.whatsapp_enabled">
                                <i class="lab lab-test"></i>
                                <span>Test Connection</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";

export default {
    name: "WhatsAppGatewayComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                whatsapp_enabled: false,
                whatsapp_api_url: '',
                whatsapp_session: '',
                whatsapp_phone: '',
                whatsapp_company_name: 'Canting Food',
                whatsapp_message_template: '',
            },
            errors: {},
        };
    },
    mounted() {
        this.loadSettings();
    },
    methods: {
        loadSettings: async function () {
            try {
                this.loading.isActive = true;
                const res = await this.$store.dispatch('whatsappGateway/lists');
                this.form = {
                    whatsapp_enabled: res.data.data.whatsapp_enabled || false,
                    whatsapp_api_url: res.data.data.whatsapp_api_url || '',
                    whatsapp_session: res.data.data.whatsapp_session || '',
                    whatsapp_phone: res.data.data.whatsapp_phone || '',
                    whatsapp_company_name: res.data.data.whatsapp_company_name || 'Canting Food',
                    whatsapp_message_template: res.data.data.whatsapp_message_template || '',
                };
                this.loading.isActive = false;
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("whatsappGateway/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success("WhatsApp gateway settings updated successfully");
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        testConnection: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("whatsappGateway/testConnection").then((res) => {
                    this.loading.isActive = false;
                    alertService.success("WhatsApp connection test successful!");
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response?.data?.message || 'Connection test failed');
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
