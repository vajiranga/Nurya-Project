<template>
  <q-page padding>
    <!-- Admin Dashboard Menu -->
    <div v-if="view === 'menu'" class="q-pa-md">
      <div class="text-h4 q-mb-xl text-center font-playfair text-gold">Admin Dashboard</div>
      
      <div class="row q-col-gutter-lg justify-center">
        <!-- Manage Products Card -->
        <div class="col-12 col-sm-6 col-md-4">
          <q-card class="menu-card cursor-pointer q-hoverable" @click="view = 'products'" v-ripple>
            <q-card-section class="flex flex-center column q-py-xl">
              <q-icon name="inventory_2" size="4em" color="primary" class="q-mb-md" />
              <div class="text-h5">Manage Products</div>
              <div class="text-grey-7 text-center q-mt-sm">Add, Edit, and Manage Inventory</div>
            </q-card-section>
          </q-card>
        </div>

        <!-- Manage Vouchers Card -->
        <div class="col-12 col-sm-6 col-md-4">
          <q-card class="menu-card cursor-pointer q-hoverable" @click="view = 'vouchers'" v-ripple>
            <q-card-section class="flex flex-center column q-py-xl">
              <q-icon name="local_offer" size="4em" color="accent" class="q-mb-md" />
              <div class="text-h5">Manage Vouchers</div>
              <div class="text-grey-7 text-center q-mt-sm">Create and Edit Coupons</div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>

    <!-- Product Management View -->
    <div v-else-if="view === 'products'">
      <div class="row items-center q-mb-md">
        <q-btn flat icon="arrow_back" label="Back to Dashboard" color="primary" @click="view = 'menu'" />
      </div>

      <div class="row q-col-gutter-md">
        <!-- Add Product Form -->
        <div class="col-12 col-md-4">
          <q-card>
            <q-card-section>
              <div class="text-h6">{{ isEditing ? 'Edit Product' : 'Add New Product' }}</div>
            </q-card-section>

            <q-card-section>
              <q-form ref="formRef" @submit="onSubmit" class="q-gutter-md">
                <q-input filled v-model="form.name" label="Product Name" :rules="[val => !!val || 'Name is required']" />
                <q-input filled v-model="form.sku" label="SKU (Optional)" class="q-mb-md"/>
                
                <div class="row items-center q-mb-md">
                  <q-toggle v-model="hasVariants" label="Product has variants (Size)?" color="primary" />
                </div>

                <!-- Price Input (If No Variants) -->
                <q-input 
                  v-if="!hasVariants"
                  filled 
                  v-model="form.price" 
                  type="number" 
                  label="Price (LKR)" 
                  :rules="[val => !!val || 'Price is required']" 
                />

                <!-- Variants Inputs (If Has Variants) -->
                <div v-else class="q-mb-md">
                  <div v-for="(variant, index) in form.variants" :key="index" class="row q-col-gutter-sm q-mb-sm items-center">
                    <div class="col-6">
                      <q-input filled v-model="variant.size" label="Size/Label" dense />
                    </div>
                    <div class="col-5">
                      <q-input filled v-model="variant.price" type="number" label="Price" dense />
                    </div>
                    <div class="col-1">
                      <q-btn icon="remove_circle" color="negative" flat round dense @click="removeVariant(index)" />
                    </div>
                  </div>
                  <div class="text-negative text-caption" v-if="variantsErrors">{{ variantsErrors }}</div>
                  <q-btn label="Add Variant" icon="add" flat color="primary" size="sm" @click="addVariant" />
                </div>
                
                <q-select 
                  filled 
                  v-model="form.category_id" 
                  :options="categoryOptions" 
                  label="Category" 
                  emit-value 
                  map-options 
                  option-label="name" 
                  option-value="id"
                  class="q-mb-md"
                />

                <div class="row q-col-gutter-sm q-mb-md">
                  <div class="col-6">
                    <q-input filled v-model="form.gold_weight" label="Gold Weight (e.g., 2.5g)" />
                  </div>
                  <div class="col-6">
                    <q-select filled v-model="form.karat" :options="['18K', '20K', '21K', '22K', '24K']" label="Karat" />
                  </div>
                </div>

                <q-select 
                  filled 
                  v-model="form.stock_status" 
                  :options="['In Stock', 'Low Stock', 'Out of Stock']" 
                  label="Stock Status" 
                  class="q-mb-md"
                />

                <q-input filled v-model="form.description" type="textarea" label="Description" class="q-mb-md" />
                
                <!-- Image Upload -->
                <q-file
                  v-model="form.images"
                  label="Product Images"
                  filled
                  multiple
                  accept="image/*"
                  class="q-mb-md"
                >
                  <template v-slot:prepend>
                    <q-icon name="attach_file" />
                  </template>
                </q-file>

                <div class="row justify-end q-gutter-sm">
                  <q-btn label="Reset" flat color="warning" @click="onReset" />
                  <q-btn :label="isEditing ? 'Update Product' : 'Add Product'" color="primary" @click="onSubmit" :loading="submitting" />
                </div>
              </q-form>
            </q-card-section>
          </q-card>
        </div>

        <!-- Product List -->
        <div class="col-12 col-md-8">
          <q-table
            title="Product List"
            :rows="filteredProducts"
            :columns="columns"
            row-key="id"
            :filter="filter"
            :loading="loading"
            v-model:pagination="pagination"
            :rows-per-page-options="[20, 50, 100, 0]"
          >
            <template v-slot:top-right>
              <q-select 
                filled 
                dense 
                v-model="stockFilter" 
                :options="['All', 'In Stock', 'Low Stock', 'Out of Stock']" 
                label="Stock Filter" 
                class="q-mr-md" 
                style="min-width: 150px"
              />
              <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </template>

            <template v-slot:body="props">
               <q-tr :props="props">
                  <q-td key="image" :props="props">
                     <q-img :src="getImage(props.row.images_json)" style="width: 50px; height: 50px" />
                  </q-td>
                  <q-td key="sku" :props="props">{{ props.row.sku || '-' }}</q-td>
                  <q-td key="name" :props="props">{{ props.row.name }}</q-td>
                  <q-td key="price" :props="props">LKR {{ props.row.price }}</q-td>
                  <q-td key="category" :props="props">{{ props.row.category ? props.row.category.name : '-' }}</q-td>
                  <q-td key="sizes" :props="props">
                     <div v-if="props.row.sizes">
                        <div v-for="(s, i) in (typeof props.row.sizes === 'string' ? JSON.parse(props.row.sizes) : props.row.sizes)" :key="i">
                          {{ s.size || 'Base' }}: {{ s.price }}
                        </div>
                     </div>
                     <div v-else>-</div>
                  </q-td>
                  <q-td key="stock" :props="props">
                     <q-chip 
                       :color="getStockColor(props.row.stock_status)" 
                       text-color="white" 
                       dense
                     >
                       {{ getStockLabel(props.row.stock_status) }}
                     </q-chip>
                  </q-td>
                  <q-td key="actions" :props="props">
                      <q-btn flat round color="primary" icon="edit" size="sm" @click="editProduct(props.row)" />
                      <q-btn flat round color="negative" icon="delete" size="sm" @click="deleteProduct(props.row.id)" />
                  </q-td>
               </q-tr>
            </template>
          </q-table>
        </div>
      </div>
    </div>

    <!-- Voucher Management View -->
    <div v-else-if="view === 'vouchers'">
      <div class="row items-center q-mb-md">
        <q-btn flat icon="arrow_back" label="Back to Dashboard" color="primary" @click="view = 'menu'" />
      </div>

      <div class="row q-col-gutter-md">
        <!-- Add Voucher Form -->
        <div class="col-12 col-md-4">
           <q-card>
             <q-card-section>
               <div class="text-h6">{{ isEditingVoucher ? 'Edit Voucher' : 'Create New Voucher' }}</div>
             </q-card-section>
             <q-card-section>
                <q-form @submit="onVoucherSubmit" class="q-gutter-md">
                  <q-input filled v-model="voucherForm.code" label="Code (e.g. SALE10)" :rules="[val => !!val || 'Code is required']" />
                  <q-input filled v-model="voucherForm.title" label="Title (e.g. 10% Off New Year Sale)" :rules="[val => !!val || 'Title is required']" />
                  
                  <q-select 
                    filled 
                    v-model="voucherForm.discount_type" 
                    :options="[{label: 'Percentage (%)', value: 'percentage'}, {label: 'Fixed Amount (LKR)', value: 'fixed'}, {label: 'Free Shipping', value: 'free_shipping'}]" 
                    label="Discount Type" 
                    emit-value
                    map-options
                  />

                  <q-input 
                    v-if="voucherForm.discount_type !== 'free_shipping'"
                    filled 
                    v-model="voucherForm.discount_value" 
                    type="number" 
                    :label="voucherForm.discount_type === 'percentage' ? 'Percentage (%)' : 'Amount (LKR)'"
                    :rules="[val => (voucherForm.discount_type === 'free_shipping' || !!val) || 'Value is required']"
                  />

                  <q-input filled v-model="voucherForm.min_spend" type="number" label="Minimum Spend (Optional)" hint="Leave empty for no minimum" />

                  <div class="row q-col-gutter-sm q-mb-md">
                    <div class="col-6">
                      <q-input filled v-model="voucherForm.usage_limit" type="number" label="Total Usage Limit (Optional)" hint="Total times voucher can be used" />
                    </div>
                    <div class="col-6">
                      <q-input filled v-model="voucherForm.limit_per_user" type="number" label="Limit Per User (Optional)" hint="Max uses per customer" />
                    </div>
                  </div>

                  <q-input filled v-model="voucherForm.expires_at" type="date" label="Expiry Date (Optional)" stack-label />
                  
                  <div class="row items-center justify-between">
                    <q-toggle v-model="voucherForm.is_public" label="Public (Visible to everyone)?" color="green" />
                    <q-toggle v-model="voucherForm.is_active" label="Active?" color="primary" />
                  </div>

                  <div class="row justify-end q-gutter-sm">
                    <q-btn label="Reset" flat color="warning" @click="resetVoucherForm" />
                    <q-btn :label="isEditingVoucher ? 'Update Voucher' : 'Create Voucher'" color="primary" text-color="white" type="submit" :loading="voucherSubmitting" />
                  </div>
                </q-form>
             </q-card-section>
           </q-card>
        </div>

        <!-- Voucher List -->
        <div class="col-12 col-md-8">
           <q-table
             title="Active Vouchers"
             :rows="vouchers"
             :columns="voucherColumns"
             row-key="id"
             :loading="voucherLoading"
           >
             <template v-slot:body-cell-status="props">
               <q-td :props="props">
                 <q-chip :color="props.row.is_active ? 'green' : 'grey'" text-color="white" dense>
                   {{ props.row.is_active ? 'Active' : 'Inactive' }}
                 </q-chip>
                 <q-chip v-if="props.row.is_public" color="blue" text-color="white" dense icon="public">Public</q-chip>
                 <q-chip v-else color="grey-8" text-color="white" dense icon="lock">Private</q-chip>
               </q-td>
             </template>
             <template v-slot:body-cell-actions="props">
               <q-td :props="props">
                 <q-btn flat round color="primary" icon="edit" size="sm" @click="editVoucher(props.row)" />
                 <q-btn flat round color="negative" icon="delete" size="sm" @click="deleteVoucher(props.row.id)" />
               </q-td>
             </template>
           </q-table>
        </div>
      </div>
    </div>

  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { api } from 'boot/axios';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const view = ref('menu'); // 'menu', 'products', 'vouchers'

