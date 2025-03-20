<template>
    <div class="container mx-auto p-8">
        <h1 class="text-xl font-bold mb-4">Join a Team or Create a New One</h1>

        <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <!-- Search for a Team -->
            <div class="mb-4">
                <label class="block text-gray-700">Search for a Team</label>
                <input
                    type="text"
                    v-model="searchQuery"
                    @input="searchTeams"
                    placeholder="Type team name..."
                    class="w-full p-2 border rounded-md"
                />
                <ul v-if="teams.length" class="border mt-2 rounded-md bg-white shadow-sm">
                    <li v-for="team in teams" :key="team.id" @click="selectTeam(team)"
                        class="p-2 cursor-pointer hover:bg-gray-100">
                        {{ team.name }}
                    </li>
                </ul>
            </div>

            <div v-if="selectedTeam" class="mb-4 p-2 bg-green-100 rounded-md">
                <p>Selected Team: <strong>{{ selectedTeam.name }}</strong></p>
            </div>

            <div v-else class="mb-4">
                <label class="block text-gray-700">Create a New Team</label>
                <input v-model="newTeamName" type="text" placeholder="Enter new team name"
                    class="w-full p-2 border rounded-md" />
            </div>

            <!-- Personal Details -->
            <div class="mb-4">
                <label class="block text-gray-700">Full Name</label>
                <input v-model="member.full_name" type="text" required class="w-full p-2 border rounded-md" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input v-model="member.email" type="email" required class="w-full p-2 border rounded-md" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Phone Number</label>
                <input v-model="member.phone_number" type="text" required class="w-full p-2 border rounded-md" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Institution</label>
                <input v-model="member.institution_name" type="text" required class="w-full p-2 border rounded-md" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Department & Level</label>
                <input v-model="member.department_level" type="text" required class="w-full p-2 border rounded-md" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Matric Number</label>
                <input v-model="member.matric_number" type="text" required class="w-full p-2 border rounded-md" />
            </div>

            <!-- Upload Proof of Undergraduate Status -->
            <div class="mb-4">
                <label class="block text-gray-700">Upload Proof of Undergraduate Status</label>
                <input type="file" @change="handleFileUpload('proof_document', $event)" required class="w-full p-2 border rounded-md" />
                <p v-if="proofDocument" class="text-sm text-gray-600">Selected: {{ proofDocument.name }}</p>
            </div>

            <!-- Policy Proposal Submission -->
            <h2 class="text-lg font-semibold mb-2">Your Vision for National Unity – Submit Your Proposal!</h2>
            <p class="text-sm text-gray-700 mb-2">
                Upload your team’s proposal (2,000 – 4,000 words) in PDF or DOCX format.
            </p>
            <div class="mb-4">
                <label class="block text-gray-700">Upload Policy Proposal</label>
                <input type="file" @change="handleFileUpload('policy_proposal', $event)" accept=".pdf, .docx" required class="w-full p-2 border rounded-md" />
                <p v-if="policyProposal" class="text-sm text-gray-600">Selected: {{ policyProposal.name }}</p>
            </div>

            <!-- Declaration -->
            <h2 class="text-lg font-semibold mb-2"> Declaration</h2>
            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" v-model="declarationConfirmed" required class="mr-2" />
                    <span class="text-gray-700 text-sm">
                        I confirm that all information provided is accurate and that I/we meet the eligibility criteria.
                    </span>
                </label>
            </div>

            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" v-model="acknowledgeEvaluation" required class="mr-2" />
                    <span class="text-gray-700 text-sm">
                        I acknowledge that my team’s submission will be evaluated.
                    </span>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full">Submit</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const searchQuery = ref("");
const teams = ref([]);
const selectedTeam = ref(null);
const newTeamName = ref("");
const member = ref({
    full_name: "",
    email: "",
    phone_number: "",
    institution_name: "",
    department_level: "",
    matric_number: "",
});
const proofDocument = ref(null);
const documentPath = ref(null);
const declarationConfirmed = ref(false);
const acknowledgeEvaluation = ref(false);

const searchTeams = async () => {
    if (searchQuery.value.length > 2) {
        let { data } = await axios.get(`/teams/search?q=${searchQuery.value}`);
        teams.value = data;
    } else {
        teams.value = [];
    }
};

const selectTeam = (team) => {
    selectedTeam.value = team;
    searchQuery.value = team.name;
    teams.value = [];
};

const handleFileUpload = (type, event) => {
    if (type === 'proof_document') {
        proofDocument.value = event.target.files[0];
    } else if (type === 'document_path') {
        policyProposal.value = event.target.files[0];
    }
};

const submitForm = async () => {
    try {
        let formData = new FormData();
        formData.append("team_id", selectedTeam.value ? selectedTeam.value.id : "");
        formData.append("new_team_name", newTeamName.value);
        formData.append("full_name", member.value.full_name);
        formData.append("email", member.value.email);
        formData.append("phone_number", member.value.phone_number);
        formData.append("institution_name", member.value.institution_name);
        formData.append("department_level", member.value.department_level);
        formData.append("matric_number", member.value.matric_number);
        formData.append("proof_document", proofDocument.value);
        formData.append("document_path", documentPath.value);
        formData.append("declaration", "1");

        await axios.post("/competition", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        alert("Application submitted successfully!");
    } catch (error) {
        console.error("Error submitting form:", error);
        alert("Failed to submit application. Please try again.");
    }
};
</script>
