document.addEventListener('keydown', (event) => {
    if (event.keyCode === 27) {
        $('.uk-modal.uk-open').removeClass('uk-open').attr('aria-hidden', true).css('display', 'none');
    }
});