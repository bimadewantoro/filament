<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {FwbCard, FwbDropdown, FwbListGroup, FwbListGroupItem} from "flowbite-vue";

defineProps({
    courses: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Home
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <h1 class="text-3xl mb-3">
                        Courses
                    </h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        {{courses}}
                        <fwb-card v-for="course in courses"
                                  :key="course.id"
                                  :img-src="'/storage/' + course.media.path"
                                  :img-alt="course.media.alt"
                        >
                        <div class="p-5">
                                <h5 class="mb-2 flex justify-between text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ course?.title }}
                                    <div>
                                        <fwb-dropdown v-if="$page.props.auth.user">
                                            <template #trigger>
                                                <span>
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </span>
                                            </template>
                                            <fwb-list-group>
                                                <fwb-list-group-item>
                                                    <a href="#">
                                                        Edit Course
                                                    </a>
                                                </fwb-list-group-item>
                                                <fwb-list-group-item class="bg-red-500 border-red-500 text-gray-100">
                                                    <a href="#">
                                                        Delete Course
                                                    </a>
                                                </fwb-list-group-item>
                                            </fwb-list-group>
                                        </fwb-dropdown>
                                    </div>
                                </h5>
                                <p class="font-normal text-gray-900 dark:text-gray-400">
                                    {{ course?.excerpt }}
                                </p>
                            </div>
                        </fwb-card>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
