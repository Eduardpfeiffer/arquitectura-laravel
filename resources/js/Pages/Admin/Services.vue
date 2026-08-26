<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff, ExternalLink, ImagePlus, Loader2, Pencil, Plus, Save, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    isAdmin: { type: Boolean, default: false },
    services: { type: Array, default: () => [] },
});

const emptyService = {
    title: '',
    eyebrow: '',
    items: '',
    description: '',
    image: '',
    published: true,
    sortOrder: 0,
};

const isEditorOpen = ref(false);
const editingId = ref(null);
const deletingId = ref(null);
const form = useForm({ ...emptyService });

function separateLines(value) {
    return value.split('\n').map((item) => item.trim()).filter(Boolean);
}

function closeEditor() {
    isEditorOpen.value = false;
    editingId.value = null;
    form.reset();
}

function openEditor(service) {
    if (service) {
        editingId.value = service.id;
        form.defaults({
            title: service.title,
            eyebrow: service.eyebrow,
            items: service.items.join('\n'),
            description: service.description,
            image: service.image || '',
            published: service.published,
            sortOrder: service.sort_order,
        }).reset();
    } else {
        editingId.value = null;
        form.defaults({ ...emptyService, sortOrder: props.services.length + 1 }).reset();
    }
    isEditorOpen.value = true;
}

function handleSave() {
    const items = separateLines(form.items);

    if (items.length > 10) {
        alert('Puedes registrar un máximo de 10 elementos por servicio.');
        return;
    }

    Object.assign(form, {
        title: form.title.trim(),
        eyebrow: form.eyebrow.trim(),
        items,
        description: form.description.trim(),
        image: form.image.trim() || null,
        published: form.published,
        sortOrder: Number(form.sortOrder) || 0,
    });

    if (editingId.value) {
        form.put(`/admin/servicios/${editingId.value}`, { onSuccess: closeEditor, preserveScroll: true });
    } else {
        form.post('/admin/servicios', { onSuccess: closeEditor, preserveScroll: true });
    }
}

function deleteService(service) {
    if (!confirm(`¿Eliminar "${service.title}"? Esta acción no se puede deshacer.`)) return;
    deletingId.value = service.id;
    router.delete(`/admin/servicios/${service.id}`, {
        preserveScroll: true,
        onFinish: () => (deletingId.value = null),
    });
}
</script>

