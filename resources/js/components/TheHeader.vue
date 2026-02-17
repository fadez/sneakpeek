<script setup></script>

<template>
    <header
        data-test="header"
        class="mb-4 border-b-2 border-zinc-200 bg-white dark:border-zinc-700 dark:bg-transparent"
    >
        <div class="container mx-auto max-w-4xl p-4">
            <div class="flex w-full flex-col items-center justify-between sm:flex-row">
                <div class="mb-2 flex items-center justify-between select-none sm:mb-0">
                    <RouterLink
                        to="/"
                        class="flex items-center rounded-md focus-visible:ring-2 focus-visible:outline-hidden dark:focus-visible:ring-white"
                    >
                        <img id="logo" src="/public/logo.svg" class="size-12" alt="SneakPeek Logo" />

                        <div class="p-1 font-brand text-xl font-medium dark:text-white">SneakPeek</div>
                    </RouterLink>
                </div>

                <div class="text-zinc-600 dark:text-zinc-300">Secure, one-time secret sharing made simple.</div>
            </div>
        </div>
    </header>
</template>
