function updateSummary() {
  const tierSelected = document.querySelector('input[name="tier_id"]:checked')
  const durationSelected = document.querySelector('input[name="duration"]:checked')
  const paymentTypeSelected = document.querySelector('input[name="payment_type"]:checked')

  // Update tier name
  const summaryTier = document.getElementById('summary-tier')
  if(tierSelected) {
    summaryTier.textContent = tierSelected.dataset.tierName
  }

  // Update duration name
  const summaryDuration = document.getElementById('summary-duration')
  if(durationSelected) {
    summaryDuration.textContent = durationSelected.value + ' Month' + (durationSelected.value > 1 ? 's' : '')
  }

  // Calculate and update total
  const summaryTotal = document.getElementById('summary-total')
  if (summaryTotal) {
    if (tierSelected && durationSelected && paymentTypeSelected) {
      const price = parseFloat(tierSelected.dataset.tierPrice)
      const duration = parseInt(durationSelected.value)
      if(duration < 6) {
          const upfront = document.getElementsByClassName('highlight-save')
          upfront[0].style.display = 'none'
      }
      else {
          const upfront = document.getElementsByClassName('highlight-save')
          upfront[0].style.display = 'block'
      }

      let total = price;
      if (paymentTypeSelected.value === 'upfront'  && duration > 1) {
        total = price * duration * 0.9
      }
      summaryTotal.textContent = total.toFixed(2) + '€'
    } else {
      summaryTotal.textContent = ''
    }
  }
}

// Update on page load and whenever selections change
document.addEventListener('DOMContentLoaded', updateSummary)
document.querySelectorAll('input[name="tier_id"], input[name="duration"], input[name="payment_type"]').forEach(input => {
  input.addEventListener('change', updateSummary)
})