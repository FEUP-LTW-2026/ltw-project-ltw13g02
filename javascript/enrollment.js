const apiMap = {
    'enroll': '../api/action_enrollment.php',
    'cancel': '../api/action_cancel_enrollment.php',
    'join_waitlist': '../api/action_join_waitlist.php',
    'leave_waitlist':'../api/action_leave_waitlist.php',
};

const nextFormMap = {
    'enrolled':    { action: 'cancel', label: 'Cancel Enrollment', css: 'btn medium warning outline' },
    'waitlisted':  { action: 'leave_waitlist', label: 'Leave Waitlist', css: 'btn medium warning outline' },
    'cancelled':   { action: 'enroll', label: 'Enroll Now', css: 'btn medium blue' },
    'left_waitlist':{ action: 'enroll', label: 'Enroll Now', css: 'btn medium blue' },
};

// TODO: move the top messages to a generic js file

document.querySelectorAll('.scheduled-classes').forEach(list => {
    list.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const classId = form.querySelector('input[name="class_id"]').value;
        const csrfToken = form.querySelector('input[name="csrf_token"]').value;

        const response = await fetch(apiMap[form.dataset.action], {
                method: 'post',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: encodeForAjax({class_id: classId, csrf_token: csrfToken}) });
        const data = await response.json();

        showMessage(data.message, data.messageType);

        if (data.messageType === 'success') {

            // Update capacity
            const card = form.closest('.card');
            card.querySelectorAll('p').forEach(p => {
                if (p.textContent.includes('/')) {
                    p.innerHTML = `<span class="symbol">person</span> ${data.current_enrollment}/${data.capacity} enrolled`;
                }
            });

            const next = nextFormMap[data.status];
            form.outerHTML = `
                <form data-action="${next.action}">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="class_id"   value="${classId}">
                    <button class="btn ${next.css}" type="submit">${next.label}</button>
                </form>`;
        }
    });
});