// --- Product Management Logic ---
const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const submitting = ref(false);
const filter = ref('');
const stockFilter = ref('All');

const pagination = ref({
  rowsPerPage: 50
});

const filteredProducts = computed(() => {
  let result = products.value;
  if (stockFilter.value !== 'All') {
    result = result.filter(p => {
       const s = (p.stock_status || 'In Stock').toLowerCase();
       const f = stockFilter.value.toLowerCase();
       
       if (f === 'in stock') return s === 'in stock' || s === 'in_stock';
       if (f === 'low stock') return s === 'low stock' || s === 'low_stock';
       if (f === 'out of stock') return s === 'out of stock' || s === 'out_of_stock';
       
       return s === f;
    });
  }
  return result;
});

const columns = [
  { name: 'image', label: 'Image', field: 'image', align: 'left' },
  { name: 'sku', label: 'SKU', field: 'sku', align: 'left', sortable: true },
  { name: 'name', label: 'Name', field: 'name', align: 'left', sortable: true },
  { name: 'price', label: 'Price (LKR)', field: 'price', format: val => `LKR ${val}`, sortable: true },
  { name: 'category', label: 'Category', field: row => row.category ? row.category.name : '-', sortable: true },
  { name: 'sizes', label: 'Variants', field: 'sizes', align: 'left' },
  { name: 'stock', label: 'Stock Status', field: 'stock_status', sortable: true },
  { name: 'actions', label: 'Actions', field: 'actions', align: 'right' }
];

