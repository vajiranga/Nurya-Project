<template>
  <q-page class="contact-page q-pa-md">
    <div class="container">
      <h1 class="text-h3 text-center font-playfair q-mb-xl text-gold" data-aos="fade-up">Contact Us</h1>

      <div class="row justify-center q-col-gutter-lg">
        <!-- Contact Information Card -->
        <div class="col-12 col-md-6" data-aos="fade-right">
          <q-card class="contact-card h-100" flat bordered>
            <q-card-section>
              <h2 class="text-h4 q-mb-md">Get in Touch</h2>
              <p class="text-subtitle1 text-grey-7 q-mb-lg">
                We are always here to help you. Reach out to us through any of the following channels.
              </p>

              <q-list padding>
                <!-- Phone / Call -->
                <q-item clickable v-ripple tag="a" href="tel:0776819034" class="contact-item">
                  <q-item-section avatar>
                    <q-icon name="call" color="green-6" size="2em" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-h6">Call Us</q-item-label>
                    <q-item-label caption class="text-subtitle1">077 681 9034</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-icon name="arrow_forward" color="grey" />
                  </q-item-section>
                </q-item>

                <q-separator spaced />

                <!-- WhatsApp -->
                <q-item clickable v-ripple @click="openWhatsAppSupport" class="contact-item">
                  <q-item-section avatar>
                    <!-- WhatsApp SVG Icon -->
                    <svg
                      style="width: 2em; height: 2em"
                      viewBox="0 0 24 24"
                      fill="#25D366"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-h6">WhatsApp Support</q-item-label>
                    <q-item-label caption class="text-subtitle1">Chat with us</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-icon name="arrow_forward" color="grey" />
                  </q-item-section>
                </q-item>

                <q-separator spaced />

                <!-- Email -->
                <q-item clickable v-ripple tag="a" href="mailto:Nuryajewellery@gmail.com" class="contact-item">
                  <q-item-section avatar>
                    <q-icon name="email" color="red-5" size="2em" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-h6">Email Us</q-item-label>
                    <q-item-label caption class="text-subtitle1">Nuryajewellery@gmail.com</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-btn flat round icon="content_copy" color="grey-7" @click.prevent.stop="copyEmail">
                      <q-tooltip>Copy Email</q-tooltip>
                    </q-btn>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
          </q-card>
        </div>

        <!-- Social Media Card -->
        <div class="col-12 col-md-6" data-aos="fade-left">
          <q-card class="contact-card h-100" flat bordered>
            <q-card-section>
              <h2 class="text-h4 q-mb-md">Follow Us</h2>
              <p class="text-subtitle1 text-grey-7 q-mb-lg">
                Stay updated with our latest collections and offers on social media.
              </p>

              <div class="social-links-grid">
                <q-btn
                  v-for="social in socialLinks"
                  :key="social.name"
                  :href="social.link"
                  target="_blank"
                  class="social-btn"
                  :class="social.colorClass"
                  stack
                  flat
                  bordered
                >
                  <q-icon :name="social.icon" size="3em" class="q-mb-sm" />
                  <div class="text-subtitle2">{{ social.name }}</div>
                </q-btn>
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { useQuasar } from 'quasar';

const $q = useQuasar();

const openWhatsAppSupport = () => {
  const phone = '94776819034';
  const text = 'Hellow Nurya, I want to help for buy a product';
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
};

const copyEmail = () => {
  navigator.clipboard.writeText('Nuryajewellery@gmail.com');
  $q.notify({
    message: 'Email copied to clipboard!',
    color: 'positive',
    icon: 'check_circle',
    position: 'top'
  });
};

const socialLinks = [
  {
    name: 'Facebook',
    link: 'https://www.facebook.com/share/1Aar2Vdiiv/',
    icon: 'facebook',
    colorClass: 'text-blue-8'
  },
  {
    name: 'Instagram',
    link: 'https://www.instagram.com/nuryajewellery?igsh=MWd3MGk1OGM1b3A1dQ==',
    icon: 'camera_alt', // Fallback if ion-logo-instagram logic fails
    colorClass: 'text-purple-6'
  },
  {
    name: 'TikTok',
    link: 'https://www.tiktok.com/@nuryajewellery?_r=1&_t=ZS-92q2p41As0w',
    icon: 'music_note', // Fallback
    colorClass: 'tiktok-text'
  },
  {
    name: 'Pinterest',
    link: '#',
    icon: 'image', // Fallback
    colorClass: 'text-red-7'
  }
];
</script>

<style scoped>
.contact-page {
  min-height: 80vh;
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
}

body.body--dark .contact-page {
  background: linear-gradient(135deg, #121212 0%, #000000 100%);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}

.text-gold {
  color: #D4AF37;
}

.contact-card {
  border-radius: 20px;
  background: white;
  transition: transform 0.3s ease;
  height: 100%;
}

body.body--dark .contact-card {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.1);
}

.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.contact-item {
  border-radius: 12px;
  margin-bottom: 1rem;
}

.social-links-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.social-btn {
  padding: 2rem;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.social-btn:hover {
  background: rgba(0,0,0,0.03);
  transform: scale(1.02);
}

body.body--dark .social-btn:hover {
  background: rgba(255,255,255,0.05);
}

.tiktok-text {
  color: #000000;
}

body.body--dark .tiktok-text {
  color: #ffffff;
}
</style>
