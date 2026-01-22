<template>
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 pt-3 border-t border-gray-200">
        <span class="text-sm font-medium text-gray-700">Filter Periode:</span>
        <div class="flex items-center gap-2 flex-wrap">
            <select
                :value="modelValue.month"
                @change="$emit('update:modelValue', { ...modelValue, month: $event.target.value })"
                class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-green-500 focus:border-green-500"
            >
                <option value="">Semua Bulan</option>
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <select
                :value="modelValue.year"
                @change="$emit('update:modelValue', { ...modelValue, year: $event.target.value })"
                class="border border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-green-500 focus:border-green-500"
            >
                <option value="">Semua Tahun</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>
            <button
                @click="$emit('apply')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm font-medium"
            >
                Terapkan
            </button>
            <button
                @click="$emit('reset')"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1.5 rounded-md text-sm font-medium"
            >
                Reset
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    modelValue: {
        type: Object,
        required: true
    }
});

defineEmits(['update:modelValue', 'apply', 'reset']);
</script>