const form = ref({
  name: '',
  sku: '',
  price: '',
  category_id: null,
  gold_weight: '',
  karat: '22K',
  stock_status: 'In Stock',
  description: '',
  images: null, // File object array
  variants: []
});

const categoryOptions = ref([]);



const formRef = ref(null);
const isEditing = ref(false);
const editId = ref(null);
const hasVariants = ref(false);
const variantsErrors = ref('');

const addVariant = () => {
  form.value.variants.push({ size: '', price: '' });
};

const removeVariant = (index) => {
  form.value.variants.splice(index, 1);
};

const onSubmit = async () => {
  submitting.value = true;
  variantsErrors.value = '';

  // Validate variants if enabled
  if (hasVariants.value) {
    if (form.value.variants.length === 0) {
      variantsErrors.value = 'Add at least one variant';
      submitting.value = false;
      return;
    }
    // Check if any critical details are missing (Price is mandatory, others optional but at least one should be there)
    const invalid = form.value.variants.some(v => !v.price || !v.size);
    if (invalid) {
      variantsErrors.value = 'Price and Size are required.';
      submitting.value = false;
      return;
    }
  }

  try {
    const formData = new FormData();
    formData.append('name', form.value.name);
    
    if (hasVariants.value) {
      // Send variants as 'sizes' JSON for backend compatibility
      // Structure: [{ size: "10", price: "50000" }]
      form.value.variants.forEach((v, i) => {
        if (v.size) formData.append(`sizes[${i}][size]`, v.size);
        formData.append(`sizes[${i}][price]`, v.price);
      });
      // Set a base price as the minimum price from variants
      const minPrice = Math.min(...form.value.variants.map(v => Number(v.price)));
      formData.append('price', minPrice);
    } else {
      formData.append('price', form.value.price);
    }

    if (form.value.sku) formData.append('sku', form.value.sku);
    if (form.value.category_id) formData.append('category_id', form.value.category_id);
    if (form.value.gold_weight) formData.append('gold_weight', form.value.gold_weight);
    if (form.value.karat) formData.append('karat', form.value.karat);
    if (form.value.stock_status) formData.append('stock_status', form.value.stock_status);
    if (form.value.description) formData.append('description', form.value.description);

    if (form.value.images) {
      form.value.images.forEach((file) => {
        formData.append('images[]', file);
      });
    }

    if (isEditing.value) {
      formData.append('_method', 'PUT');
      await api.post(`/products/${editId.value}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      $q.notify({ type: 'positive', message: 'Product updated successfully' });
    } else {
      await api.post('/products', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      $q.notify({ type: 'positive', message: 'Product added successfully' });
    }
    
    onReset();
    await fetchData();
    
  } catch (err) {
    console.error(err);
    $q.notify({ type: 'negative', message: 'Operation failed' });
  } finally {
    submitting.value = false;
  }
};

const editProduct = (product) => {
  isEditing.value = true;
  editId.value = product.id;
  
  const existingVariants = product.sizes ? (typeof product.sizes === 'string' ? JSON.parse(product.sizes) : product.sizes) : [];
  hasVariants.value = existingVariants.length > 0;
  
  // Debug log to check images structure
  console.log('Editing Product Images:', product.images_json, typeof product.images_json);

  form.value = {
    name: product.name,
    sku: product.sku || '',
    price: product.price,
    category_id: product.category_id,
    gold_weight: product.gold_weight,
    karat: product.karat,
    stock_status: product.stock_status || 'In Stock',
    description: product.description,
    images: null, // File input usually requires File List, better to keep null and let user re-upload
    variants: existingVariants.length > 0 ? existingVariants : []
  };
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return;
  
  // Optimistic UI Update: Remove immediately
  const previousProducts = [...products.value];
  products.value = products.value.filter(p => p.id !== id);

  try {
    await api.delete(`/products/${id}`);
    $q.notify({ type: 'positive', message: 'Product deleted' });
    // No need to fetch data if strictly successful, but good to sync eventually.
    // We keep the optimistic change.
  } catch (err) {
    console.error(err);
    // Revert if failed
    products.value = previousProducts;
    $q.notify({ type: 'negative', message: 'Delete failed' });
  }
};

const onReset = () => {
  isEditing.value = false;
  editId.value = null;
  hasVariants.value = false;
  variantsErrors.value = '';
  form.value = {
    name: '',
    sku: '',
    price: '',
    category_id: null,
    gold_weight: '',
    karat: '22K',
    stock_status: 'In Stock',
    description: '',
    images: null,
    variants: []
  };
  
  if (formRef.value) {
    formRef.value.resetValidation();
  }
};

const getImage = (images) => {
  let imgArray = images;
  
  // Parse if string
  if (typeof images === 'string') {
    try {
      imgArray = JSON.parse(images);
    } catch (e) {
      console.error(e);
      imgArray = [];
    }
  }

  if (Array.isArray(imgArray) && imgArray.length > 0) {
    const img = imgArray[0];
    if (!img) return '/images/logo.png';
    if (img.startsWith('http')) return img;
    // Ensure we don't double slash
    const path = img.startsWith('/') ? img : `/${img}`;
    // Use localhost:8000 for dev. In prod, this should be env var.
    return `http://localhost:8000${path}`; 
  }
  return '/images/logo.png'; // Fallback
};

const getStockColor = (status) => {
  if (!status) return 'positive';
  const s = status.toLowerCase();
  if (s === 'in stock' || s === 'in_stock') return 'positive';
  if (s === 'out of stock' || s === 'out_of_stock') return 'negative';
  if (s === 'low stock' || s === 'low_stock') return 'orange-9';
  return 'positive'; // Default
};

const getStockLabel = (status) => {
  if (!status) return 'In Stock';
  const s = status.toLowerCase();
  if (s === 'in_stock') return 'In Stock';
  if (s === 'out_of_stock') return 'Out of Stock';
  if (s === 'low_stock') return 'Low Stock';
  return status;
};

// --- Voucher Management Logic ---
const vouchers = ref([]);
const voucherLoading = ref(false);
const voucherSubmitting = ref(false);

const voucherForm = ref({
  code: '',
  title: '',
  discount_type: 'percentage',
  discount_value: '',
  min_spend: '',
  is_public: false,
  expires_at: '',
  is_active: true
});

const voucherColumns = [
  { name: 'code', label: 'Code', field: 'code', align: 'left', sortable: true },
  { name: 'type', label: 'Type', field: 'discount_type', align: 'left' },
  { name: 'value', label: 'Value', field: row => row.discount_type === 'percentage' ? `${row.discount_value}%` : (row.discount_type === 'free_shipping' ? 'Free Ship' : `LKR ${row.discount_value}`), align: 'left' },
  { name: 'min_spend', label: 'Min Spend', field: row => row.min_spend ? `LKR ${row.min_spend}` : '-', align: 'left' },
  { name: 'status', label: 'Status/Visibility', field: 'is_active', align: 'left' },
  { name: 'actions', label: 'Actions', field: 'actions', align: 'right' }
];

const fetchVouchers = async () => {
  voucherLoading.value = true;
  try {
    const res = await api.get('/vouchers');
    vouchers.value = res.data;
  } catch (err) {
    console.error(err);
    $q.notify({ type: 'negative', message: 'Failed to load vouchers' });
  } finally {
    voucherLoading.value = false;
  }
};

const isEditingVoucher = ref(false);
const editVoucherId = ref(null);

const onVoucherSubmit = async () => {
  voucherSubmitting.value = true;
  try {
    if (isEditingVoucher.value) {
      await api.put(`/vouchers/${editVoucherId.value}`, voucherForm.value);
      $q.notify({ type: 'positive', message: 'Voucher updated successfully' });
    } else {
      await api.post('/vouchers', voucherForm.value);
      $q.notify({ type: 'positive', message: 'Voucher created successfully' });
    }
    resetVoucherForm();
    await fetchVouchers();
  } catch (err) {
    console.error(err);
    const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Operation failed';
    $q.notify({ type: 'negative', message: msg });
  } finally {
    voucherSubmitting.value = false;
  }
};

const editVoucher = (voucher) => {
  isEditingVoucher.value = true;
  editVoucherId.value = voucher.id;
  
  // Format date for date input (YYYY-MM-DD)
  let dateFormatted = '';
  if (voucher.expires_at) {
    dateFormatted = voucher.expires_at.split('T')[0];
  }

  voucherForm.value = {
    code: voucher.code,
    title: voucher.title,
    discount_type: voucher.discount_type,
    discount_value: voucher.discount_value,
    min_spend: voucher.min_spend,
    usage_limit: voucher.usage_limit,
    limit_per_user: voucher.limit_per_user,
    is_public: Boolean(voucher.is_public),
    expires_at: dateFormatted,
    is_active: Boolean(voucher.is_active)
  };
  // Scroll to top or form if needed
};

const deleteVoucher = async (id) => {
  if (!confirm('Delete this voucher?')) return;
  try {
    await api.delete(`/vouchers/${id}`);
    $q.notify({ type: 'positive', message: 'Voucher deleted' });
    await fetchVouchers();
  } catch (err) {
    console.error(err);
    $q.notify({ type: 'negative', message: 'Failed to delete voucher' });
  }
};

const resetVoucherForm = () => {
  voucherForm.value = {
    code: '',
    title: '',
    discount_type: 'percentage',
    discount_value: '',
    min_spend: '',
    is_public: false,
    expires_at: '',
    is_active: true
  };
  isEditingVoucher.value = false;
  editVoucherId.value = null;
};

const fetchData = async () => {
  loading.value = true;
  try {
    const [prodRes, catRes] = await Promise.all([
      api.get('/products'),
      api.get('/categories')
    ]);
    products.value = prodRes.data;
    categories.value = catRes.data;
    categoryOptions.value = categories.value.map(c => ({ id: c.id, name: c.name }));
    
    // Also fetch vouchers initial
    await fetchVouchers();
  } catch (err) {
    console.error(err);
    $q.notify({ type: 'negative', message: 'Failed to data' });
  } finally {
    loading.value = false;
  }
};
onMounted(fetchData);
</script>

<style scoped>
.menu-card {
  border-radius: 16px;
  transition: all 0.3s ease;
  border: 1px solid rgba(0,0,0,0.05);
}

.menu-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.menu-card .q-icon {
  transition: transform 0.3s ease;
}

.menu-card:hover .q-icon {
  transform: scale(1.1);
}

.text-gold {
  color: #D4AF37;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}
</style>
