// If needed the function can become more generic
document.addEventListener('DOMContentLoaded', () => {
  const addBtn = document.querySelector('.add-tier'); // Finds the Add Tier buttons
  const tpl = document.getElementById('tier-template'); // Finds the template
  if (!addBtn || !tpl) return; // Guard that checks if the elements really exist
  addBtn.addEventListener('click', () => {
    const clone = tpl.content.cloneNode(true);
    document.querySelector('.tiers').prepend(clone);
  })
})