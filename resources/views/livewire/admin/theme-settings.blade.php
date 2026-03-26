<div>
    {{-- Header with Title --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex flex-col gap-2">
            <h2>Theme Settings</h2>
            <p>Customize your website's colors and fonts with live preview</p>
        </div>
        <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
            <i class="fa-solid fa-palette text-white text-xl"></i>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-12 gap-8">

        {{-- Left Column: Theme Settings --}}
        <div class="col-span-8">
            <div class="flex flex-col gap-4 bg-gray-50/50 rounded-md border border-gray-200 p-8 mb-8">
                {{-- Theme Customization Card --}}
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-secondary rounded-md flex items-center justify-center">
                        <i class="fa-solid fa-swatchbook text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3>Theme Customization</h3>
                        <p>Adjust colors and fonts to match your brand</p>
                    </div>
                </div>

                <div class="space-y-8">
                    {{-- Brand Colors --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-palette text-primary mr-3"></i>
                            Brand Colors
                        </h4>

                        <div class="grid grid-cols-3 gap-4">
                            {{-- Primary Color --}}
                            <div>
                                <label for="colorPrimary" class="block text-sm font-medium text-gray-700 mb-2">
                                    Primary Color
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="colorPrimary" wire:model.live="colorPrimary"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="colorPrimaryText"
                                        wire:model.live.debounce.500ms="colorPrimary"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#081245">
                                </div>
                                @error('colorPrimary')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Secondary Color --}}
                            <div>
                                <label for="colorSecondary" class="block text-sm font-medium text-gray-700 mb-2">
                                    Secondary Color
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="colorSecondary" wire:model.live="colorSecondary"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="colorSecondaryText"
                                        wire:model.live.debounce.500ms="colorSecondary"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#0073e6">
                                </div>
                                @error('colorSecondary')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Natural Color --}}
                            <div>
                                <label for="colorNatural" class="block text-sm font-medium text-gray-700 mb-2">
                                    Natural Color
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="colorNatural" wire:model.live="colorNatural"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="colorNaturalText"
                                        wire:model.live.debounce.500ms="colorNatural"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#dfd4d4">
                                </div>
                                @error('colorNatural')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Header & Footer Colors --}}
                    <div>
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-layer-group text-primary mr-3"></i>
                            Header & Footer Colors
                        </h4>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Header Background --}}
                            <div>
                                <label for="headerBg" class="block text-sm font-medium text-gray-700 mb-2">
                                    Header Background
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="headerBg" wire:model.live="headerBg"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="headerBgText"
                                        wire:model.live.debounce.500ms="headerBg"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#ffffff">
                                </div>
                                @error('headerBg')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Header Text --}}
                            <div>
                                <label for="headerText" class="block text-sm font-medium text-gray-700 mb-2">
                                    Header Text
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="headerText" wire:model.live="headerText"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="headerTextText"
                                        wire:model.live.debounce.500ms="headerText"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#1a1a2e">
                                </div>
                                @error('headerText')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Footer Background --}}
                            <div>
                                <label for="footerBg" class="block text-sm font-medium text-gray-700 mb-2">
                                    Footer Background
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="footerBg" wire:model.live="footerBg"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="footerBgText"
                                        wire:model.live.debounce.500ms="footerBg"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#1a1a2e">
                                </div>
                                @error('footerBg')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Footer Text --}}
                            <div>
                                <label for="footerText" class="block text-sm font-medium text-gray-700 mb-2">
                                    Footer Text
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <input type="color" id="footerText" wire:model.live="footerText"
                                        class="w-full h-12 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" id="footerTextText"
                                        wire:model.live.debounce.500ms="footerText"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                        placeholder="#ffffff">
                                </div>
                                @error('footerText')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Typography Section --}}
                    <div class="pt-6">
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-font text-primary mr-3"></i>
                            Typography
                        </h4>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Sans-serif Font --}}
                            <div>
                                <label for="fontSans" class="block text-sm font-medium text-gray-700 mb-2">
                                    Primary Font (Sans-serif)
                                </label>
                                <div class="relative">
                                    <input type="text" id="fontSansSearch" wire:model.live="fontSearchSans"
                                        placeholder="Search fonts..."
                                        class="block bg-white w-full px-3 py-2 pl-9 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none text-sm mb-2">
                                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                                    <select id="fontSans" wire:model.live="fontSans" size="10"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none text-sm overflow-y-auto">
                                        @foreach($filteredFontsSans as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('fontSans')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <div class="font-preview-text mt-2 p-2 bg-gray-50 border border-gray-200 rounded-md text-xs"
                                    style="font-family: '{{ $fontSans }}', sans-serif;">
                                    The quick brown fox jumps over the lazy dog
                                </div>
                            </div>

                            {{-- Outfit Font --}}
                            <div>
                                <label for="fontOutfit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Heading Font (Outfit)
                                </label>
                                <div class="relative">
                                    <input type="text" id="fontOutfitSearch" wire:model.live="fontSearchOutfit"
                                        placeholder="Search fonts..."
                                        class="block bg-white w-full px-3 py-2 pl-9 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none text-sm mb-2">
                                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                                    <select id="fontOutfit" wire:model.live="fontOutfit" size="10"
                                        class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 focus:outline-none text-sm overflow-y-auto">
                                        @foreach($filteredFontsOutfit as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('fontOutfit')
                                    <p class="mt-1 text-xs text-red-600 flex items-center">
                                        <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <div class="font-preview-text mt-2 p-2 bg-gray-50 border border-gray-200 rounded-md text-xs"
                                    style="font-family: '{{ $fontOutfit }}', sans-serif;">
                                    The quick brown fox jumps over the lazy dog
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Font Size Settings --}}
                    <div class="pt-6">
                        <h4 class="mb-4 flex items-center">
                            <i class="fa-solid fa-text-height text-primary mr-3"></i>
                            Font Sizes
                        </h4>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- H1 Font Size --}}
                            <div>
                                <label for="fontSizeH1Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H1 (px)
                                </label>
                                <input type="number" id="fontSizeH1Px" wire:model.live.debounce.500ms="fontSizeH1Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="48">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH1Px ?? 48 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH1Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH1Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- H2 Font Size --}}
                            <div>
                                <label for="fontSizeH2Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H2 (px)
                                </label>
                                <input type="number" id="fontSizeH2Px" wire:model.live.debounce.500ms="fontSizeH2Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="36">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH2Px ?? 36 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH2Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH2Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- H3 Font Size --}}
                            <div>
                                <label for="fontSizeH3Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H3 (px)
                                </label>
                                <input type="number" id="fontSizeH3Px" wire:model.live.debounce.500ms="fontSizeH3Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="24">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH3Px ?? 24 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH3Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH3Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- H4 Font Size --}}
                            <div>
                                <label for="fontSizeH4Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H4 (px)
                                </label>
                                <input type="number" id="fontSizeH4Px" wire:model.live.debounce.500ms="fontSizeH4Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="18">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH4Px ?? 18 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH4Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH4Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- H5 Font Size --}}
                            <div>
                                <label for="fontSizeH5Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H5 (px)
                                </label>
                                <input type="number" id="fontSizeH5Px" wire:model.live.debounce.500ms="fontSizeH5Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="16">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH5Px ?? 16 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH5Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH5Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- H6 Font Size --}}
                            <div>
                                <label for="fontSizeH6Px" class="block text-sm font-medium text-gray-700 mb-2">
                                    H6 (px)
                                </label>
                                <input type="number" id="fontSizeH6Px" wire:model.live.debounce.500ms="fontSizeH6Px"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="14">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizeH6Px ?? 14 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizeH6Px) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizeH6Px')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Paragraph Font Size --}}
                            <div>
                                <label for="fontSizePPx" class="block text-sm font-medium text-gray-700 mb-2">
                                    P (px)
                                </label>
                                <input type="number" id="fontSizePPx" wire:model.live.debounce.500ms="fontSizePPx"
                                    min="1" max="3200" step="1"
                                    class="block bg-white w-full px-3 py-2 border border-gray-200 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none font-mono text-sm"
                                    placeholder="16">
                                <div class="mt-1 p-2 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-xs text-blue-900">
                                        <strong>{{ $fontSizePPx ?? 16 }}</strong>px =
                                        <strong>{{ $this->getRemValue($fontSizePPx) }}</strong>rem
                                    </p>
                                </div>
                                @error('fontSizePPx')
                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="resetToDefaults" wire:loading.attr="disabled"
                            class="bg-gray-200 text-gray-800 px-5 py-2 rounded-md font-medium hover:bg-gray-300 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 text-sm">
                            <i class="fa-solid fa-undo mr-2"></i>
                            Reset to Defaults
                        </button>
                        <button type="button" wire:click="save" wire:loading.attr="disabled"
                            class="px-6 py-2 rounded-md font-medium focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 text-sm {{ $saved ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-primary text-white hover:bg-primary/90' }}">
                            <span wire:loading.remove wire:target="save">
                                @if($saved)
                                    <i class="fa-solid fa-check mr-2"></i>
                                    Saved!
                                @else
                                    <i class="fa-solid fa-save mr-2"></i>
                                    Save Theme Settings
                                @endif
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Live Preview & Info --}}
        <div class="col-span-4">
            {{-- Live Preview Card --}}
            <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 mb-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-eye text-primary mr-2"></i>
                    Live Preview
                </h3>

                <div class="space-y-4">
                    {{-- Preview Header --}}
                    <div class="p-4 rounded-md" style="background-color: {{ $colorPrimary }};">
                        <h1 class="font-bold text-white mb-2"
                            style="font-family: '{{ $fontOutfit }}', sans-serif; font-size: {{ ($fontSizeH1Px ?? 48) }}px;">
                            Your Brand Name
                        </h1>
                        <p class="text-white/90 text-sm"
                            style="font-family: '{{ $fontSans }}', sans-serif; font-size: {{ ($fontSizePPx ?? 16) }}px;">
                            This is how your primary color looks with your chosen fonts.
                        </p>
                    </div>

                    {{-- Preview Button --}}
                    <div class="p-4 bg-gray-50 rounded-md">
                        <button
                            class="px-4 py-2 rounded-lg text-white font-medium transition-all duration-200 hover:opacity-90 text-sm"
                            style="background-color: {{ $colorSecondary }}; font-family: '{{ $fontSans }}', sans-serif; font-size: {{ ($fontSizePPx ?? 16) }}px;">
                            <i class="fa-solid fa-rocket mr-2"></i>
                            Secondary Color Button
                        </button>
                    </div>

                    {{-- Preview Card --}}
                    <div class="p-4 rounded-md border-2"
                        style="border-color: {{ $colorNatural }}; background-color: {{ $colorNatural }}20;">
                        <h2 class="font-semibold mb-2"
                            style="font-family: '{{ $fontOutfit }}', sans-serif; font-size: {{ ($fontSizeH2Px ?? 36) }}px;">
                            Preview Card
                        </h2>
                        <p class="text-gray-700 text-sm"
                            style="font-family: '{{ $fontSans }}', sans-serif; font-size: {{ ($fontSizePPx ?? 16) }}px;">
                            This is a preview of how your selected colors and fonts will look on the website.
                        </p>
                    </div>

                    {{-- Typography Preview --}}
                    <div class="p-4 bg-white rounded-md border border-gray-200">
                        <h4 class="text-xs font-semibold text-gray-900 mb-2">Typography Preview</h4>
                        <div class="space-y-1">
                            <h1 class="leading-tight"
                                style="font-family: '{{ $fontOutfit }}', sans-serif; font-size: {{ ($fontSizeH1Px ?? 48) }}px; color: {{ $colorPrimary }};">
                                Heading 1</h1>
                            <h2 class="leading-tight"
                                style="font-family: '{{ $fontOutfit }}', sans-serif; font-size: {{ ($fontSizeH2Px ?? 36) }}px; color: {{ $colorPrimary }};">
                                Heading 2</h2>
                            <h3 class="leading-tight"
                                style="font-family: '{{ $fontOutfit }}', sans-serif; font-size: {{ ($fontSizeH3Px ?? 24) }}px; color: {{ $colorPrimary }};">
                                Heading 3</h3>
                            <p class="text-sm"
                                style="font-family: '{{ $fontSans }}', sans-serif; font-size: {{ ($fontSizePPx ?? 16) }}px; color: #374151;">
                                This is a paragraph text preview with your chosen font.</p>
                        </div>
                    </div>

                    {{-- Color Swatches --}}
                    <div class="p-4 bg-gray-50 rounded-md">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Color Palette</h4>
                        <div class="flex space-x-2">
                            <div class="flex-1">
                                <div class="h-10 rounded-md mb-1" style="background-color: {{ $colorPrimary }};"></div>
                                <p class="text-xs text-gray-600">Primary</p>
                            </div>
                            <div class="flex-1">
                                <div class="h-10 rounded-md mb-1" style="background-color: {{ $colorSecondary }};">
                                </div>
                                <p class="text-xs text-gray-600">Secondary</p>
                            </div>
                            <div class="flex-1">
                                <div class="h-10 rounded-md mb-1" style="background-color: {{ $colorNatural }};"></div>
                                <p class="text-xs text-gray-600">Natural</p>
                            </div>
                        </div>
                    </div>

                    {{-- Header & Footer Preview --}}
                    <div class="p-4 bg-gray-50 rounded-md space-y-3">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Header & Footer</h4>
                        <div class="p-3 rounded-md border border-gray-200" style="background-color: {{ $headerBg }};">
                            <p class="text-sm font-medium" style="color: {{ $headerText }};">Header preview</p>
                        </div>
                        <div class="p-3 rounded-md border border-gray-200" style="background-color: {{ $footerBg }};">
                            <p class="text-sm font-medium" style="color: {{ $footerText }};">Footer preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Load Google Fonts dynamically when they change
        window.loadGoogleFont = function (fontName) {
            if (!fontName || fontName.trim() === '') return;

            // Normalize font name for URL encoding - replace spaces with + and encode special characters
            const fontNameNormalized = fontName.trim();
            const fontNameEncoded = encodeURIComponent(fontNameNormalized).replace(/%20/g, '+');
            const fontUrl = `https://fonts.googleapis.com/css2?family=${fontNameEncoded}:wght@400;500;600;700&display=swap`;

            // Create a unique ID for this font based on its normalized name
            const fontId = `google-font-${fontNameNormalized.toLowerCase().replace(/\s+/g, '-')}`;

            // Check if font is already loaded by looking for the link with our unique ID
            let existingLink = document.getElementById(fontId);

            // Also check by href pattern (for backward compatibility)
            if (!existingLink) {
                const links = document.querySelectorAll('link[href*="fonts.googleapis.com"]');
                links.forEach(link => {
                    if (link.href.includes(fontNameNormalized.replace(/\s+/g, '+'))) {
                        existingLink = link;
                    }
                });
            }

            if (!existingLink) {
                const link = document.createElement('link');
                link.id = fontId;
                link.rel = 'stylesheet';
                link.href = fontUrl;
                link.crossOrigin = 'anonymous';

                // Add to head before other scripts to ensure proper loading order
                const firstLink = document.head.querySelector('link[rel="stylesheet"]');
                if (firstLink) {
                    document.head.insertBefore(link, firstLink);
                } else {
                    document.head.appendChild(link);
                }

                // Wait for font to load
                link.onload = function () {
                    // Force font to apply by dispatching a custom event
                    document.dispatchEvent(new CustomEvent('fontLoaded', { detail: { fontName } }));
                };
                link.onerror = function () {
                    // Try alternative loading method
                    const alternativeUrl = `https://fonts.googleapis.com/css2?family=${fontNameNormalized.replace(/\s+/g, '+')}:wght@400;500;600;700&display=swap`;
                    if (alternativeUrl !== fontUrl) {
                        link.href = alternativeUrl;
                    }
                };
            } else {
                // Font already loaded, but ensure it's applied
                document.dispatchEvent(new CustomEvent('fontLoaded', { detail: { fontName } }));
            }
        };

        // Listen for font changes from Livewire
        Livewire.on('font-changed', ({ fontName }) => {
            if (fontName) {
                window.loadGoogleFont(fontName);
            }
        });

        // Load fonts when Livewire component updates
        // Load fonts when Livewire component updates
        Livewire.hook('morph.updated', ({ component }) => {
            const fontSansEl = document.getElementById('fontSans');
            const fontOutfitEl = document.getElementById('fontOutfit');
            if (fontSansEl && fontSansEl.value) {
                window.loadGoogleFont(fontSansEl.value);
            }
            if (fontOutfitEl && fontOutfitEl.value) {
                window.loadGoogleFont(fontOutfitEl.value);
            }
        });

        // Load fonts on initial page load
        document.addEventListener('DOMContentLoaded', () => {
            const fontSansEl = document.getElementById('fontSans');
            const fontOutfitEl = document.getElementById('fontOutfit');
            if (fontSansEl && fontSansEl.value) {
                window.loadGoogleFont(fontSansEl.value);
            }
            if (fontOutfitEl && fontOutfitEl.value) {
                window.loadGoogleFont(fontOutfitEl.value);
            }

            // Sync color picker with text input
            ['Primary', 'Secondary', 'Natural'].forEach(colorType => {
                const colorPicker = document.getElementById(`color${colorType}`);
                const colorText = document.getElementById(`color${colorType}Text`);

                if (colorPicker && colorText) {
                    colorPicker.addEventListener('input', (e) => {
                        colorText.value = e.target.value;
                    });

                    colorText.addEventListener('input', (e) => {
                        if (/^#[0-9A-Fa-f]{6}$/i.test(e.target.value)) {
                            colorPicker.value = e.target.value;
                        }
                    });
                }
            });

            // Sync header & footer color pickers
            [
                { picker: 'headerBg', text: 'headerBgText' },
                { picker: 'headerText', text: 'headerTextText' },
                { picker: 'footerBg', text: 'footerBgText' },
                { picker: 'footerText', text: 'footerTextText' }
            ].forEach(({ picker, text }) => {
                const colorPicker = document.getElementById(picker);
                const colorText = document.getElementById(text);
                if (colorPicker && colorText) {
                    colorPicker.addEventListener('input', (e) => { colorText.value = e.target.value; });
                    colorText.addEventListener('input', (e) => {
                        if (/^#[0-9A-Fa-f]{6}$/i.test(e.target.value)) colorPicker.value = e.target.value;
                    });
                }
            });
        });

        // Reset saved state after 1 second
        Livewire.on('notify', () => {
            setTimeout(() => {
                @this.set('saved', false);
            }, 1000);
        });
    </script>
@endpush