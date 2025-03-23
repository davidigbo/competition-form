<template>
    <div class="container mx-auto p-8">
        <h1 class="text-xl font-bold mb-4">Competition Submissions</h1>

        <div v-if="loading" class="text-center text-gray-500">Loading submissions...</div>
        <div v-else-if="submissions.length === 0" class="text-center text-gray-500">No submissions found.</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="submission in submissions" :key="submission.id" class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold">{{ submission.full_name }}</h2>
                <p class="text-gray-700"><strong>Team:</strong> {{ submission.team_name || 'N/A' }}</p>
                <p class="text-gray-700"><strong>Email:</strong> {{ submission.email }}</p>
                <p class="text-gray-700"><strong>Phone:</strong> {{ submission.phone_number }}</p>
                <p class="text-gray-700"><strong>Institution:</strong> {{ submission.institution_name }}</p>
                <p class="text-gray-700"><strong>Department & Level:</strong> {{ submission.department_level }}</p>
                <p class="text-gray-700"><strong>Matric Number:</strong> {{ submission.matric_number }}</p>
                
                <div class="mt-4">
                    <a :href="submission.proof_document" target="_blank" class="text-blue-500 hover:underline">View Proof Document</a>
                </div>
                <div class="mt-2">
                    <a :href="submission.document_path" target="_blank" class="text-blue-500 hover:underline">View Proposal</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const submissions = ref([]);
const loading = ref(true);

const fetchSubmissions = async () => {
    try {
        const { data } = await axios.get('/competition');
        submissions.value = data;
    } catch (error) {
        console.error("Error fetching submissions:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchSubmissions);
</script>
