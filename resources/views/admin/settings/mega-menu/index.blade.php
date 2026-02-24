<x-layouts.admin title="Mega Menu Management">
    <div class="px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mega Menu Management</h1>
                <p class="text-gray-600 mt-1">Manage navigation menu structure</p>
            </div>
            <a href="{{ route('admin.settings.mega-menu.create') }}"
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                <i class="fas fa-plus mr-2"></i>Add Root Item
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg mr-3"></i>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Menu Items (2/3) -->
            <div class="lg:col-span-2">
                @if($menuItems->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-bars text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No menu items yet</h3>
                        <p class="text-gray-600 mb-6">Get started by creating your first mega menu item</p>
                        <a href="{{ route('admin.settings.mega-menu.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            <i class="fas fa-plus mr-2"></i>Add First Item
                        </a>
                    </div>
                @else
                    <!-- Menu Items List -->
                    <div class="bg-white rounded-lg shadow">
                        @foreach($menuItems as $item)
                            <div class="border-b border-gray-200 last:border-b-0" x-data="{ childrenOpen: false }">
                                <!-- Root Item -->
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3 flex-1">
                                            @if($item->children->count() > 0)
                                                <button @click="childrenOpen = !childrenOpen" class="text-gray-500 hover:text-gray-700">
                                                    <i class="fas fa-chevron-right transition-transform text-sm" :class="childrenOpen && 'rotate-90'"></i>
                                                </button>
                                            @else
                                                <span class="w-5"></span>
                                            @endif

                                            @if($item->icon)
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                                     style="background-color: {{ $item->icon_bg_color ?? '#3B82F6' }}">
                                                    <i class="{{ $item->icon }} text-white"></i>
                                                </div>
                                            @endif

                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="font-semibold text-gray-900">{{ $item->title }}</h4>
                                                    @if($item->is_mega_menu)
                                                        <span class="text-xs px-2 py-0.5 bg-primary text-white rounded-full">Mega Menu</span>
                                                    @else
                                                        <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-700 rounded-full">Simple Link</span>
                                                    @endif
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                            {{ $item->children->count() }} children
                                        </span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-link mr-1"></i>{{ $item->url ?? '#' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            @if($item->is_mega_menu)
                                                <a href="{{ route('admin.settings.mega-menu.create', ['parent_id' => $item->id]) }}"
                                                   title="Add child item"
                                                   class="px-3 py-1.5 text-primary hover:bg-primary/10 rounded text-sm">
                                                    <i class="fas fa-plus mr-1"></i>Add Child
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.settings.mega-menu.edit', $item) }}"
                                               title="Edit item"
                                               class="px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded text-sm">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.settings.mega-menu.destroy', $item) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Are you sure? This will also delete all children.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="Delete item"
                                                        class="px-3 py-1.5 text-red-600 hover:bg-red-50 rounded text-sm">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Children List -->
                                @if($item->children->count() > 0)
                                    <div x-show="childrenOpen" x-collapse class="bg-gray-50 px-4 pb-4">
                                        <div class="pl-8 space-y-2">
                                            @foreach($item->children as $child)
                                                <div class="flex items-center justify-between p-3 bg-white rounded hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center space-x-3 flex-1">
                                                        @if($child->icon)
                                                            <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0"
                                                                 style="background-color: {{ $child->icon_bg_color }}20">
                                                                <i class="{{ $child->icon }} text-sm" style="color: {{ $child->icon_bg_color }}"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-gray-900">{{ $child->title }}</p>
                                                            @if($child->subtitle)
                                                                <p class="text-xs text-gray-500">{{ $child->subtitle }}</p>
                                                            @endif
                                                            <p class="text-xs text-gray-400 mt-0.5">
                                                                <i class="fas fa-link mr-1"></i>{{ $child->url ?? '#' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('admin.settings.mega-menu.edit', $child) }}"
                                                           class="px-2 py-1 text-blue-600 hover:bg-blue-50 rounded text-xs">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.settings.mega-menu.destroy', $child) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Delete this menu item?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-2 py-1 text-red-600 hover:bg-red-50 rounded text-xs">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Side: Settings (1/3) -->
            <div class="lg:col-span-1">
                <form action="{{ route('admin.settings.mega-menu.update-all-settings') }}" method="POST" id="settings-form">
                    @csrf
                    {{-- Hidden inputs for CTA settings --}}
                    <input type="hidden" name="header_cta_button_text" id="header_cta_button_text_hidden" value="{{ get_setting('header_cta_button_text', 'Sign up') }}">
                    <input type="hidden" name="header_cta_button_url" id="header_cta_button_url_hidden" value="{{ get_setting('header_cta_button_url', '#') }}">

                    <!-- Save Button -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            <i class="fas fa-save mr-2"></i>Save All Settings
                        </button>
                        <button type="button"
                                onclick="openHeaderCtaSettingsModal()"
                                class="w-full mt-3 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-cog mr-2"></i>Header CTA Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Header CTA Settings Modal -->
    <div id="header-cta-settings-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50" onclick="closeHeaderCtaSettingsModal(event)">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900">Header CTA Settings</h3>
                <button type="button" onclick="closeHeaderCtaSettingsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-start">
                        <i class="fa-solid fa-info-circle text-blue-500 text-sm mr-2 mt-0.5"></i>
                        <p class="text-sm text-blue-800">Configure CTA settings here. Use the "Save All Settings" button in the main form to save your changes.</p>
                    </div>
                </div>

                <div class="space-y-4" id="header-cta-settings-form">
                    <div>
                        <label for="header_cta_button_text" class="block text-sm font-medium text-gray-700 mb-2">CTA Button Text</label>
                        <input type="text" name="header_cta_button_text" id="header_cta_button_text"
                               value="{{ get_setting('header_cta_button_text', 'Sign up') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="header_cta_button_url" class="block text-sm font-medium text-gray-700 mb-2">CTA Button URL</label>
                        <input type="text" name="header_cta_button_url" id="header_cta_button_url"
                               value="{{ get_setting('header_cta_button_url', '#') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="saveCtaSettingsToMainForm()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                            <i class="fas fa-check mr-2"></i>Apply CTA Settings
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                <button type="button" onclick="closeHeaderCtaSettingsModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openHeaderCtaSettingsModal() {
            document.getElementById('header-cta-settings-modal').classList.remove('hidden');
            document.getElementById('header-cta-settings-modal').classList.add('flex');
        }

        function closeHeaderCtaSettingsModal(event) {
            if (event && event.target !== event.currentTarget) {
                return;
            }
            document.getElementById('header-cta-settings-modal').classList.add('hidden');
            document.getElementById('header-cta-settings-modal').classList.remove('flex');
        }

        // Save CTA settings to main form and close modal
        function saveCtaSettingsToMainForm() {
            const buttonText = document.getElementById('header_cta_button_text').value;
            const buttonUrl = document.getElementById('header_cta_button_url').value;

            // Update hidden inputs in main form
            document.getElementById('header_cta_button_text_hidden').value = buttonText;
            document.getElementById('header_cta_button_url_hidden').value = buttonUrl;

            // Close modal
            closeHeaderCtaSettingsModal();

            // Show success message
            showTemporaryMessage('CTA settings applied. Click "Save All Settings" to save changes.', 'success');
        }

        // Show temporary success/info message
        function showTemporaryMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-4 bg-${type === 'success' ? 'green' : 'blue'}-50 border border-${type === 'success' ? 'green' : 'blue'}-200 rounded-lg p-3`;
            messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fa-solid fa-${type === 'success' ? 'check' : 'info'}-circle text-${type === 'success' ? 'green' : 'blue'}-500 text-sm mr-2"></i>
                <p class="text-sm font-medium text-${type === 'success' ? 'green' : 'blue'}-800">${message}</p>
            </div>
        `;

            const settingsForm = document.getElementById('settings-form');
            settingsForm.parentElement.insertBefore(messageDiv, settingsForm);

            // Remove message after 5 seconds
            setTimeout(function() {
                messageDiv.remove();
            }, 5000);
        }

    </script>
</x-layouts.admin>
