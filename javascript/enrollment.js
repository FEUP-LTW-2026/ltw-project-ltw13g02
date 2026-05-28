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

function showMessage(message, type) {
    const container = document.querySelector('.messages');
    const article = document.createElement('article');
    article.className = `message ${type}`;
    article.textContent = message;

    if (container) {
        container.appendChild(article);
    } else {
        const div = document.createElement('div');
        div.className = 'container messages';
        div.appendChild(article);
        document.body.insertBefore(div, document.body.firstChild);
        setTimeout(() => {
            div.remove();
        }, 2000);
    }
}

// TODO: move the top messages to a generic js file

document.querySelectorAll('.scheduled-classes').forEach(list => {
    list.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const classId = formData.get('class_id');
        const csrfToken = formData.get('csrf_token');

        const response = await fetch(apiMap[form.dataset.action], { method: 'POST', body: formData });
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

            // Swap form
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

