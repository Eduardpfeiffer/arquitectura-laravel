<script setup>
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar sesión" />

    <div class="login-shell">
        <div class="login-card">
            <span class="login-kicker">Nova Atelier · Administración</span>
            <h1 class="login-title">Inicia<br /><em>sesión.</em></h1>
            <p class="login-copy">Accede para administrar el portafolio y los servicios del sitio.</p>

            <div v-if="status" class="login-status">{{ status }}</div>

            <form @submit.prevent="submit" class="login-form">
                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="tu@email.com"
                />
                <div v-if="form.errors.email" class="login-error">{{ form.errors.email }}</div>

                <label for="password">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <div v-if="form.errors.password" class="login-error">{{ form.errors.password }}</div>

                <label class="login-remember">
                    <input type="checkbox" v-model="form.remember" />
                    <span>Recordarme</span>
                </label>

                <button type="submit" class="button button-dark" :disabled="form.processing">
                    {{ form.processing ? 'Ingresando…' : 'Iniciar sesión' }}
                </button>
            </form>
        </div>
    </div>
</template>
