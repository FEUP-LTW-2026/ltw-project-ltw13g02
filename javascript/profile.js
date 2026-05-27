const dialogDetails = document.getElementById('membership-details-modal');
const detailButtons = document.querySelectorAll('.see-membership-details-btn');

detailButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        if (dialogDetails) {
            const membershipId = e.target.getAttribute('data-membership-id');
            const membershipTier = e.target.getAttribute('data-membership-tier');
            const membershipDescription = e.target.getAttribute('data-membership-description');
            const status = e.target.getAttribute('data-membership-status');
            const startDate = e.target.getAttribute('data-membership-start');
            const endDate = e.target.getAttribute('data-membership-end');
            const membershipFee = e.target.getAttribute('data-membership-fee');

            document.getElementById('membership-tier').value = membershipTier;
            document.getElementById('membership-description').value = membershipDescription;
            document.getElementById('membership-status').value = status;
            document.getElementById('membership-start').value = startDate;
            document.getElementById('membership-end').value = endDate;
            document.getElementById('membership-fee').value = membershipFee;

            dialogDetails.showModal();
        }
    })
});

// Closes modal when the "Close" button is clicked
document.querySelectorAll('.btn-close-dialog').forEach(btn => {
    btn.addEventListener('click', () => {
        dialogDetails.close();
    });
});
