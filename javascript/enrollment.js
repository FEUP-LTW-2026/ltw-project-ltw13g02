const apiMap = {
    'enroll': '../api/action_enrollment.php',
    'cancel': '../api/action_cancel_enrollment.php',
    'join_waitlist': '../api/action_join_waitlist.php',
    'leave_waitlist':'../api/action_leave_waitlist.php',
};

const nextFormMap = {
    'enrolled':     { action: 'cancel',       label: 'Cancel Enrollment', css: 'btn medium warning outline' },
    'waitlisted':   { action: 'leave_waitlist', label: 'Leave Waitlist',  css: 'btn medium warning outline' },
    'cancelled':    { action: 'enroll',        label: 'Enroll Now',       css: 'btn medium blue' },
    'left_waitlist':{ action: 'enroll',        label: 'Enroll Now',       css: 'btn medium blue' },
    'cancelled_full': { action: 'join_waitlist', label: 'Join Waitlist',  css: 'btn medium blue' },
};

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
                    p.textContent = '';
                    const icon = document.createElement('span');
                    icon.className = 'symbol';
                    icon.textContent = 'person';
                    p.appendChild(icon);
                    p.appendChild(document.createTextNode(` ${data.current_enrollment}/${data.capacity} enrolled`));
                }
            });

            const isFull = data.current_enrollment >= data.capacity;
            const resolvedStatus = (data.status === 'cancelled' && isFull) ? 'cancelled_full' : data.status;
            const next = nextFormMap[resolvedStatus];
            const newForm = document.createElement('form');
            newForm.dataset.action = next.action;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken;

            const classInput = document.createElement('input');
            classInput.type = 'hidden';
            classInput.name = 'class_id';
            classInput.value = classId;

            const btn = document.createElement('button');
            btn.type = 'submit';
            btn.className = next.css;
            btn.textContent = next.label;

            newForm.appendChild(csrfInput);
            newForm.appendChild(classInput);
            newForm.appendChild(btn);

            form.replaceWith(newForm);
        }
    });
});

