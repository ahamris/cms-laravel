@push('vite')
    @vite(['resources/js/admin/image-optimizer.js'])
@endpush
<script>
    window.IMAGE_OPTIMIZER_DATA = {
        streamUrl: @json(route('admin.image-optimizer.stream')),
        translations: {
            optimizing: @json(__('admin.image_optimizer.optimizing')),
            start_optimization: @json(__('admin.image_optimizer.start_optimization')),
            output_log: @json(__('admin.image_optimizer.output_log')),
            processing: @json(__('admin.image_optimizer.processing')),
            statistics: @json(__('admin.image_optimizer.statistics')),
            jpeg_files: @json(__('admin.image_optimizer.jpeg_files')),
            png_files: @json(__('admin.image_optimizer.png_files')),
            webp_files: @json(__('admin.image_optimizer.webp_files')),
            errors: @json(__('admin.image_optimizer.errors')),
            saved: @json(__('admin.image_optimizer.saved')),
            total_savings: @json(__('admin.image_optimizer.total_savings')),
            original_size: @json(__('admin.image_optimizer.original_size')),
            optimized_size: @json(__('admin.image_optimizer.optimized_size')),
            optimization_complete: @json(__('admin.image_optimizer.optimization_complete'))
        }
    };
</script>
<x-layouts.admin title="{{ __('admin.image_optimizer.title') }}">
<div id="image-optimizer-app" class="container mx-auto px-4 py-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.image_optimizer.title') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('admin.image_optimizer.description') }}</p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-semibold text-gray-900">{{ __('admin.image_optimizer.optimize_images') }}</h6>
        </div>

        <div class="p-6">
            {{-- Info Section --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>{{ __('admin.image_optimizer.warning') }}:</strong>
                            {{ __('admin.image_optimizer.warning_message') }}
                        </p>
                        <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                            <li>{{ __('admin.image_optimizer.optimizes_jpeg') }}</li>
                            <li>{{ __('admin.image_optimizer.optimizes_png') }}</li>
                            <li>{{ __('admin.image_optimizer.creates_webp') }}</li>
                            <li>{{ __('admin.image_optimizer.overwrites_originals') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Vue Component (mounted by resources/js/admin/image-optimizer.js) --}}
            <image-optimizer-component></image-optimizer-component>
        </div>
    </div>
</div>

<style>
/* Toggle styling */
input:checked ~ .dot {
    transform: translateX(100%);
    background-color: #48bb78; /* green-400 */
}
input:checked ~ .block {
    background-color: #2f855a; /* green-700 */
}
</style>
</x-layouts.admin>

