const passwordInput = document.getElementById('password-input');
const requirements = {
  length:    document.getElementById('req-length'),
  uppercase: document.getElementById('req-uppercase'),
  lowercase: document.getElementById('req-lowercase'),
  digit:     document.getElementById('req-digit'),
  special:   document.getElementById('req-special'),
};

passwordInput?.addEventListener('input', () => {
  const val = passwordInput.value;
  toggle(requirements.length,    val.length >= 8);
  toggle(requirements.uppercase, /[A-Z]/.test(val));
  toggle(requirements.lowercase, /[a-z]/.test(val));
  toggle(requirements.digit,     /\d/.test(val));
  toggle(requirements.special,   /[!@#$%^&*]/.test(val));
});

function toggle(el, met) {
  el?.classList.toggle('met', met);
}
