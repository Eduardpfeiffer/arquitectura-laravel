<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import {
    ArrowDownRight,
    ArrowRight,
    ArrowUpRight,
    Check,
    ChevronLeft,
    ChevronRight,
    Instagram,
    Linkedin,
    MapPin,
    Menu,
    Minus,
    Phone,
    Play,
    Plus,
    Send,
    X,
} from 'lucide-vue-next';

const props = defineProps({
    services: { type: Array, default: () => [] },
    projects: { type: Array, default: () => [] },
});

const assets = {
    hero: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1800&q=80',
    services: 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1200&q=80',
    about: 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=1200&q=80',
    portfolio: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80',
};

const menuOpen = ref(false);
const serviceOpen = ref(null);
const projectIndex = ref(0);
const projectOpen = ref(null);
const formStep = ref(1);
const formSent = ref(false);
const isScrolled = ref(false);

const form = useForm({
    name: '',
    email: '',
    projectType: '',
    budgetRange: '',
    message: '',
    phone: '',
});

const services = computed(() => {
    if (!props.services.length) return [];
    return props.services.map((service) => ({
        title: service.title,
        eyebrow: service.eyebrow,
        items: service.items,
        description: service.description,
        image: service.image,
    }));
});

const projects = computed(() => {
    if (!props.projects.length) return [];
    return props.projects.map((project) => ({
        id: project.id,
        title: project.title,
        location: project.location,
        category: project.category,
        description: project.description,
        longDescription: project.long_description || project.description,
        image: project.cover_image || project.images[0] || assets.portfolio,
        gallery: project.images.length ? project.images : [project.cover_image || assets.portfolio],
        details: project.details.length ? project.details : [project.location, project.category],
    }));
});

const visibleProjects = computed(() => {
    if (!projects.value.length) return [];
    return projects.value.map((_, i) => projects.value[(projectIndex.value + i) % projects.value.length]);
});

function nextProject() {
    if (!projects.value.length) return;
    projectIndex.value = (projectIndex.value + 1) % projects.value.length;
}
function previousProject() {
    if (!projects.value.length) return;
    projectIndex.value = (projectIndex.value - 1 + projects.value.length) % projects.value.length;
}

function onScroll() {
    isScrolled.value = window.scrollY > 30;
}
onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }));
onUnmounted(() => window.removeEventListener('scroll', onScroll));

function handleContactSubmit() {
    if (formStep.value < 3) {
        formStep.value += 1;
        return;
    }
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            formSent.value = true;
        },
    });
}

function resetForm() {
    formSent.value = false;
    formStep.value = 1;
    form.reset();
}
</script>

