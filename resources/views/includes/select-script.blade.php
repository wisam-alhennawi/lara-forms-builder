<script>

document.addEventListener('alpine:init', () => {
    Alpine.data('lfbStyledSelect', (config) => ({
        search: '',
        selected: config.preselected,
        placeholder: '{{ __('Please select...') }}',
        data: config.data,
        show: false,
        filteredOptions() {
            return this.data.filter((item) => {
                return item.label.toLowerCase().includes(this.search.toLowerCase());
            });
        },
        selectOption(value) {
            this.selected = value;
            this.resetSearch();
            this.close();
        },
        removeSelected() {
            this.selected = '';
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
})
</script>
