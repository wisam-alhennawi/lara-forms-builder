<script>
    window.addEventListener('scroll-to-top-form', () => {
        setTimeout(() => {
            const firstErrorElement = document.querySelector('.lfb-field-error-wrapper');
            if (firstErrorElement) {
                return;
            }
            const formElement = document.getElementById('lara-forms-builder');
            if (formElement) {
                const headerOffset = 100;
                const elementPosition = formElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                window.scrollTo({ top: elementPosition, behavior: 'smooth' });
            }
        }, 100);
    });
</script>