<template>
    <Head title="Nova Atelier — Arquitectura y Diseño" />

    <main class="site-shell" id="inicio">
        <header :class="['site-header', isScrolled ? 'site-header-scrolled' : '']">
            <div class="header-inner">
                <a href="#inicio" class="brand" aria-label="Nova Atelier, inicio">
                    <span class="brand-copy"><strong>nova</strong><small>atelier</small></span>
                </a>
                <nav :class="['main-nav', menuOpen ? 'main-nav-open' : '']" aria-label="Navegación principal">
                    <a href="#inicio" @click="menuOpen = false">Home</a>
                    <a href="#servicios" @click="menuOpen = false">Servicios</a>
                    <a href="#nosotros" @click="menuOpen = false">Nosotros</a>
                    <a href="#portafolio" @click="menuOpen = false">Portafolio</a>
                    <a href="#contacto" @click="menuOpen = false">Contacto</a>
                    <a href="https://wa.me/573134674799" target="_blank" rel="noreferrer" class="nav-quote" @click="menuOpen = false">Cotizar <ArrowUpRight :size="15" /></a>
                </nav>
                <button class="mobile-menu" @click="menuOpen = !menuOpen" :aria-label="menuOpen ? 'Cerrar menú' : 'Abrir menú'" :aria-expanded="menuOpen">
                    <X v-if="menuOpen" :size="22" />
                    <Menu v-else :size="22" />
                </button>
            </div>
        </header>

        <section class="hero-section" aria-labelledby="hero-title">
            <img :src="assets.hero" alt="Interior contemporáneo con piedra, madera y luz natural" class="hero-image" />
            <div class="hero-overlay" />
            <div class="hero-content">
                <div class="hero-kicker"><span class="kicker-dot" /> Arquitectura · Diseño · Construcción</div>
                <h1 id="hero-title">Espacios que<br /><em>dicen quién eres.</em></h1>
                <p class="hero-subtitle">Diseñamos hogares con carácter, funcionalidad y belleza duradera.</p>
                <a href="https://wa.me/573134674799" target="_blank" rel="noreferrer" class="button button-light">Contacta un asesor <ArrowUpRight :size="17" /></a>
            </div>
            <div class="hero-footer-note"><span>01</span><span class="hero-line" /><span>Bogotá · Colombia</span></div>
            <a class="scroll-cue" href="#servicios" aria-label="Ver servicios"><span>Descubrir</span><ArrowDownRight :size="18" /></a>
        </section>

        <section class="proof-strip" aria-label="Razones para elegir Nova Atelier">
            <div><span class="proof-number">+12</span><span>Años creando<br />espacios con intención</span></div>
            <div><span class="proof-number">48</span><span>Proyectos<br />que siguen vivos</span></div>
            <div><span class="proof-number">01</span><span>Equipo que escucha<br />antes de diseñar</span></div>
            <div class="proof-statement">Tu proyecto merece<br /><strong>una mirada experta.</strong></div>
        </section>

        <section class="services-section section-pad" id="servicios" aria-labelledby="services-title">
            <div class="section-heading services-heading">
                <div class="section-label"><span>01</span><span class="section-label-line" /><span>Lo que hacemos</span></div>
                <h2 id="services-title">Diseño que se<br /><em>habita.</em></h2>
                <p>Desde una primera idea hasta la última llave, acompañamos cada decisión para que el resultado se sienta inevitablemente tuyo.</p>
            </div>
            <div class="services-layout">
                <div class="services-image-wrap"><img :src="assets.services" alt="Muestras de materiales y planos de arquitectura" /><div class="image-caption">Materia · Proporción · Luz</div></div>
                <div class="services-list">
                    <button v-for="(service, index) in services" :key="service.title" :class="['service-row', serviceOpen === index ? 'service-row-active' : '']" @click="serviceOpen = index" :aria-label="`Ver detalles de ${service.title}`">
                        <span class="service-number">{{ String(index + 1).padStart(2, '0') }}</span>
                        <span class="service-main"><span class="service-eyebrow">{{ service.eyebrow }}</span><strong>{{ service.title }}</strong><span class="service-items">{{ service.items.join('  ·  ') }}</span></span>
                        <span class="service-icon"><Minus v-if="serviceOpen === index" :size="19" /><Plus v-else :size="19" /></span>
                    </button>
                </div>
            </div>
        </section>

        <section class="about-section section-pad" id="nosotros" aria-labelledby="about-title">
            <div class="about-image-column"><img :src="assets.about" alt="Casa contemporánea con patio, vegetación y agua" /><div class="about-play"><Play :size="17" fill="currentColor" /><span>Conoce el estudio</span></div></div>
            <div class="about-copy">
                <div class="section-label"><span>02</span><span class="section-label-line" /><span>Quiénes somos</span></div>
                <h2 id="about-title">Diseñamos lo<br /><em>esencial.</em></h2>
                <p class="lead-copy">Nova Atelier nace de una convicción: un buen espacio no solo se ve bien, también te hace sentir bien.</p>
                <p>Somos un estudio de arquitectura y diseño que trabaja entre la sensibilidad y la precisión. Escuchamos cómo quieres vivir, traducimos tus necesidades a decisiones claras y construimos con materiales que envejecen con dignidad.</p>
                <a class="text-link" href="#contacto">Conoce nuestra forma de trabajar <ArrowRight :size="17" /></a>
            </div>
        </section>

        <section class="portfolio-section section-pad" id="portafolio" aria-labelledby="portfolio-title">
            <div class="portfolio-top">
                <div><div class="section-label"><span>03</span><span class="section-label-line" /><span>Proyectos seleccionados</span></div><h2 id="portfolio-title">Obras que<br /><em>permanecen.</em></h2></div>
                <div class="portfolio-controls"><span>0{{ projectIndex + 1 }} <small>/ 0{{ projects.length }}</small></span><button @click="previousProject" aria-label="Proyecto anterior"><ChevronLeft :size="20" /></button><button @click="nextProject" aria-label="Siguiente proyecto"><ChevronRight :size="20" /></button></div>
            </div>
            <div class="portfolio-carousel">
                <article v-for="(project, index) in visibleProjects" :key="`${project.title}-${index}`" :class="['project-card', index === 0 ? 'project-card-featured' : '']" @click="projectOpen = projects.findIndex((item) => item.title === project.title)">
                    <div class="project-image-wrap"><img :src="project.image" :alt="`${project.title}, ${project.category}`" /><span class="project-open"><ArrowUpRight :size="20" /></span></div>
                    <div class="project-meta"><span class="project-category">{{ project.category }}</span><h3>{{ project.title }}</h3><span class="project-location"><MapPin :size="13" /> {{ project.location }}</span><p>{{ project.description }}</p><span class="project-detail-link">Ver proyecto <ArrowRight :size="15" /></span></div>
                </article>
            </div>
        </section>

        <section class="partners-section">
            <div class="partners-intro"><div class="section-label"><span>04</span><span class="section-label-line" /><span>Con quienes construimos</span></div><p>Aliados que comparten nuestra obsesión por hacer las cosas bien.</p></div>
            <div class="partner-logos"><span>DEKTON</span><span>PORCELANOSA</span><span class="partner-serif">Hunter<br /><small>DOUGLAS</small></span><span>FV</span><span class="partner-light">maderame</span></div>
        </section>

        <section class="contact-section section-pad" id="contacto" aria-labelledby="contact-title">
            <div class="contact-intro">
                <div class="section-label"><span>05</span><span class="section-label-line" /><span>Hablemos</span></div>
                <h2 id="contact-title">Tu espacio<br /><em>empieza aquí.</em></h2>
                <p>Cuéntanos qué quieres transformar. Te responderemos con una primera lectura experta y sin compromiso.</p>
                <div class="contact-availability"><span class="status-dot" /> Agenda abierta para proyectos 2025</div>
            </div>
            <div class="contact-form-wrap">
                <div v-if="formSent" class="form-success">
                    <div class="success-mark"><Check :size="25" /></div>
                    <span class="form-kicker">Mensaje recibido</span>
                    <h3>Ya dimos el<br /><em>primer paso.</em></h3>
                    <p>Un asesor de Nova Atelier revisará tu proyecto y te contactará en menos de 24 horas.</p>
                    <button class="text-link" @click="resetForm">Enviar otra consulta <ArrowRight :size="16" /></button>
                </div>
                <form v-else @submit.prevent="handleContactSubmit">
                    <div class="form-header"><span>Solicita una asesoría</span><span>0{{ formStep }} <small>/ 03</small></span></div>
                    <div class="progress-bar"><span :style="{ width: `${(formStep / 3) * 100}%` }" /></div>

                    <div v-if="formStep === 1" class="form-step">
                        <label for="name">¿Cómo te llamamos?</label>
                        <input id="name" required placeholder="Tu nombre completo" autocomplete="name" v-model="form.name" />
                        <label for="email">¿Dónde te respondemos?</label>
                        <input id="email" type="email" required placeholder="tu@email.com" autocomplete="email" v-model="form.email" />
                        <button class="button button-dark" type="submit">Siguiente <ArrowRight :size="16" /></button>
                    </div>

                    <div v-else-if="formStep === 2" class="form-step">
                        <label>¿Qué quieres transformar?</label>
                        <div class="choice-grid">
                            <label v-for="option in ['Mi hogar', 'Un proyecto nuevo', 'Un espacio comercial', 'Aún no lo sé']" :key="option">
                                <input type="radio" name="project" required :checked="form.projectType === option" @change="form.projectType = option" /> <span>{{ option }}</span>
                            </label>
                        </div>
                        <label for="budget">Inversión estimada</label>
                        <select id="budget" v-model="form.budgetRange">
                            <option value="" disabled>Selecciona un rango</option>
                            <option>Hasta $25.000</option>
                            <option>$25.000 — $60.000</option>
                            <option>Más de $60.000</option>
                        </select>
                        <button class="button button-dark" type="submit">Continuar <ArrowRight :size="16" /></button>
                    </div>

                    <div v-else class="form-step">
                        <label for="message">Cuéntanos brevemente</label>
                        <textarea id="message" required placeholder="¿Qué te gustaría lograr en tu espacio?" rows="4" v-model="form.message" />
                        <label for="phone">Teléfono / WhatsApp</label>
                        <input id="phone" type="tel" required placeholder="+57 300 000 0000" v-model="form.phone" />
                        <button class="button button-dark" type="submit" :disabled="form.processing">{{ form.processing ? 'Enviando…' : 'Enviar consulta' }} <Send :size="15" /></button>
                        <span class="form-note">Tus datos están protegidos. Solo los usaremos para responder a tu solicitud.</span>
                    </div>
                </form>
            </div>
            <div class="contact-map-column">
                <div class="map-card">
                    <div class="map-grid" />
                    <div class="map-pin"><MapPin :size="23" fill="currentColor" /></div>
                    <div class="map-label">Nova Atelier<br /><small>Bogotá · Colombia</small></div>
                    <a href="https://maps.google.com/?q=Calle+72+%2374A-24+Local+101+Bogota+Colombia" target="_blank" rel="noreferrer" class="map-link">Abrir en Google Maps <ArrowUpRight :size="14" /></a>
                </div>
                <div class="contact-details">
                    <div><span>Visítanos</span><p>Calle 72 # 74A-24, Local 101<br />Bogotá, Colombia</p></div>
                    <div><span>Llámanos</span><p>+57 313 467 4799</p></div>
                    <div><span>Escríbenos</span><p>hola@novaatelier.com<br />proyectos@novaatelier.com</p></div>
                </div>
            </div>
        </section>

        <footer class="site-footer">
            <div class="footer-top"><span class="brand-copy"><strong>nova</strong><small>atelier</small></span><p>Arquitectura para vivir<br /><em>mejor.</em></p></div>
            <div class="footer-columns">
                <div><span class="footer-heading">Navegar</span><a href="#inicio">Inicio</a><a href="#servicios">Servicios</a><a href="#nosotros">Nosotros</a><a href="#portafolio">Portafolio</a><a href="#contacto">Contacto</a></div>
                <div><span class="footer-heading">Síguenos</span><a href="#instagram"><Instagram :size="15" /> Instagram</a><a href="#linkedin"><Linkedin :size="15" /> LinkedIn</a><a href="#whatsapp"><Phone :size="15" /> WhatsApp</a></div>
                <div><span class="footer-heading">Legal</span><a href="#privacidad">Política de privacidad</a><a href="#terminos">Términos y condiciones</a><a href="#cookies">Cookies</a></div>
            </div>
            <div class="footer-bottom"><span>© 2025 Nova Atelier. Todos los derechos reservados.</span><span>Desarrollado por <a href="https://hyperionlatam.com" target="_blank" rel="noreferrer"><strong>John Cajamarca</strong></a></span></div>
        </footer>

        <a class="whatsapp-float" href="https://wa.me/573134674799" target="_blank" rel="noreferrer" aria-label="Contactar por WhatsApp"><Phone :size="21" fill="currentColor" /><span class="whatsapp-pulse" /></a>

        <div v-if="serviceOpen !== null" class="modal-backdrop" @click="serviceOpen = null">
            <div class="detail-modal" @click.stop>
                <button class="modal-close" @click="serviceOpen = null" aria-label="Cerrar"><X :size="19" /></button>
                <img :src="services[serviceOpen].image || assets.services" :alt="services[serviceOpen].title" />
                <div class="modal-body">
                    <span class="form-kicker">{{ String(serviceOpen + 1).padStart(2, '0') }} · {{ services[serviceOpen].eyebrow }}</span>
                    <h2>{{ services[serviceOpen].title }}</h2>
                    <p>{{ services[serviceOpen].description }}</p>
                    <ul><li v-for="item in services[serviceOpen].items" :key="item"><Check :size="15" /> {{ item }}</li></ul>
                    <a href="#contacto" class="button button-dark" @click="serviceOpen = null">Quiero hablar con un asesor <ArrowUpRight :size="16" /></a>
                </div>
            </div>
        </div>

        <div v-if="projectOpen !== null" class="modal-backdrop" @click="projectOpen = null">
            <div class="project-modal" @click.stop>
                <button class="modal-close" @click="projectOpen = null" aria-label="Cerrar"><X :size="19" /></button>
                <img :src="projects[projectOpen].image" :alt="projects[projectOpen].title" />
                <div class="project-modal-body">
                    <span class="form-kicker">{{ projects[projectOpen].category }} · {{ projects[projectOpen].location }}</span>
                    <h2>{{ projects[projectOpen].title }}</h2>
                    <p>{{ projects[projectOpen].longDescription || projects[projectOpen].description }}</p>
                    <div class="project-facts"><span v-for="detail in projects[projectOpen].details" :key="detail">{{ detail }}</span></div>
                    <div v-if="projects[projectOpen].gallery && projects[projectOpen].gallery.length > 1" class="project-gallery">
                        <img v-for="(image, index) in projects[projectOpen].gallery.slice(0, 10)" :key="`${image}-${index}`" :src="image" :alt="`${projects[projectOpen].title} — vista ${index + 1}`" />
                    </div>
                    <a href="#contacto" class="text-link" @click="projectOpen = null">¿Tienes un proyecto similar? Hablemos <ArrowRight :size="16" /></a>
                </div>
            </div>
        </div>
    </main>
</template>
