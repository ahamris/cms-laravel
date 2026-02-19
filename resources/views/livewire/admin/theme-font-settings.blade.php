<div>
    {{-- Typography Section --}}
    <div class="border-t border-gray-200 pt-8">
        <h4 class="text-lg font-semibold mb-6 text-gray-900 flex items-center">
            <i class="fa-solid fa-font text-purple-600 mr-3"></i>
            Typography
        </h4>

        <div class="space-y-6">
            {{-- Sans-serif Font --}}
            <div>
                <label for="fontSans" class="block text-sm font-medium text-gray-700 mb-2">
                    Primary Font (Sans-serif)
                </label>
                <select id="fontSans" 
                        wire:model="fontSans"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200">
                    @foreach($googleFonts as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="font-preview-text mt-2" style="font-family: '{{ $fontSans }}', sans-serif;">
                    The quick brown fox jumps over the lazy dog
                </div>
                <p class="text-xs text-gray-500 mt-2">Used for body text, paragraphs, and general content</p>
            </div>

            {{-- Outfit Font --}}
            <div>
                <label for="fontOutfit" class="block text-sm font-medium text-gray-700 mb-2">
                    Heading Font (Outfit)
                </label>
                <select id="fontOutfit" 
                        wire:model="fontOutfit"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200">
                    @foreach($googleFonts as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="font-preview-text mt-2" style="font-family: '{{ $fontOutfit }}', sans-serif;">
                    The quick brown fox jumps over the lazy dog
                </div>
                <p class="text-xs text-gray-500 mt-2">Used for headings, titles, and emphasis</p>
            </div>
        </div>
    </div>

    {{-- Font Size Settings --}}
    <div class="border-t border-gray-200 pt-8">
        <h4 class="text-lg font-semibold mb-6 text-gray-900 flex items-center">
            <i class="fa-solid fa-text-height text-purple-600 mr-3"></i>
            Font Sizes
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- H1 Font Size --}}
            <div>
                <label for="fontSizeH1Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H1 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH1Px ?? 48 }}, updateRem() { return (this.px / 16).toFixed(4); } }">
                    <input type="number" 
                           id="fontSizeH1Px" 
                           wire:model.blur="fontSizeH1Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH1Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="48">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 48"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- H2 Font Size --}}
            <div>
                <label for="fontSizeH2Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H2 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH2Px ?? 36 }}, updateRem() { return (this.px / 16).toFixed(4); } }">
                    <input type="number" 
                           id="fontSizeH2Px" 
                           wire:model.blur="fontSizeH2Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH2Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="30">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 36"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- H3 Font Size --}}
            <div>
                <label for="fontSizeH3Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H3 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH3Px ?? 24 }}, updateRem() { return (this.px / 16).toFixed(4); } }">
                    <input type="number" 
                           id="fontSizeH3Px" 
                           wire:model.blur="fontSizeH3Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH3Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="24">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 24"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- H4 Font Size --}}
            <div>
                <label for="fontSizeH4Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H4 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH4Px ?? 18 }}, updateRem() { return (this.px / 16).toFixed(4); } }"
                     @reset-alpine-state.window="px = $event.detail.fontSizeH4Px">
                    <input type="number" 
                           id="fontSizeH4Px" 
                           wire:model.blur="fontSizeH4Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH4Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="20">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 18"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- H5 Font Size --}}
            <div>
                <label for="fontSizeH5Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H5 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH5Px ?? 16 }}, updateRem() { return (this.px / 16).toFixed(4); } }"
                     @reset-alpine-state.window="px = $event.detail.fontSizeH5Px">
                    <input type="number" 
                           id="fontSizeH5Px" 
                           wire:model.blur="fontSizeH5Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH5Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="16">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 16"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- H6 Font Size --}}
            <div>
                <label for="fontSizeH6Px" class="block text-sm font-medium text-gray-700 mb-2">
                    H6 Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizeH6Px ?? 14 }}, updateRem() { return (this.px / 16).toFixed(4); } }"
                     @reset-alpine-state.window="px = $event.detail.fontSizeH6Px">
                    <input type="number" 
                           id="fontSizeH6Px" 
                           wire:model.blur="fontSizeH6Px"
                           x-model.number="px"
                           @input="$wire.set('fontSizeH6Px', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="14">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 14"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>

            {{-- Paragraph Font Size --}}
            <div>
                <label for="fontSizePPx" class="block text-sm font-medium text-gray-700 mb-2">
                    Paragraph Font Size (px)
                </label>
                <div x-data="{ px: {{ $fontSizePPx ?? 16 }}, updateRem() { return (this.px / 16).toFixed(4); } }"
                     @reset-alpine-state.window="px = $event.detail.fontSizePPx">
                    <input type="number" 
                           id="fontSizePPx" 
                           wire:model.blur="fontSizePPx"
                           x-model.number="px"
                           @input="$wire.set('fontSizePPx', $event.target.value ? parseInt($event.target.value) : null)"
                           min="1"
                           max="3200"
                           step="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 font-mono"
                           placeholder="16">
                    {{-- Rem Conversion Info Box --}}
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            <strong x-text="px || 16"></strong>px = <strong x-text="updateRem()"></strong>rem
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="mt-4 flex items-center text-sm text-gray-600">
        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
        Saving...
    </div>
</div>

@push('scripts')
<script>
    // Load Google Fonts dynamically
    function loadGoogleFont(fontName) {
        if (!fontName) return;
        const fontUrl = `https://fonts.googleapis.com/css2?family=${fontName.replace(/ /g, '+')}:wght@400;500;600;700&display=swap`;
        const existingLink = document.querySelector(`link[href*="${fontName.replace(/ /g, '+')}"]`);
        if (!existingLink) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = fontUrl;
            document.head.appendChild(link);
        }
    }

    // Load fonts when component updates
    document.addEventListener('livewire:update', () => {
        const fontSansEl = document.getElementById('fontSans');
        const fontOutfitEl = document.getElementById('fontOutfit');
        if (fontSansEl) loadGoogleFont(fontSansEl.value);
        if (fontOutfitEl) loadGoogleFont(fontOutfitEl.value);
    });
</script>
@endpush
