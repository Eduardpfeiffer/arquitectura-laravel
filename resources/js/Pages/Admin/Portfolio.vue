<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff, ExternalLink, ImagePlus, Loader2, Pencil, Plus, Save, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    isAdmin: { type: Boolean, default: false },
    projects: { type: Array, default: () => [] },
});

const emptyProject = {
    title: '',
    location: '',
    category: '',
    description: '',
    longDescription: '',
    coverImage: '',
    images: '',
    details: '',
    published: true,
    sortOrder: 0,
};

const isEditorOpen = ref(false);
const editingId = ref(null);
const deletingId = ref(null);
const form = useForm({ ...emptyProject });

function separateLines(value) {
    return value.split('\n').map((item) => item.trim()).filter(Boolean);
}

function closeEditor() {
    isEditorOpen.value = false;
    editingId.value = null;
    form.reset();
}

function openEditor(project) {
    if (project) {
        editingId.value = project.id;
        form.defaults({
            title: project.title,
            location: project.location,
            category: project.category,
            description: project.description,
            longDescription: project.long_description || '',
            coverImage: project.cover_image || '',
            images: project.images.join('\n'),
            details: project.details.join('\n'),
            published: project.published,
            sortOrder: project.sort_order,
        }).reset();
    } else {
        editingId.value = null;
        form.defaults({ ...emptyProject, sortOrder: props.projects.length + 1 }).reset();
    }
    isEditorOpen.value = true;
}

function handleSave() {
    const images = separateLines(form.images);
    const details = separateLines(form.details);

    if (images.length > 10) {
        alert('Puedes registrar un máximo de 10 imágenes por proyecto.');
        return;
    }

    const payload = {
        title: form.title.trim(),
        location: form.location.trim(),
        category: form.category.trim(),
        description: form.description.trim(),
        longDescription: form.longDescription.trim() || null,
        coverImage: form.coverImage.trim(),
        images,
        details,
        published: form.published,
        sortOrder: Number(form.sortOrder) || 0,
    };

    Object.assign(form, payload);

    if (editingId.value) {
        form.put(`/admin/${editingId.value}`, { onSuccess: closeEditor, preserveScroll: true });
    } else {
        form.post('/admin', { onSuccess: closeEditor, preserveScroll: true });
    }
}

function deleteProject(project) {
    if (!confirm(`¿Eliminar "${project.title}"? Esta acción no se puede deshacer.`)) return;
    deletingId.value = project.id;
    router.delete(`/admin/${project.id}`, {
        preserveScroll: true,
        onFinish: () => (deletingId.value = null),
    });
}
</script>

