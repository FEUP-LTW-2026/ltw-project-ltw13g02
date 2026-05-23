document.addEventListener('click', (e) => {
  const btn = e.target.closest('button');
  if (!btn) return;

  // Always will be used inside a form
  const form = btn.closest('form');

  if (btn.classList.contains('add')) {
    // Button to add input fields
    // Using data attributes to know the specific container and label for the new input
    const target = btn.dataset.target;
    const label = btn.dataset.label;
    // Find the container for the target input fields, always stored inside a div with class `${target}-container`
    const container = form.querySelector(`.${target}-container`);
    if (!container) return;
    const input = document.createElement('input');
    input.type = 'text';
    input.name = `${target}[]`;
    input.placeholder = label;
    input.required = true;
    container.appendChild(input);
  }

  // Same for the remove, only needs the target to know which container to remove from
  if (btn.classList.contains('remove')) {
    const target = btn.dataset.target;
    const container = form.querySelector(`.${target}-container`);
    if (!container) return;
    const last = container.querySelector('input[type="text"]:last-child');
    if (last) container.removeChild(last);
  }
});