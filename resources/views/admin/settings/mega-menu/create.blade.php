<x-layouts.admin title="Create Mega Menu Item">
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Mega Menu Item</h1>
            <p class="text-gray-600 mt-1">Add a new parent item to the mega menu</p>
        </div>
        <a href="{{ route('admin.settings.mega-menu.index') }}" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Info Box -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Creating a Parent Menu Item</p>
                <p>After creating this item, you can add children by editing it or using the index page. You can also fetch children from modules automatically.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    Item Details
                </h2>
            </div>
            
            <form action="{{ route('admin.settings.mega-menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    @include('admin.settings.mega-menu.form')
                </div>
                
                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                        Tip: Children can be added after creation
                    </p>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.settings.mega-menu.index') }}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            <i class="fas fa-save mr-2"></i>Create Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.admin>
