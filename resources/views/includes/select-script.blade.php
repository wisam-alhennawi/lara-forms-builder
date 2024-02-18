@once
<script>

const initStyledSelect = () => {
    Alpine.data('lfbStyledSelect', (options) => ({
        search: '',
        selectedLabel() {
            if (this.value) {
                const selectedItem = this.options.find((item) => {
                    return item.value === this.value;
                });
                if (selectedItem) {
                    return selectedItem.label;
                }
            }
            return '';
        },
        placeholder: '{{ __('Please select...') }}',
        options: options,
        show: false,
        filteredOptions() {
            return this.options.filter((item) => {
                return item.label.toLowerCase().includes(this.search.toLowerCase());
            });
        },
        selectOption(item) {
            this.value = item.value;
            this.resetSearch();
            this.close();
        },
        removeSelected() {
            this.value = null;
        },
        resetSearch() {
            this.search = '';
        },
        open() {
            this.show = true;
            this.resetSearch();
        },
        close() {
            this.show = false;
        },
        toggle() {
            this.show = !this.show;
        },
    }))
};

if (typeof Alpine !== 'undefined') {
    initStyledSelect();
} else {
    document.addEventListener('alpine:init', initStyledSelect);
}
</script>
@endonce
