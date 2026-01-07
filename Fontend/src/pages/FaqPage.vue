<template>
  <q-page class="faq-page q-pa-lg">
    <div class="container container-narrow">
      <div class="row items-center q-mb-xl" data-aos="fade-down">
        <h1 class="page-title q-ma-none text-center full-width">Frequently Asked Questions</h1>
      </div>

      <div class="q-gutter-y-md" style="max-width: 800px; margin: 0 auto">
        <!-- Order & Delivery -->
        <div class="text-h5 text-gold font-playfair q-mb-md q-mt-xl">Orders & Delivery</div>
        
        <q-expansion-item
          group="faq"
          icon="local_shipping"
          label="How long does delivery take?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              Standard delivery typically takes 2-4 business days within Colombo and suburbs. For outstation areas, it may take 3-5 business days. We ensure all jewellery is packaged securely.
            </q-card-section>
          </q-card>
        </q-expansion-item>

        <q-expansion-item
          group="faq"
          icon="payments"
          label="What payment methods do you accept?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              We currently support Cash On Delivery (COD), Direct Bank Transfer, and Store Pickup. For Bank Transfers, your order will be processed once we receive the payment confirmation via WhatsApp.
            </q-card-section>
          </q-card>
        </q-expansion-item>

        <q-expansion-item
          group="faq"
          icon="location_on"
          label="Where is your physical store located?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              Our showroom is located at No 29, Godakalana, Loluwagoda. You are welcome to visit us during business hours to view our exclusive collections in person.
            </q-card-section>
          </q-card>
        </q-expansion-item>

        <!-- Products -->
        <div class="text-h5 text-gold font-playfair q-mb-md q-mt-xl">Products & Warranty</div>

        <q-expansion-item
          group="faq"
          icon="verified"
          label="Is your gold certified?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              Yes, absolutely. All Nurya Jewellery items come with a certificate of authenticity guaranteeing the purity of the gold (22K or 24K as specified).
            </q-card-section>
          </q-card>
        </q-expansion-item>

        <q-expansion-item
          group="faq"
          icon="handyman"
          label="Do you offer customization?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              Yes, we specialize in bespoke designs. You can contact us via WhatsApp with your design requirements, and our master craftsmen will bring your vision to life.
            </q-card-section>
          </q-card>
        </q-expansion-item>

        <q-expansion-item
          group="faq"
          icon="replay"
          label="What is your return policy?"
          header-class="text-weight-bold faq-header"
          expand-icon-class="text-gold"
          class="shadow-1 overflow-hidden"
          style="border-radius: 8px"
        >
          <q-card class="faq-card">
            <q-card-section>
              We have a strict quality control process. However, if you find a manufacturing defect, please report it within 24 hours of delivery. Due to the nature of gold jewellery, we generally do not accept returns for change of mind.
            </q-card-section>
          </q-card>
        </q-expansion-item>
      </div>

      <div class="text-center q-mt-xl">
        <p class="text-grey-7">Still have questions?</p>
        <q-btn
          unelevated
          rounded
          color="gold"
          text-color="black"
          label="Contact Support"
          to="/contact"
          class="text-weight-bold"
        />
      </div>
    </div>
  </q-page>
</template>

<script setup>
// Script setup is minimal as we are just displaying content
</script>

<style lang="scss" scoped>
.faq-page {
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
  min-height: 80vh;
}

/* Custom FAQ Styles */
.faq-header {
  background: #f5f5f5; /* Light mode header bg */
  transition: all 0.3s ease;
}

.faq-card {
  background: white;
}

body.body--dark {
  .faq-page {
    background: #121212;
  }
  
  .faq-header {
    background: rgba(255, 255, 255, 0.05) !important; /* Dark mode header bg */
    color: white !important;
  }
  
  .faq-card {
    background: rgba(255, 255, 255, 0.05) !important; /* Dark mode card content bg */
  }

  /* Override text colors for dark mode */
  :deep(.q-item__label) {
    color: white !important;
  }
  
  :deep(.q-card__section) {
    color: #e0e0e0 !important;
  }

  .text-grey-7 {
    color: #bdbdbd !important;
  }
}

.container-narrow {
  max-width: 1000px;
  margin: 0 auto;
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  font-weight: 700;
  color: #D4AF37;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}

.text-gold {
  color: #D4AF37;
}

.bg-gold {
  background: #D4AF37 !important;
}
</style>
