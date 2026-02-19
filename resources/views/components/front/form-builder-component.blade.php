@if($form && $form->is_active)
<div class="{{ $wrapperClass ?? 'form-builder-wrapper' }}">
    {{-- Title and Subtitle --}}
    @if($title || $subtitle)
    <div class="mb-6">
        @if($title)
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
            {{ $title }}
        </h2>
        @endif
        @if($subtitle)
        <p class="text-gray-600">
            {{ $subtitle }}
        </p>
        @endif
    </div>
    @endif

    {{-- Form --}}
    <form id="form-builder-{{ $form->identifier }}" 
          class="{{ $formClass ?? 'space-y-5' }}" 
          method="POST" 
          action="{{ route('form-builder.submit', ['identifier' => $form->identifier]) }}">
        @csrf

        @foreach($form->fields ?? [] as $field)
            <x-form.form-field 
                :field="$field" 
                :value="old($field['name'] ?? '')" />
        @endforeach

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit" 
                    class="w-full px-6 py-3 bg-primary hover:bg-primary/90 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <i class="fa fa-paper-plane mr-2"></i>
                {{ $form->submit_button_text ?? 'Submit' }}
            </button>
        </div>
    </form>

    {{-- Success/Error Messages --}}
    <div id="form-message-{{ $form->identifier }}" class="mt-4 hidden"></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-builder-{{ $form->identifier }}');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        const messageDiv = document.getElementById('form-message-{{ $form->identifier }}');
        
        // Disable submit button
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Sending...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success message
                messageDiv.className = 'mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg';
                messageDiv.innerHTML = '<i class="fa fa-check-circle mr-2"></i>' + (data.message || '{{ $form->success_message ?? "Thank you for your submission!" }}');
                messageDiv.classList.remove('hidden');
                
                // Reset form
                form.reset();
                
                @if($form->redirect_url)
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ $form->redirect_url }}';
                    }, 2000);
                @endif
            } else {
                // Show error message
                messageDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg';
                messageDiv.innerHTML = '<i class="fa fa-exclamation-circle mr-2"></i>' + (data.message || 'An error occurred. Please try again.');
                
                // Show field errors if available
                if (data.errors) {
                    Object.keys(data.errors).forEach(fieldName => {
                        const field = form.querySelector('[name="' + fieldName + '"]');
                        if (field) {
                            field.classList.add('border-red-500');
                            const errorDiv = document.createElement('p');
                            errorDiv.className = 'mt-1 text-sm text-red-600';
                            errorDiv.textContent = data.errors[fieldName][0];
                            field.parentElement.appendChild(errorDiv);
                        }
                    });
                }
                
                messageDiv.classList.remove('hidden');
            }
        } catch (error) {
            // Show error message
            messageDiv.className = 'mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg';
            messageDiv.innerHTML = '<i class="fa fa-exclamation-circle mr-2"></i> An error occurred. Please try again.';
            messageDiv.classList.remove('hidden');
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });
});
</script>
@endpush
@endif

