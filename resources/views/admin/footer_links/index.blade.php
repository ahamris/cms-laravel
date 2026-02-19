<x-layouts.admin title="Footer Links Management">
<x-slot:styles>
<style>
    .sortable-ghost {
        background-color: #c8ebfb;
        opacity: 0.5;
    }
    .sortable-drag {
        opacity: 1 !important;
    }
</style>
</x-slot:styles>

<div class="px-4 py-6">
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center">
            <i class="fa-solid fa-check-circle text-green-500 text-lg mr-3"></i>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Side: Footer Links (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Footer Links</h3>
                    <a href="{{ route('admin.settings.footer-links.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i> Add New Link
                    </a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6" id="footer-columns-container">
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-bold text-gray-700 mb-4">Column {{ $i }}</h4>
                            <div id="column-{{ $i }}" data-column-id="{{ $i }}" class="space-y-3 min-h-[200px] sortable-list">
                                @if(isset($links[$i]))
                                    @foreach($links[$i] as $link)
                                    <div class="bg-white p-3 rounded-md shadow-sm border border-gray-200 flex justify-between items-center cursor-move" data-id="{{ $link->id }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-grip-vertical text-gray-400 mr-3"></i>
                                            <span class="text-sm font-medium text-gray-800">{{ $link->title }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.settings.footer-links.edit', $link) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.settings.footer-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Footer Component Selection (1/3) -->
        <div class="lg:col-span-1">
            <form action="{{ route('admin.settings.footer-links.update-footer-component') }}" method="POST" id="footer-settings-form">
                @csrf
                {{-- Hidden inputs for CTA settings --}}
                <input type="hidden" name="footer_cta_title" id="footer_cta_title_hidden" value="{{ get_setting('footer_cta_title', 'Get started') }}">
                <input type="hidden" name="footer_cta_subtitle" id="footer_cta_subtitle_hidden" value="{{ get_setting('footer_cta_subtitle', 'Boost your productivity. Start using our app today.') }}">
                <input type="hidden" name="footer_cta_description" id="footer_cta_description_hidden" value="{{ get_setting('footer_cta_description', 'Incididunt sint fugiat pariatur cupidatat consectetur sit cillum anim id veniam aliqua proident excepteur commodo do ea.') }}">
                <input type="hidden" name="footer_cta_button_text" id="footer_cta_button_text_hidden" value="{{ get_setting('footer_cta_button_text', 'Get started') }}">
                <input type="hidden" name="footer_cta_button_url" id="footer_cta_button_url_hidden" value="{{ get_setting('footer_cta_button_url', '#') }}">
                
                <!-- Footer Component Selection -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Footer Component Selection</h2>
                        <p class="text-sm text-gray-600 mt-1">Select a TailwindPlus component to use as the footer</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                        <div>
                            <label for="footer_component_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Footer Component
                            </label>
                            <select name="footer_component_id" id="footer_component_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    onchange="updateFooterSettingsButton()">
                                <option value="">-- Use Default Footer --</option>
                                @foreach($footerComponents as $component)
                                    <option value="{{ $component['id'] }}" 
                                            {{ $selectedFooterComponentId == $component['id'] ? 'selected' : '' }}
                                            data-raw-name="{{ $component['raw_name'] ?? '' }}">
                                        {{ $component['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            </div>
                            <div>
                                <label for="footer_layout_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Footer Layout Type
                                </label>
                                <select name="footer_layout_type" id="footer_layout_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="" {{ $selectedFooterLayoutType === null || $selectedFooterLayoutType === '' ? 'selected' : '' }}>Use page settings</option>
                                    <option value="full-width" {{ $selectedFooterLayoutType === 'full-width' ? 'selected' : '' }}>Full Width</option>
                                    <option value="container" {{ $selectedFooterLayoutType === 'container' ? 'selected' : '' }}>Container (mx-auto)</option>
                                    <option value="max-w-2xl" {{ $selectedFooterLayoutType === 'max-w-2xl' ? 'selected' : '' }}>Max Width: 2xl</option>
                                    <option value="max-w-4xl" {{ $selectedFooterLayoutType === 'max-w-4xl' ? 'selected' : '' }}>Max Width: 4xl</option>
                                    <option value="max-w-6xl" {{ $selectedFooterLayoutType === 'max-w-6xl' ? 'selected' : '' }}>Max Width: 6xl</option>
                                    <option value="max-w-7xl" {{ $selectedFooterLayoutType === 'max-w-7xl' ? 'selected' : '' }}>Max Width: 7xl</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">If "Use page settings" is selected, the showcase page's layout type will be used</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="bg-white shadow rounded-lg p-6">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Footer Component
                    </button>
                    <div id="footer-settings-button-container"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer Settings Modal -->
<div id="footer-settings-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50" onclick="closeFooterSettingsModal(event)">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-900">Footer Settings</h3>
            <button type="button" onclick="closeFooterSettingsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 space-y-6" id="footer-settings-modal-content">
            @php
                $selectedComponent = collect($footerComponents)->firstWhere('id', $selectedFooterComponentId);
                $componentRawName = $selectedComponent && isset($selectedComponent['raw_name']) ? $selectedComponent['raw_name'] : '';
                $hasCTA = $selectedComponent && in_array($componentRawName, ['4-column with call-to-action']);
                $hasNewsletter = $selectedComponent && in_array($componentRawName, ['4-column with newsletter', '4-column with newsletter below']);
                $hasSocialLinks = $selectedComponent && in_array($componentRawName, ['Simple with social links', '4-column with call-to-action', '4-column with company mission', '4-column with newsletter', '4-column with newsletter below']);
            @endphp

            <div id="footer-cta-settings-section" style="display: none;">
                <!-- CTA Settings -->
                <div>
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fa-solid fa-info-circle text-blue-500 text-sm mr-2 mt-0.5"></i>
                            <p class="text-sm text-blue-800">Configure CTA settings here. Use the "Save Footer Component" button in the main form to save your changes.</p>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Call To Action Settings</h4>
                    <div class="space-y-4" id="footer-cta-settings-form">
                        <div>
                            <label for="cta_title" class="block text-sm font-medium text-gray-700 mb-2">CTA Title</label>
                            <input type="text" name="cta_title" id="cta_title" 
                                   value="{{ get_setting('footer_cta_title', 'Get started') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="cta_subtitle" class="block text-sm font-medium text-gray-700 mb-2">CTA Subtitle</label>
                            <input type="text" name="cta_subtitle" id="cta_subtitle" 
                                   value="{{ get_setting('footer_cta_subtitle', 'Boost your productivity. Start using our app today.') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label for="cta_description" class="block text-sm font-medium text-gray-700 mb-2">CTA Description</label>
                            <textarea name="cta_description" id="cta_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">{{ get_setting('footer_cta_description', 'Incididunt sint fugiat pariatur cupidatat consectetur sit cillum anim id veniam aliqua proident excepteur commodo do ea.') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="cta_button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                                <input type="text" name="cta_button_text" id="cta_button_text" 
                                       value="{{ get_setting('footer_cta_button_text', 'Get started') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label for="cta_button_url" class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                                <input type="text" name="cta_button_url" id="cta_button_url" 
                                       value="{{ get_setting('footer_cta_button_url', '#') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="saveFooterCtaSettingsToMainForm()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>Apply CTA Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="footer-newsletter-section" style="display: none;">
                <!-- Newsletter Info -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fa-solid fa-info-circle text-yellow-600 text-lg mr-3 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-yellow-900 mb-1">Newsletter Subscription</h4>
                            <p class="text-sm text-yellow-800">
                                Newsletter subscription is planned for a future release. The footer signup form is currently disabled until a newsletter service is integrated.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="footer-social-links-section" style="display: none;">
                <!-- Social Links Management -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-gray-900">Social Links</h4>
                        <a href="{{ route('admin.social-settings.index') }}" 
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Manage Social Links <i class="fa-solid fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Social links are managed from the Social Settings page. Click "Manage Social Links" to add, edit, or remove social media links.
                    </p>
                    @php
                        $socialLinks = \App\Models\SocialSetting::getCached();
                    @endphp
                    @if($socialLinks && $socialLinks->count() > 0)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-medium text-gray-700 mb-2">Current Social Links:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($socialLinks as $social)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $social->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-600">No social links configured yet.</p>
                            <a href="{{ route('admin.social-settings.create') }}" 
                               class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Add Your First Social Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
            <button type="button" onclick="closeFooterSettingsModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function updateFooterSettingsButton() {
        const select = document.getElementById('footer_component_id');
        const selectedOption = select.options[select.selectedIndex];
        const rawName = selectedOption ? selectedOption.getAttribute('data-raw-name') : null;
        const container = document.getElementById('footer-settings-button-container');
        
        // Clear existing buttons
        container.innerHTML = '';
        
        // Check if component has CTA, Newsletter, or Social Links
        const hasCTA = rawName === '4-column with call-to-action';
        const hasNewsletter = rawName === '4-column with newsletter' || rawName === '4-column with newsletter below';
        const hasSocialLinks = rawName === 'Simple with social links' || rawName === '4-column with call-to-action' || rawName === '4-column with company mission' || rawName === '4-column with newsletter' || rawName === '4-column with newsletter below';
        
        // Update modal content visibility
        const ctaSection = document.getElementById('footer-cta-settings-section');
        const newsletterSection = document.getElementById('footer-newsletter-section');
        const socialLinksSection = document.getElementById('footer-social-links-section');
        
        if (ctaSection) ctaSection.style.display = hasCTA ? 'block' : 'none';
        if (newsletterSection) newsletterSection.style.display = hasNewsletter ? 'block' : 'none';
        if (socialLinksSection) socialLinksSection.style.display = hasSocialLinks ? 'block' : 'none';
        
        if (hasCTA || hasNewsletter || hasSocialLinks) {
            const settingsButton = document.createElement('button');
            settingsButton.type = 'button';
            settingsButton.onclick = function() { openFooterSettingsModal(); };
            settingsButton.className = 'w-full mt-3 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors';
            settingsButton.innerHTML = '<i class="fas fa-cog mr-2"></i>Footer Settings';
            container.appendChild(settingsButton);
        }
    }

    function openFooterSettingsModal() {
        document.getElementById('footer-settings-modal').classList.remove('hidden');
        document.getElementById('footer-settings-modal').classList.add('flex');
    }

    function closeFooterSettingsModal(event) {
        if (event && event.target !== event.currentTarget) {
            return;
        }
        document.getElementById('footer-settings-modal').classList.add('hidden');
        document.getElementById('footer-settings-modal').classList.remove('flex');
    }

    // Save CTA settings to main form and close modal
    function saveFooterCtaSettingsToMainForm() {
        const ctaTitle = document.getElementById('cta_title').value;
        const ctaSubtitle = document.getElementById('cta_subtitle').value;
        const ctaDescription = document.getElementById('cta_description').value;
        const ctaButtonText = document.getElementById('cta_button_text').value;
        const ctaButtonUrl = document.getElementById('cta_button_url').value;
        
        // Update hidden inputs in main form
        document.getElementById('footer_cta_title_hidden').value = ctaTitle;
        document.getElementById('footer_cta_subtitle_hidden').value = ctaSubtitle;
        document.getElementById('footer_cta_description_hidden').value = ctaDescription;
        document.getElementById('footer_cta_button_text_hidden').value = ctaButtonText;
        document.getElementById('footer_cta_button_url_hidden').value = ctaButtonUrl;
        
        // Close modal
        closeFooterSettingsModal();
        
        // Show success message
        showFooterTemporaryMessage('CTA settings applied. Click "Save Footer Component" to save changes.', 'success');
    }
    
    // Show temporary success/info message
    function showFooterTemporaryMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 bg-${type === 'success' ? 'green' : 'blue'}-50 border border-${type === 'success' ? 'green' : 'blue'}-200 rounded-lg p-3`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fa-solid fa-${type === 'success' ? 'check' : 'info'}-circle text-${type === 'success' ? 'green' : 'blue'}-500 text-sm mr-2"></i>
                <p class="text-sm font-medium text-${type === 'success' ? 'green' : 'blue'}-800">${message}</p>
            </div>
        `;
        
        const settingsForm = document.getElementById('footer-settings-form');
        settingsForm.parentElement.insertBefore(messageDiv, settingsForm);
        
        // Remove message after 5 seconds
        setTimeout(function() {
            messageDiv.remove();
        }, 5000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFooterSettingsButton();
    });
</script>

<x-slot:scripts>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columns = document.querySelectorAll('.sortable-list');
        columns.forEach(column => {
            new Sortable(column, {
                group: 'footer-links',
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                preventOnFilter: false, // Allows clicks on buttons inside sortable items
                onEnd: function (evt) {
                    updateOrder();
                }
            });
        });

        function updateOrder() {
            let orderData = {};
            columns.forEach(column => {
                const columnId = column.dataset.columnId;
                const items = Array.from(column.children).map(item => item.dataset.id);
                orderData[columnId] = items;
            });

            fetch('{{ route("admin.settings.footer-links.order") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: orderData })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to save order');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>
</x-slot:scripts>
</x-layouts.admin>
