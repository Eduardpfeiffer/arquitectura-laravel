<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { BriefcaseBusiness, Hammer, LogOut } from 'lucide-vue-next';

const page = usePage();
const menuItems = [
    { icon: BriefcaseBusiness, label: 'Portafolio', path: '/admin' },
    { icon: Hammer, label: 'Servicios', path: '/admin/servicios' },
];

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="flex min-h-screen bg-[#f4f1eb]">
        <aside class="flex w-60 shrink-0 flex-col border-r border-[#d7d1c7] bg-[#fbfaf7]">
            <div class="border-b border-[#d7d1c7] px-5 py-6">
                <span class="font-serif text-2xl leading-none text-[#24231f]">Nova Atelier</span>
            </div>
            <nav class="flex-1 px-3 py-4">
                <Link
                    v-for="item in menuItems"
                    :key="item.path"
                    :href="item.path"
                    :class="[
                        'mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors',
                        page.url === item.path ? 'bg-[#24231f] text-[#f4f1eb]' : 'text-[#4a4741] hover:bg-[#eee9e0]',
                    ]"
                >
                    <component :is="item.icon" :size="16" />
                    <span>{{ item.label }}</span>
                </Link>
            </nav>
            <div class="border-t border-[#d7d1c7] p-3">
                <div class="flex items-center gap-3 px-1 py-1">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[#d7d1c7] bg-white text-xs font-medium">
                        {{ page.props.auth.user.name?.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium leading-none text-[#24231f]">{{ page.props.auth.user.name }}</p>
                        <p class="mt-1.5 truncate text-xs text-[#8b8378]">{{ page.props.auth.user.email }}</p>
                    </div>
                    <button @click="logout" class="text-[#a8462f] transition hover:text-[#7b3524]" aria-label="Cerrar sesión">
                        <LogOut :size="16" />
                    </button>
                </div>
            </div>
        </aside>
        <main class="flex-1 overflow-y-auto">
            <slot />
        </main>
    </div>
</template>
