<x-layouts.admin title="Edit Mega Menu Item">
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Mega Menu Item</h1>
            <p class="text-gray-600 mt-1">Update parent details and manage children</p>
        </div>
        <a href="{{ route('admin.settings.mega-menu.index') }}" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Parent Details -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit text-primary mr-2"></i>
                    Parent Item Details
                </h2>
            </div>
            
            <form action="{{ route('admin.settings.mega-menu.update', $megaMenu) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6">
                    @include('admin.settings.mega-menu.form')
                </div>
                
                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                    <a href="{{ route('admin.settings.mega-menu.index') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                        <i class="fas fa-save mr-2"></i>Update Parent
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Sub-Items Manager -->
        <div class="bg-white rounded-lg shadow" x-data="subItemManager()">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list text-primary mr-2"></i>
                        Sub-Items
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $megaMenu->children->count() }} {{ Str::plural('item', $megaMenu->children->count()) }}</p>
                </div>
                <button @click="showAddModal = true" 
                        class="px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary/90">
                    <i class="fas fa-plus mr-2"></i>Add Sub-Item
                </button>
            </div>

            <div class="p-6">
                @if($megaMenu->children->count() > 0)
                    <div class="space-y-3">
                        @foreach($megaMenu->children as $child)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-white transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    @if($child->icon)
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" 
                                         style="background-color: {{ $child->icon_bg_color ?? '#3B82F6' }}">
                                        <i class="{{ $child->icon }} text-white text-sm"></i>
                                    </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $child->title }}</h4>
                                        @if($child->subtitle)
                                        <p class="text-sm text-gray-600">{{ $child->subtitle }}</p>
                                        @endif
                                        <div class="flex items-center mt-1 space-x-2 text-xs">
                                            <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ $child->url }}</span>
                                            @if(!$child->is_active)
                                            <span class="px-2 py-1 bg-gray-200 text-gray-600 rounded">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="editSubItem({{ $child->id }})" 
                                            class="px-3 py-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.settings.mega-menu.remove-sub-item', [$megaMenu, $child]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to remove this sub-item?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-4">No sub-items yet</p>
                        <button @click="showAddModal = true" 
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            <i class="fas fa-plus mr-2"></i>Add First Sub-Item
                        </button>
                    </div>
                @endif
            </div>

            <!-- Add Sub-Item Modal -->
            <div x-show="showAddModal" 
                 x-cloak
                 @click.away="showAddModal = false"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-black opacity-50"></div>
                    
                    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Add Sub-Item</h3>
                            <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.settings.mega-menu.add-sub-item', $megaMenu) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="title"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="e.g., CRM">
                            </div>

                            <!-- Subtitle -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Subtitle
                                </label>
                                <input type="text" 
                                       name="subtitle"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="e.g., Customer relationship management">
                            </div>

                            <!-- Link Type Selection -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Link Type</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="link_type" value="predefined" 
                                               x-model="addLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">Predefined Route</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="link_type" value="system" 
                                               x-model="addLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">System Content</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="link_type" value="custom" 
                                               x-model="addLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">Custom URL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Predefined Routes Dropdown -->
                            <div x-show="addLinkType === 'predefined'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Select Route</label>
                                <select name="predefined_route" x-model="addSelectedRoute" @change="updateAddUrl()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Choose a page...</option>
                                    @if(isset($availableRoutes))
                                        @foreach($availableRoutes as $name => $url)
                                            <option value="{{ $url }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- System Content Dropdown -->
                            <div x-show="addLinkType === 'system'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Select Content</label>
                                <select name="system_content" x-model="addSelectedSystemContent" @change="updateAddUrl()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Choose content...</option>
                                    @if(isset($systemContent))
                                        @foreach($systemContent as $category => $items)
                                            <optgroup label="{{ $category }}">
                                                @foreach($items as $item)
                                                    <option value="{{ $item['url'] }}">{{ $item['title'] }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Custom URL Input -->
                            <div x-show="addLinkType === 'custom'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Custom URL</label>
                                <input type="text" name="custom_url" x-model="addCustomUrl" @input="updateAddUrl()"
                                       placeholder="/custom-page or https://external-site.com"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>

                            <!-- Hidden URL Field -->
                            <input type="hidden" name="url" x-model="addFinalUrl">

                            <!-- Icon -->
                            <div>
                                <x-icon-picker
                                    id="icon"
                                    name="icon"
                                    :value="old('icon', $megaMenu->icon ?? '')"
                                    label="Icon (Font Awesome)"
                                    help-text="Example: fas fa-users, fas fa-rocket, fas fa-chart-line"
                                    :required="false"
                                />
                            </div>

                            <!-- Icon Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Background Color</label>
                                <input type="color" 
                                       name="icon_bg_color"
                                       value="#3B82F6"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                <input type="text" 
                                       name="tags"
                                       placeholder="e.g. dropdown-item (comma-separated)"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <p class="text-xs text-gray-500 mt-1">Optional. Comma-separated, e.g. dropdown-item</p>
                            </div>

                            <!-- Align -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Align</label>
                                <select name="align" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="1" selected>Left</option>
                                    <option value="2">Right</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Child alignment in dropdown (left or right column)</p>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active"
                                       value="1"
                                       checked
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <label class="ml-2 text-sm text-gray-700">Active</label>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" 
                                        @click="showAddModal = false"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                                    <i class="fas fa-save mr-2"></i>Add Sub-Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Sub-Item Modal -->
            <div x-show="showEditModal" 
                 x-cloak
                 @click.away="showEditModal = false"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-black opacity-50"></div>
                    
                    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Sub-Item</h3>
                            <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form @submit.prevent="updateSubItem()" class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       x-model="editForm.title"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="e.g., CRM">
                            </div>

                            <!-- Subtitle -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Subtitle
                                </label>
                                <input type="text" 
                                       x-model="editForm.subtitle"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="e.g., Customer relationship management">
                            </div>

                            <!-- Link Type Selection -->
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Link Type</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="edit_link_type" value="predefined" 
                                               x-model="editLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">Predefined Route</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="edit_link_type" value="system" 
                                               x-model="editLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">System Content</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="edit_link_type" value="custom" 
                                               x-model="editLinkType"
                                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                        <span class="text-sm text-gray-700">Custom URL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Predefined Routes Dropdown -->
                            <div x-show="editLinkType === 'predefined'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Select Route</label>
                                <select x-model="editSelectedRoute" @change="updateEditUrl()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Choose a page...</option>
                                    <template x-for="(url, name) in availableRoutes" :key="url">
                                        <option :value="url" x-text="name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- System Content Dropdown -->
                            <div x-show="editLinkType === 'system'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Select Content</label>
                                <select x-model="editSelectedSystemContent" @change="updateEditUrl()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Choose content...</option>
                                    <template x-for="(items, category) in systemContent" :key="category">
                                        <optgroup :label="category">
                                            <template x-for="item in items" :key="item.url">
                                                <option :value="item.url" x-text="item.title"></option>
                                            </template>
                                        </optgroup>
                                    </template>
                                </select>
                            </div>

                            <!-- Custom URL Input -->
                            <div x-show="editLinkType === 'custom'" x-transition class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Custom URL</label>
                                <input type="text" x-model="editCustomUrl" @input="updateEditUrl()"
                                       placeholder="/custom-page or https://external-site.com"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>

                            <!-- Icon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Icon (Font Awesome)
                                </label>
                                <input type="text" 
                                       x-model="editForm.icon"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                                       placeholder="e.g., fas fa-users">
                                <p class="text-xs text-gray-500 mt-1">Example: fas fa-users, fas fa-rocket, fas fa-chart-line</p>
                            </div>

                            <!-- Icon Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Background Color</label>
                                <input type="color" 
                                       x-model="editForm.icon_bg_color"
                                       class="w-full h-10 border border-gray-300 rounded-lg">
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                <input type="text" 
                                       x-model="editForm.tags"
                                       placeholder="e.g. dropdown-item (comma-separated)"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                <p class="text-xs text-gray-500 mt-1">Optional. Comma-separated</p>
                            </div>

                            <!-- Align -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Align</label>
                                <select x-model="editForm.align" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="1">Left</option>
                                    <option value="2">Right</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Child alignment in dropdown (left or right column)</p>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" 
                                           x-model="editForm.is_active"
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" 
                                           x-model="editForm.open_in_new_tab"
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Open in new tab</span>
                                </label>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-2 pt-4">
                                <button type="button" 
                                        @click="showEditModal = false"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        :disabled="isUpdating"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 disabled:opacity-50">
                                    <i class="fas fa-save mr-2"></i>
                                    <span x-text="isUpdating ? 'Updating...' : 'Update Sub-Item'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
function subItemManager() {
    const availableRoutes = @json($availableRoutes ?? []);
    const systemContent = @json($systemContent ?? []);
    
    return {
        showAddModal: false,
        showEditModal: false,
        isUpdating: false,
        currentEditId: null,
        
        // Add form data
        addLinkType: 'custom',
        addSelectedRoute: '',
        addSelectedSystemContent: '',
        addCustomUrl: '',
        addFinalUrl: '',
        
        // Edit form data
        editForm: {
            title: '',
            subtitle: '',
            url: '',
            icon: '',
            icon_bg_color: '#3B82F6',
            is_active: true,
            open_in_new_tab: false,
            tags: '',
            align: 1
        },
        editLinkType: 'custom',
        editSelectedRoute: '',
        editSelectedSystemContent: '',
        editCustomUrl: '',
        
        availableRoutes: availableRoutes,
        systemContent: systemContent,
        
        init() {
            // Initialize add form
            this.addLinkType = 'custom';
            this.updateAddUrl();
        },
        
        // Add form methods
        updateAddUrl() {
            if (this.addLinkType === 'predefined') {
                this.addFinalUrl = this.addSelectedRoute;
                this.addCustomUrl = '';
                this.addSelectedSystemContent = '';
            } else if (this.addLinkType === 'system') {
                this.addFinalUrl = this.addSelectedSystemContent;
                this.addCustomUrl = '';
                this.addSelectedRoute = '';
            } else {
                this.addFinalUrl = this.addCustomUrl;
                this.addSelectedRoute = '';
                this.addSelectedSystemContent = '';
            }
        },
        
        // Edit form methods
        async editSubItem(subItemId) {
            try {
                const response = await fetch(`{{ route('admin.settings.mega-menu.edit-sub-item', [$megaMenu, '__SUBITEM__']) }}`.replace('__SUBITEM__', subItemId));
                const data = await response.json();
                
                if (data.success) {
                    this.currentEditId = subItemId;
                    const tags = data.subItem.tags;
                    this.editForm = {
                        title: data.subItem.title || '',
                        subtitle: data.subItem.subtitle || '',
                        url: data.subItem.url || '',
                        icon: data.subItem.icon || '',
                        icon_bg_color: data.subItem.icon_bg_color || '#3B82F6',
                        is_active: data.subItem.is_active || false,
                        open_in_new_tab: data.subItem.open_in_new_tab || false,
                        tags: Array.isArray(tags) ? tags.join(', ') : (tags || ''),
                        align: data.subItem.align ?? 1
                    };
                    
                    // Determine link type and set appropriate values
                    this.determineLinkType(data.subItem.url);
                    this.showEditModal = true;
                } else {
                    alert('Error loading sub-item data');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error loading sub-item data');
            }
        },
        
        determineLinkType(url) {
            // Reset all values
            this.editLinkType = 'custom';
            this.editSelectedRoute = '';
            this.editSelectedSystemContent = '';
            this.editCustomUrl = url || '';
            
            if (!url) return;
            
            // Check if URL matches any predefined route
            for (const [name, routeUrl] of Object.entries(this.availableRoutes)) {
                if (routeUrl === url) {
                    this.editLinkType = 'predefined';
                    this.editSelectedRoute = url;
                    this.editCustomUrl = '';
                    return;
                }
            }
            
            // Check if URL matches any system content
            for (const [category, items] of Object.entries(this.systemContent)) {
                for (const item of items) {
                    if (item.url === url) {
                        this.editLinkType = 'system';
                        this.editSelectedSystemContent = url;
                        this.editCustomUrl = '';
                        return;
                    }
                }
            }
        },
        
        updateEditUrl() {
            if (this.editLinkType === 'predefined') {
                this.editForm.url = this.editSelectedRoute;
                this.editCustomUrl = '';
                this.editSelectedSystemContent = '';
            } else if (this.editLinkType === 'system') {
                this.editForm.url = this.editSelectedSystemContent;
                this.editCustomUrl = '';
                this.editSelectedRoute = '';
            } else {
                this.editForm.url = this.editCustomUrl;
                this.editSelectedRoute = '';
                this.editSelectedSystemContent = '';
            }
        },
        
        async updateSubItem() {
            if (!this.currentEditId) return;
            
            this.isUpdating = true;
            
            try {
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('title', this.editForm.title);
                formData.append('subtitle', this.editForm.subtitle || '');
                formData.append('url', this.editForm.url || '');
                formData.append('link_type', this.editLinkType);
                formData.append('icon', this.editForm.icon || '');
                formData.append('icon_bg_color', this.editForm.icon_bg_color || '#3B82F6');
                formData.append('is_active', this.editForm.is_active ? '1' : '0');
                formData.append('open_in_new_tab', this.editForm.open_in_new_tab ? '1' : '0');
                formData.append('tags', this.editForm.tags || '');
                formData.append('align', this.editForm.align ?? '1');
                
                const response = await fetch(`{{ route('admin.settings.mega-menu.update-sub-item', [$megaMenu, '__SUBITEM__']) }}`.replace('__SUBITEM__', this.currentEditId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showEditModal = false;
                    // Reload the page to show updated data
                    window.location.reload();
                } else {
                    alert(data.message || 'Error updating sub-item');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating sub-item');
            } finally {
                this.isUpdating = false;
            }
        }
    };
}
</script>
</x-layouts.admin>
