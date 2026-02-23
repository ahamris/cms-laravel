{{-- Alpine component: refresh chapter dropdown when category changes. Pass data-chapters-url (by-category API URL) on the element with x-data="courseChapterForm()" --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('courseChapterForm', () => ({
        chaptersUrl: '',
        init() {
            this.chaptersUrl = this.$el.dataset.chaptersUrl || '';
            const catSelect = document.querySelector('select[name="course_category_id"]');
            if (catSelect) {
                catSelect.addEventListener('change', () => this.refreshChapterSelect());
            }
        },
        async refreshChapterSelect() {
            const catSelect = document.querySelector('select[name="course_category_id"]');
            const sel = document.getElementById('course_id');
            if (!catSelect || !sel) return;
            if (!catSelect.value) {
                sel.innerHTML = '<option value="">Select chapter</option>';
                sel.value = '';
                return;
            }
            const res = await fetch(this.chaptersUrl + '?course_category_id=' + encodeURIComponent(catSelect.value));
            const data = await res.json();
            while (sel.options.length > 1) sel.removeChild(sel.options[1]);
            (data.chapters || []).forEach(ch => {
                const opt = document.createElement('option');
                opt.value = ch.id;
                opt.textContent = ch.name;
                sel.appendChild(opt);
            });
            sel.value = '';
        }
    }));
});
</script>
