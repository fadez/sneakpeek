<script setup></script>

<template>
    <header
        class="bg-white dark:bg-transparent border-b-2 border-zinc-200 dark:border-zinc-700 mb-4"
    >
        <div class="container max-w-3xl mx-auto p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between w-full">
                <div class="flex items-center justify-between mb-2 sm:mb-0 select-none">
                    <RouterLink
                        to="/"
                        class="flex items-center rounded-md focus-visible:ring-2 dark:focus-visible:ring-white focus-visible:outline-hidden"
                    >
                        <img
                            id="logo"
                            src="/public/logo.svg"
                            class="size-12"
                            alt="SneakPeek Logo"
                        />

                        <div class="p-1 font-brand text-xl font-medium dark:text-white">
                            SneakPeek
                        </div>
                    </RouterLink>
                </div>

                <div class="text-zinc-600 dark:text-zinc-300">
                    Secure, one-time secret sharing made simple.
                </div>
            </div>
        </div>
    </header>
</template>
