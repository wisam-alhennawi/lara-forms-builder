<script>
       window.addEventListener('scroll-to-first-error', () => {
        //Set a timeout to wait for the error message to be rendered
        setTimeout(() => {
            const firstErrorElement = document.querySelector('.lfb-field-error-wrapper');
            if (firstErrorElement) {
                const headerOffset = 100;
                const elementPosition = firstErrorElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                window.scrollTo({ top: elementPosition, behavior: 'smooth' });
            }
        }, 100);
     });
</script>