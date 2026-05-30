const dialogPasswordInput = document.getElementById('dialog-password-input');
const dialogRequirements = {
  length:    document.getElementById('dialog-req-length'),
  uppercase: document.getElementById('dialog-req-uppercase'),
  lowercase: document.getElementById('dialog-req-lowercase'),
  digit:     document.getElementById('dialog-req-digit'),
  special:   document.getElementById('dialog-req-special'),
};

dialogPasswordInput?.addEventListener('input', () => {
  const val = dialogPasswordInput.value;
  toggle(dialogRequirements.length,    val.length >= 8);
  toggle(dialogRequirements.uppercase, /[A-Z]/.test(val));
  toggle(dialogRequirements.lowercase, /[a-z]/.test(val));
  toggle(dialogRequirements.digit,     /\d/.test(val));
  toggle(dialogRequirements.special,   /[!@#$%^&*]/.test(val));
});

function toggle(el, met) {
  el?.classList.toggle('met', met);
}
