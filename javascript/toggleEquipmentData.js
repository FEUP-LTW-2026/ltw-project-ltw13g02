document.addEventListener('DOMContentLoaded', () => {
  const zones = document.querySelectorAll('.equipment-zone');
  const reservationForms = document.querySelectorAll('.reservation-form');

  if (!zones.length && !reservationForms.length) return;

  const hideAllZones = () => zones.forEach((zone) => {
    zone.hidden = true;
  });

  const hideAllReservationForms = () => reservationForms.forEach((form) => {
    form.hidden = true;
  });

  hideAllZones();
  hideAllReservationForms();

  document.addEventListener('click', (event) => {
    const zoneButton = event.target.closest('.zone-toggle[data-target]');
    if (zoneButton) {
      const zoneId = zoneButton.dataset.target;
      if (!zoneId) return;

      const targetZone = document.getElementById(zoneId);
      if (!targetZone) return;

      const willShow = targetZone.hidden;
      hideAllZones();
      hideAllReservationForms();
      targetZone.hidden = !willShow;
      return;
    }

    const reservationButton = event.target.closest('.reservation-toggle[data-target]');
    if (!reservationButton) return;

    const reservationId = reservationButton.dataset.target;
    if (!reservationId) return;

    const targetForm = document.getElementById(reservationId);
    if (!targetForm) return;

    const willShow = targetForm.hidden;
    hideAllReservationForms();
    targetForm.hidden = !willShow;
  });
});