<template>
    <Head title="Portafolio · Administración" />

    <AdminLayout>
        <section v-if="!isAdmin" class="mx-auto flex min-h-[70vh] max-w-xl flex-col justify-center px-6 text-center">
            <span class="mb-5 text-xs font-semibold uppercase tracking-[0.22em] text-[#bf6b4c]">Acceso restringido</span>
            <h1 class="font-serif text-5xl leading-none text-[#24231f]">Este panel es exclusivo para administradores.</h1>
            <p class="mx-auto mt-6 max-w-md text-sm leading-7 text-[#656158]">Tu cuenta inició sesión correctamente, pero no tiene permisos para administrar el portafolio.</p>
            <a href="/" class="mx-auto mt-8 inline-flex w-fit items-center gap-2 border border-[#24231f] px-5 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#24231f]">Volver al sitio <ExternalLink :size="15" /></a>
        </section>

        <div v-else class="mx-auto max-w-6xl px-2 py-5 sm:px-6 sm:py-9">
            <header class="flex flex-col justify-between gap-6 border-b border-[#d7d1c7] pb-8 md:flex-row md:items-end">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#bf6b4c]">Nova Atelier · Administración</span>
                    <h1 class="mt-3 font-serif text-5xl leading-[0.88] text-[#24231f] sm:text-6xl">Portafolio<br /><em class="text-[#bf6b4c]">de obras.</em></h1>
                    <p class="mt-5 max-w-xl text-sm leading-7 text-[#656158]">Publica proyectos, actualiza su información y controla el orden en que se muestran en la página pública.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="/" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 border border-[#cfc7ba] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#24231f] transition hover:border-[#24231f]">Ver sitio <ExternalLink :size="15" /></a>
                    <button @click="openEditor()" class="inline-flex items-center gap-2 bg-[#24231f] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c]"><Plus :size="16" /> Nuevo proyecto</button>
                </div>
            </header>

            <section class="mt-8">
                <div v-if="projects.length" class="grid gap-4">
                    <article v-for="project in projects" :key="project.id" class="grid gap-5 border border-[#ded8ce] bg-white p-4 shadow-[0_12px_35px_rgba(36,35,31,0.05)] md:grid-cols-[154px_1fr_auto] md:items-center md:p-5">
                        <div class="aspect-[4/3] overflow-hidden bg-[#e9e3da]">
                            <img v-if="project.cover_image" :src="project.cover_image" :alt="`Portada de ${project.title}`" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-[#9c9387]"><ImagePlus :size="28" /></div>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#8b8378]"><span>{{ project.category }}</span><span>·</span><span>{{ project.location }}</span><span>·</span><span>{{ project.images.length }}/10 fotos</span></div>
                            <h2 class="mt-2 font-serif text-3xl leading-none text-[#24231f]">{{ project.title }}</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-[#656158]">{{ project.description }}</p>
                        </div>
                        <div class="flex items-center gap-2 md:justify-self-end">
                            <span :title="project.published ? 'Visible en el sitio' : 'Oculto del sitio'" :class="['inline-flex h-9 w-9 items-center justify-center', project.published ? 'text-[#487557]' : 'text-[#9c9387]']">
                                <Eye v-if="project.published" :size="18" /><EyeOff v-else :size="18" />
                            </span>
                            <button @click="openEditor(project)" class="inline-flex h-10 w-10 items-center justify-center border border-[#d7d1c7] text-[#24231f] transition hover:border-[#24231f] hover:bg-[#f4f1eb]" :aria-label="`Editar ${project.title}`"><Pencil :size="16" /></button>
                            <button @click="deleteProject(project)" :disabled="deletingId === project.id" class="inline-flex h-10 w-10 items-center justify-center border border-[#e2b3a4] text-[#a8462f] transition hover:bg-[#fff1ec] disabled:opacity-50" :aria-label="`Eliminar ${project.title}`"><Trash2 :size="16" /></button>
                        </div>
                    </article>
                </div>
                <div v-else class="border border-dashed border-[#cfc7ba] bg-[#fbf9f5] px-6 py-14 text-center">
                    <ImagePlus class="mx-auto h-8 w-8 text-[#bf6b4c]" />
                    <h2 class="mt-5 font-serif text-4xl leading-none text-[#24231f]">Tu portafolio empieza aquí.</h2>
                    <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-[#656158]">Crea tu primer proyecto para reemplazar el contenido de muestra que se muestra en el sitio público.</p>
                    <button @click="openEditor()" class="mt-7 inline-flex items-center gap-2 bg-[#24231f] px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c]"><Plus :size="16" /> Crear primer proyecto</button>
                </div>
            </section>
        </div>

        <div v-if="isEditorOpen && isAdmin" class="fixed inset-0 z-[70] overflow-y-auto bg-[#24231f]/65 px-4 py-6 backdrop-blur-sm sm:px-8" role="dialog" aria-modal="true" aria-labelledby="project-editor-title">
            <div class="mx-auto max-w-3xl bg-[#f9f7f2] shadow-2xl">
                <div class="flex items-start justify-between border-b border-[#d7d1c7] px-6 py-5 sm:px-8">
                    <div><span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#bf6b4c]">Portafolio</span><h2 id="project-editor-title" class="mt-2 font-serif text-4xl leading-none text-[#24231f]">{{ editingId ? 'Editar obra' : 'Nueva obra' }}</h2></div>
                    <button @click="closeEditor" class="inline-flex h-10 w-10 items-center justify-center text-[#24231f] transition hover:bg-[#eee9e0]" aria-label="Cerrar editor"><X :size="20" /></button>
                </div>
                <div class="grid gap-5 px-6 py-6 sm:grid-cols-2 sm:px-8">
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Título de la obra
                        <input v-model="form.title" placeholder="Ej. Casa Nido" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                        <span v-if="form.errors.title" class="text-[#a8462f] normal-case">{{ form.errors.title }}</span>
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Ubicación
                        <input v-model="form.location" placeholder="Ej. Chapinero · Bogotá" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Categoría
                        <input v-model="form.category" placeholder="Ej. Remodelación integral" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54]">Orden de visualización
                        <input type="number" min="0" v-model.number="form.sortOrder" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Descripción breve
                        <textarea rows="3" v-model="form.description" placeholder="Resume el proyecto en pocas líneas…" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Descripción ampliada
                        <textarea rows="5" v-model="form.longDescription" placeholder="Cuenta el alcance, los materiales, el reto y el resultado del proyecto…" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">URL de imagen de portada
                        <input type="url" v-model="form.coverImage" placeholder="https://…" class="w-full border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                        <span v-if="form.errors.coverImage" class="text-[#a8462f] normal-case">{{ form.errors.coverImage }}</span>
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Galería de imágenes (una URL por línea, máximo 10)
                        <textarea rows="5" v-model="form.images" placeholder="https://…" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="grid gap-2 text-xs font-bold uppercase tracking-[0.1em] text-[#625d54] sm:col-span-2">Datos destacados (uno por línea, máximo 8)
                        <textarea rows="3" v-model="form.details" placeholder="240 m² intervenidos" class="w-full resize-y border border-[#d7d1c7] bg-white px-3 py-3 text-sm font-normal normal-case tracking-normal text-[#24231f] outline-none focus:border-[#bf6b4c]" />
                    </label>
                    <label class="flex items-center gap-3 text-sm font-medium text-[#24231f] sm:col-span-2">
                        <input type="checkbox" v-model="form.published" class="h-4 w-4 accent-[#bf6b4c]" /> Publicar esta obra en el sitio público
                    </label>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-[#d7d1c7] px-6 py-5 sm:flex-row sm:justify-end sm:px-8">
                    <button @click="closeEditor" :disabled="form.processing" class="px-4 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#656158]">Cancelar</button>
                    <button @click="handleSave" :disabled="form.processing" class="inline-flex items-center justify-center gap-2 bg-[#24231f] px-5 py-3 text-xs font-bold uppercase tracking-[0.12em] text-[#f4f1eb] transition hover:bg-[#bf6b4c] disabled:cursor-not-allowed disabled:opacity-60">
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" /><Save v-else :size="15" />{{ editingId ? 'Guardar cambios' : 'Crear proyecto' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
