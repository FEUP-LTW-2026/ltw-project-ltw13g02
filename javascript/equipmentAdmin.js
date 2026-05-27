const toggleButton = document.querySelectorAll('button.toggle-form');

toggleButton.forEach(button => {
  button.addEventListener('click', () => {
    const form = button.nextElementSibling;
    if (form) {
        form.toggleAttribute('hidden');
    }
  })
})