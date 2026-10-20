/* Ask before submitting a form marked with data-confirm="<question>". */
document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-confirm]')
    if (form && !window.confirm(form.dataset.confirm)) {
        event.preventDefault()
    }
})