<template>
    <Head title="Servicios · Administración" />

    <AdminLayout>
        <section v-if="!isAdmin" class="mx-auto flex min-h-[70vh] max-w-xl flex-col justify-center px-6 text-center">
            <span class="mb-5 text-xs font-semibold uppercase tracking-[0.22em] text-[#bf6b4c]">Acceso restringido</span>
            <h1 class="font-serif text-5xl leading-none text-[#24231f]">Este panel es exclusivo para administradores.</h1>
            <p class="mx-auto mt-6 max-w-md text-sm leading-7 text-[#656158]">Tu cuenta inició sesión correctamente, pero no tiene permisos para administrar los servicios.</p>
            <a href="/" class="mx-auto mt-8 inline-flex w-fit items-center gap-2 border border-[#24231f] px-5 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#24231f]">Volver al sitio <ExternalLink :size="15" /></a>
        </section>

        <div v-else class="mx-auto max-w-6xl px-2 py-5 sm:px-6 sm:py-9">
            <header class="flex flex-col justify-between gap-6 border-b border-[#d7d1c7] pb-8 md:flex-row md:items-end">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#bf6b4c]">Nova Atelier · Administración</span>
                    <h1 class="mt-3 font-serif text-5xl leading-[0.88] text-[#24231f] sm:text-6xl">Lo que<br /><em class="text-[#bf6b4c]">hacemos.</em></h1>
                    <p class="mt-5 max-w-xl text-sm leading-7 text-[#656158]">Publica servicios, actualiza su información e imagen, y controla el orden en que se muestran en la página pública.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="/#servicios" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 border border-[#cfc7ba] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#24231f] transition hover:border-[#24231f]">Ver sitio <ExternalLink :size="15" /></a>
                    <button @click="openEditor()" class="inline-flex items-center gap-2 bg-[#24231f] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c]"><Plus :size="16" /> Nuevo servicio</button>
                </div>
            </header>

            <section class="mt-8">
                <div v-if="services.length" class="grid gap-4">
                    <article v-for="(service, index) in services" :key="service.id" class="grid gap-5 border border-[#ded8ce] bg-white p-4 shadow-[0_12px_35px_rgba(36,35,31,0.05)] md:grid-cols-[154px_1fr_auto] md:items-center md:p-5">
                        <div class="aspect-[4/3] overflow-hidden bg-[#e9e3da]">
                            <img v-if="service.image" :src="service.image" :alt="`Imagen de ${service.title}`" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-[#9c9387]"><ImagePlus :size="28" /></div>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#8b8378]"><span>{{ String(index + 1).padStart(2, '0') }}</span><span>·</span><span>{{ service.eyebrow }}</span></div>
                            <h2 class="mt-2 font-serif text-3xl leading-none text-[#24231f]">{{ service.title }}</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#656158]">{{ service.description }}</p>
                            <p v-if="service.items.length" class="mt-2 text-xs text-[#8b8378]">{{ service.items.join('  ·  ') }}</p>
                        </div>
                        <div class="flex items-center gap-2 md:justify-self-end">
                            <span :title="service.published ? 'Visible en el sitio' : 'Oculto del sitio'" :class="['inline-flex h-9 w-9 items-center justify-center', service.published ? 'text-[#487557]' : 'text-[#9c9387]']">
                                <Eye v-if="service.published" :size="18" /><EyeOff v-else :size="18" />
                            </span>
                            <button @click="openEditor(service)" class="inline-flex h-10 w-10 items-center justify-center border border-[#d7d1c7] text-[#24231f] transition hover:border-[#24231f] hover:bg-[#f4f1eb]" :aria-label="`Editar ${service.title}`"><Pencil :size="16" /></button>
                            <button @click="deleteService(service)" :disabled="deletingId === service.id" class="inline-flex h-10 w-10 items-center justify-center border border-[#e2b3a4] text-[#a8462f] transition hover:bg-[#fff1ec] disabled:opacity-50" :aria-label="`Eliminar ${service.title}`"><Trash2 :size="16" /></button>
                        </div>
                    </article>
                </div>
                <div v-else class="border border-dashed border-[#cfc7ba] bg-[#fbf9f5] px-6 py-14 text-center">
                    <ImagePlus class="mx-auto h-8 w-8 text-[#bf6b4c]" />
                    <h2 class="mt-5 font-serif text-4xl leading-none text-[#24231f]">Tus servicios empiezan aquí.</h2>
                    <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-[#656158]">Crea tu primer servicio para reemplazar el contenido de muestra que se muestra en el sitio público.</p>
                    <button @click="openEditor()" class="mt-7 inline-flex items-center gap-2 bg-[#24231f] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c]"><Plus :size="16" /> Crear primer servicio</button>
                </div>
            </section>
        </div>

        <div v-if="isEditorOpen && isAdmin" class="fixed inset-0 z-[70] overflow-y-auto bg-[#24231f]/65 px-4 py-6 backdrop-blur-sm sm:px-8" role="dialog" aria-modal="true" aria-labelledby="service-editor-title">
            <div class="mx-auto max-w-3xl bg-[#f9f7f2] shadow-2xl">
                <div class="flex items-start justify-between border-b border-[#d7d1c7] px-6 py-5 sm:px-8">
                    <div><span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#bf6b4c]">Servicios</span><h2 id="service-editor-title" class="mt-2 font-serif text-4xl leading-none text-[#24231f]">{{ editingId ? 'Editar servicio' : 'Nuevo servicio' }}</h2></div>
                    <button @click="closeEditor" class="inline-flex h-10 w-10 items-center justify-center text-[#24231f] transition hover:bg-[#eee9e0]" aria-label="Cerrar editor"><X :size="20" /></button>
                </div>
                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2 sm:px-8">
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Título del servicio
                        <input v-model="form.title" placeholder="Ej. Diseño" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Frase breve (eyebrow)
                        <input v-model="form.eyebrow" placeholder="Ej. Espacios con intención" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Orden de visualización
                        <input type="number" min="0" v-model.number="form.sortOrder" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">URL de imagen
                        <input type="url" v-model="form.image" placeholder="https://…" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Descripción
                        <textarea rows="4" v-model="form.description" placeholder="Describe el servicio…" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Elementos incluidos (uno por línea, máximo 10)
                        <textarea rows="3" v-model="form.items" placeholder="Interiorismo residencial" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="flex items-center gap-3 text-sm font-medium text-[#24231f] sm:col-span-2">
                        <input type="checkbox" v-model="form.published" class="h-4 w-4 accent-[#bf6b4c]" /> Publicar este servicio en el sitio público
                    </label>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-[#d7d1c7] px-6 py-5 sm:flex-row sm:justify-end sm:px-8">
                    <button @click="closeEditor" :disabled="form.processing" class="px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#656158]">Cancelar</button>
                    <button @click="handleSave" :disabled="form.processing" class="inline-flex items-center justify-center gap-2 bg-[#24231f] px-5 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c] disabled:cursor-not-allowed disabled:opacity-60">
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" /><Save v-else :size="15" />{{ editingId ? 'Guardar cambios' : 'Crear servicio' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
