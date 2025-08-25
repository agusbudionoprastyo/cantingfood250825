<template>
  <div class="whatsapp-template-manager">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fab fa-whatsapp text-success me-2"></i>
          WhatsApp Template Manager
        </h5>
        <button @click="showCreateModal = true" class="btn btn-primary btn-sm">
          <i class="fas fa-plus me-1"></i>
          Add Template
        </button>
      </div>
      
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <select v-model="selectedType" @change="filterTemplates" class="form-select">
              <option value="">All Types</option>
              <option value="order_notification">Order Notification</option>
              <option value="custom">Custom Template</option>
              <option value="marketing">Marketing Template</option>
            </select>
          </div>
          <div class="col-md-4">
            <select v-model="selectedStatus" @change="filterTemplates" class="form-select">
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="col-md-4">
            <input 
              v-model="searchQuery" 
              @input="filterTemplates"
              type="text" 
              class="form-control" 
              placeholder="Search templates..."
            >
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Usage</th>
                <th>Last Used</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="template in filteredTemplates" :key="template.id">
                <td>
                  <strong>{{ template.name }}</strong>
                  <br>
                  <small class="text-muted">{{ template.description }}</small>
                </td>
                <td>
                  <span :class="getTypeBadgeClass(template.type)">
                    {{ template.type_label }}
                  </span>
                </td>
                <td>
                  <div class="form-check form-switch">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      :checked="template.is_active"
                      @change="toggleStatus(template.id)"
                    >
                  </div>
                </td>
                <td>
                  <span class="badge bg-info">{{ template.usage_count }}</span>
                </td>
                <td>
                  <small>{{ template.last_used || 'Never' }}</small>
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <button @click="editTemplate(template)" class="btn btn-outline-primary">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button @click="previewTemplate(template)" class="btn btn-outline-info">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button @click="deleteTemplate(template.id)" class="btn btn-outline-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="filteredTemplates.length === 0" class="text-center py-4">
          <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
          <p class="text-muted">No templates found</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" :class="{ show: showCreateModal || showEditModal }" :style="{ display: (showCreateModal || showEditModal) ? 'block' : 'none' }">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEditing ? 'Edit Template' : 'Create New Template' }}
            </h5>
            <button @click="closeModal" type="button" class="btn-close"></button>
          </div>
          
          <div class="modal-body">
            <form @submit.prevent="saveTemplate">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Template Name *</label>
                    <input 
                      v-model="form.name" 
                      type="text" 
                      class="form-control" 
                      required
                    >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Template Type *</label>
                    <select v-model="form.type" class="form-select" required>
                      <option value="order_notification">Order Notification</option>
                      <option value="custom">Custom Template</option>
                      <option value="marketing">Marketing Template</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea 
                  v-model="form.description" 
                  class="form-control" 
                  rows="2"
                ></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Template Content *</label>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <small class="text-muted">Use variables like {order_id}, {customer_name}, etc.</small>
                  <button @click="showVariables = !showVariables" type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list"></i> Available Variables
                  </button>
                </div>
                
                <div v-if="showVariables" class="alert alert-info mb-3">
                  <h6>Available Variables:</h6>
                  <div class="row">
                    <div class="col-md-4" v-for="variable in availableVariables" :key="variable">
                      <code>{{ '{' + variable + '}' }}</code>
                    </div>
                  </div>
                </div>

                <textarea 
                  v-model="form.template_content" 
                  class="form-control" 
                  rows="10"
                  required
                  placeholder="Enter your template content here..."
                ></textarea>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input 
                    v-model="form.is_active" 
                    class="form-check-input" 
                    type="checkbox"
                  >
                  <label class="form-check-label">
                    Active Template
                  </label>
                </div>
              </div>
            </form>
          </div>
          
          <div class="modal-footer">
            <button @click="closeModal" type="button" class="btn btn-secondary">Cancel</button>
            <button @click="saveTemplate" type="button" class="btn btn-primary">
              {{ isEditing ? 'Update' : 'Create' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" :class="{ show: showPreviewModal }" :style="{ display: showPreviewModal ? 'block' : 'none' }">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Template Preview</h5>
            <button @click="showPreviewModal = false" type="button" class="btn-close"></button>
          </div>
          
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Original Template:</h6>
                <pre class="bg-light p-3 rounded">{{ previewData.original_template }}</pre>
              </div>
              <div class="col-md-6">
                <h6>Preview with Sample Data:</h6>
                <pre class="bg-success text-white p-3 rounded">{{ previewData.preview_content }}</pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Backdrop -->
    <div v-if="showCreateModal || showEditModal || showPreviewModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'WhatsappTemplateManager',
  data() {
    return {
      templates: [],
      filteredTemplates: [],
      selectedType: '',
      selectedStatus: '',
      searchQuery: '',
      showCreateModal: false,
      showEditModal: false,
      showPreviewModal: false,
      showVariables: false,
      isEditing: false,
      editingId: null,
      form: {
        name: '',
        type: 'order_notification',
        description: '',
        template_content: '',
        is_active: true,
        variables: []
      },
      previewData: {
        original_template: '',
        preview_content: '',
        variables_used: {}
      },
      availableVariables: {
        order_notification: [
          'order_id', 'order_type', 'order_date', 'payment_method', 'payment_status',
          'total_amount', 'customer_name', 'customer_phone', 'customer_email',
          'branch_name', 'branch_address', 'order_items', 'delivery_address',
          'timestamp', 'order_link'
        ],
        custom: ['custom_message', 'timestamp'],
        marketing: ['promo_title', 'promo_description', 'discount_amount', 'valid_until', 'promo_code', 'website_url']
      }
    }
  },
  
  async mounted() {
    await this.loadTemplates()
  },
  
  methods: {
    async loadTemplates() {
      try {
        const response = await axios.get('/api/admin/whatsapp-templates')
        this.templates = response.data.data
        this.filteredTemplates = this.templates
      } catch (error) {
        console.error('Error loading templates:', error)
        this.$toast.error('Failed to load templates')
      }
    },
    
    filterTemplates() {
      this.filteredTemplates = this.templates.filter(template => {
        const typeMatch = !this.selectedType || template.type === this.selectedType
        const statusMatch = this.selectedStatus === '' || template.is_active.toString() === this.selectedStatus
        const searchMatch = !this.searchQuery || 
          template.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          template.description?.toLowerCase().includes(this.searchQuery.toLowerCase())
        
        return typeMatch && statusMatch && searchMatch
      })
    },
    
    getTypeBadgeClass(type) {
      const classes = {
        order_notification: 'badge bg-primary',
        custom: 'badge bg-secondary',
        marketing: 'badge bg-warning'
      }
      return classes[type] || 'badge bg-light'
    },
    
    async toggleStatus(templateId) {
      try {
        await axios.post(`/api/admin/whatsapp-templates/${templateId}/toggle-status`)
        await this.loadTemplates()
        this.$toast.success('Template status updated')
      } catch (error) {
        console.error('Error toggling status:', error)
        this.$toast.error('Failed to update status')
      }
    },
    
    editTemplate(template) {
      this.isEditing = true
      this.editingId = template.id
      this.form = { ...template }
      this.showEditModal = true
    },
    
    async previewTemplate(template) {
      try {
        const sampleData = this.getSampleData(template.type)
        const response = await axios.post(`/api/admin/whatsapp-templates/${template.id}/preview`, {
          variables: sampleData
        })
        
        this.previewData = response.data.data
        this.showPreviewModal = true
      } catch (error) {
        console.error('Error previewing template:', error)
        this.$toast.error('Failed to generate preview')
      }
    },
    
    getSampleData(type) {
      const samples = {
        order_notification: {
          order_id: 'ORD123',
          order_type: 'dine-in',
          order_date: '25/08/2024 14:30',
          payment_method: 'cash',
          payment_status: 'pending',
          total_amount: 'Rp 150.000',
          customer_name: 'John Doe',
          customer_phone: '08123456789',
          customer_email: 'john@example.com',
          branch_name: 'Cabang Jakarta Pusat',
          branch_address: 'Jl. Sudirman No. 123',
          order_items: '1. Nasi Goreng Spesial (2x)\n2. Es Teh Manis (1x)',
          delivery_address: 'Jl. Thamrin No. 456, Jakarta',
          timestamp: '25/08/2024 14:30:45',
          order_link: 'https://yourdomain.com/admin/orders/123'
        },
        custom: {
          custom_message: 'This is a custom message',
          timestamp: '25/08/2024 14:30:45'
        },
        marketing: {
          promo_title: 'Special Discount',
          promo_description: 'Get 20% off on all orders',
          discount_amount: '20%',
          valid_until: '31/12/2024',
          promo_code: 'SPECIAL20',
          website_url: 'https://yourdomain.com'
        }
      }
      
      return samples[type] || {}
    },
    
    async deleteTemplate(templateId) {
      if (!confirm('Are you sure you want to delete this template?')) {
        return
      }
      
      try {
        await axios.delete(`/api/admin/whatsapp-templates/${templateId}`)
        await this.loadTemplates()
        this.$toast.success('Template deleted successfully')
      } catch (error) {
        console.error('Error deleting template:', error)
        this.$toast.error('Failed to delete template')
      }
    },
    
    async saveTemplate() {
      try {
        if (this.isEditing) {
          await axios.put(`/api/admin/whatsapp-templates/${this.editingId}`, this.form)
          this.$toast.success('Template updated successfully')
        } else {
          await axios.post('/api/admin/whatsapp-templates', this.form)
          this.$toast.success('Template created successfully')
        }
        
        this.closeModal()
        await this.loadTemplates()
      } catch (error) {
        console.error('Error saving template:', error)
        this.$toast.error('Failed to save template')
      }
    },
    
    closeModal() {
      this.showCreateModal = false
      this.showEditModal = false
      this.showPreviewModal = false
      this.isEditing = false
      this.editingId = null
      this.form = {
        name: '',
        type: 'order_notification',
        description: '',
        template_content: '',
        is_active: true,
        variables: []
      }
    }
  }
}
</script>

<style scoped>
.whatsapp-template-manager {
  padding: 20px;
}

.modal {
  z-index: 1050;
}

.modal-backdrop {
  z-index: 1040;
}

pre {
  white-space: pre-wrap;
  word-wrap: break-word;
  font-size: 12px;
  max-height: 300px;
  overflow-y: auto;
}

.form-check-input:checked {
  background-color: #25d366;
  border-color: #25d366;
}
</style>
