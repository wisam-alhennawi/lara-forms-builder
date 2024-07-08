<script>
    window.addEventListener('scroll-to-first-error', () => {
        const firstErrorElement = document.querySelector('.lfb-field-error-wrapper');
        if (firstErrorElement) {
            const headerOffset = 100;
            const elementPosition = firstErrorElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
            window.scrollTo({ top: elementPosition, behavior: 'smooth' });
        }
     });
 </script>