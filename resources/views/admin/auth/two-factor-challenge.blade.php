@extends('admin.layouts.auth')

@section('title', 'Two-Factor Authentication')
@section('heading', 'Two-Factor Authentication')
@section('subheading', 'Please enter the 6-digit code from your authenticator app')
@section('image_alt', 'Two-Factor Authentication')

@section('content')

<form class="space-y-5" method="POST" action="{{ route('admin.two-factor.verify') }}">
    @csrf
    
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Authentication Code</label>
        <input id="code" 
               name="code" 
               type="text" 
               maxlength="6" 
               pattern="[0-9]{6}"
               placeholder="000000"
               class="block w-full rounded-md border border-gray-200 px-3 py-2.5 focus:outline-none focus:border-primary/50 font-mono text-center text-lg tracking-widest @error('code') border-red-500 @enderror"
               required
               autofocus>
        @error('code')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="!mt-8">
        <button type="submit" class="w-full py-2.5 px-3 bg-primary text-white font-semibold rounded-md hover:bg-primary/90 focus:outline-none transition-all duration-300 cursor-pointer">
            Verify Code
        </button>
    </div>

    <div class="text-center">
        <p class="text-sm text-slate-500">
            Lost your device?
            <a href="{{ route('admin.two-factor.recovery') }}" class="font-medium text-primary hover:underline">
                Use a recovery code
            </a>
        </p>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-sm text-slate-500 hover:text-slate-700">
            ← Back to login
        </a>
    </div>
</form>

@push('scripts')
<script>
// Auto-focus and format the code input
document.getElementById('code').addEventListener('input', function(e) {
    // Remove any non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Auto-submit when 6 digits are entered
    if (this.value.length === 6) {
        this.form.submit();
    }
});
</script>
@endpush
@endsection
