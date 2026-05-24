/*
  Inside a form, add buttons with class add and remove, using data attributes inside the input
  to specify the target input fields and labels, inside a div with class `${target}-container` to hold the input fields, and this script will handle the rest.
  Example:
  <form>
    <div class="email-container">
      <input type="text" name="email[]" placeholder="Email" required>
    </div>
    <button type="button" class="add" data-target="email" data-label="Email">Add Email</button>
    <button type="button" class="remove" data-target="email">Remove Email</button>
  </form>

  That its a flexible way to add and remove input fields, dinamically
*/


document.addEventListener('click', (e) => {
  const btn = e.target.closest('button');
  if (!btn) return;

  // Always will be used inside a form
  const form = btn.closest('form');

  if (btn.classList.contains('add')) {
    // Button to add input fields
    // Using data attributes to know the specific container and label used for the new input
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