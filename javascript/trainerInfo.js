function toggleListEmpty(listClass, formId, message) {
    const list = document.querySelector(listClass);
    const addForm = document.getElementById(formId);
    const article = addForm.closest('article');

    if (list && list.children.length === 0) {
        list.remove();
        const p = document.createElement('p');
        p.textContent = message;
        article.insertBefore(p, addForm);
    }
}

function attachDeleteSpecializationsListener(elem) {
    elem.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const specializationId = form.querySelector('input[name="specialization_id"]').value;
        const csrfToken = form.querySelector('input[name="csrf_token"]').value;

        const response = await fetch('../api/trainers/action_remove_specialization.php', {
            method: 'post',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: encodeForAjax({ specialization_id: specializationId, csrf_token: csrfToken })
        });

        const data = await response.json();
        showMessage(data.message, data.messageType);

        if (data.messageType === 'success') {
            const card = form.parentElement.parentElement;
            card.remove();

            const select = document.querySelector('#add-specialization select[name="specialization_id"]');
            const option = document.createElement('option');
            option.value = specializationId;
            option.textContent = form.parentElement.querySelector('h3').textContent;
            select.appendChild(option);

            toggleListEmpty('.specializations', 'add-specialization', 'No specializations added yet.');
        }
    });
}

function attachDeleteCertificationsListener(elem) {
    elem.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const certificationId = form.querySelector('input[name="certification_id"]').value;
        const csrfToken = form.querySelector('input[name="csrf_token"]').value;

        const response = await fetch('../api/trainers/action_remove_certification.php', {
            method: 'post',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: encodeForAjax({ certification_id: certificationId, csrf_token: csrfToken })
        });

        const data = await response.json();
        showMessage(data.message, data.messageType);

        if (data.messageType === 'success') {
            const card = form.parentElement;
            card.remove();

            const select = document.querySelector('#add-certification select[name="certification_id"]');
            const option = document.createElement('option');
            option.value = certificationId;
            option.textContent = form.parentElement.querySelector('h3').textContent;
            select.appendChild(option);

            toggleListEmpty('.certifications', 'add-certification', 'No certifications added yet.');
        }
    });
}

// specialization create
document.getElementById('add-specialization').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const csrfToken = form.querySelector('input[name="csrf_token"]').value;
    const specializationId = form.querySelector('select[name="specialization_id"]').value;

    const response = await fetch('../api/trainers/action_add_specialization.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: encodeForAjax({ specialization_id: specializationId, csrf_token: csrfToken })
    });

    const data = await response.json();
    showMessage(data.message, data.messageType);

    if (data.messageType === 'success') {
        let specializationsList = document.querySelector('.specializations');

        if (!specializationsList) {
            specializationsList = document.createElement('ul');
            specializationsList.classList.add('specializations');
            form.parentNode.insertBefore(specializationsList, form);
            attachDeleteSpecializationsListener(specializationsList);

            const noSpecializations = document.querySelector('article p');
            if (noSpecializations) noSpecializations.remove();
        }

        const { logo, name, description, id } = data.specialization;
        const card = document.createElement('li');
        card.classList.add('card');

        const header = document.createElement('header');

        const icon = document.createElement('span');
        icon.className = 'symbol';
        icon.textContent = logo ?? 'exercise';

        const h3 = document.createElement('h3');
        h3.textContent = name;

        const deleteForm = document.createElement('form');
        deleteForm.method = 'post';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = csrfToken;

        const specIdInput = document.createElement('input');
        specIdInput.type = 'hidden';
        specIdInput.name = 'specialization_id';
        specIdInput.value = id;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'submit';
        removeBtn.className = 'btn warning remove-btn';
        removeBtn.title = 'Remove Specialization';
        const closeIcon = document.createElement('span');
        closeIcon.className = 'symbol close';
        closeIcon.textContent = 'close';
        removeBtn.appendChild(closeIcon);

        deleteForm.appendChild(csrfInput);
        deleteForm.appendChild(specIdInput);
        deleteForm.appendChild(removeBtn);

        header.appendChild(icon);
        header.appendChild(h3);
        header.appendChild(deleteForm);

        const p = document.createElement('p');
        p.textContent = description;

        card.appendChild(header);
        card.appendChild(p);

        specializationsList.appendChild(card);
        form.querySelector(`option[value="${id}"]`).remove();
    }
});

// certification create
document.getElementById('add-certification').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const csrfToken = form.querySelector('input[name="csrf_token"]').value;
    const certificationId = form.querySelector('select[name="certification_id"]').value;

    const response = await fetch('../api/trainers/action_add_certification.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: encodeForAjax({ certification_id: certificationId, csrf_token: csrfToken })
    });

    const data = await response.json();
    showMessage(data.message, data.messageType);

    if (data.messageType === 'success') {
        let certificationsList = document.querySelector('.certifications');

        if (!certificationsList) {
            certificationsList = document.createElement('ul');
            certificationsList.classList.add('certifications');
            form.parentNode.insertBefore(certificationsList, form);
            attachDeleteCertificationsListener(certificationsList);

            const noCertifications = document.querySelector('#add-certification').closest('article').querySelector('p');
            if (noCertifications) noCertifications.remove();
        }

        const { name, id } = data.certification;
        const card = document.createElement('li');
        card.classList.add('card');

        const h3 = document.createElement('h3');
        h3.textContent = name;

        const deleteForm = document.createElement('form');
        deleteForm.method = 'post';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = csrfToken;

        const certIdInput = document.createElement('input');
        certIdInput.type = 'hidden';
        certIdInput.name = 'certification_id';
        certIdInput.value = id;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'submit';
        removeBtn.className = 'btn warning remove-btn';
        removeBtn.title = 'Remove Certification';
        const closeIcon = document.createElement('span');
        closeIcon.className = 'symbol close';
        closeIcon.textContent = 'close';
        removeBtn.appendChild(closeIcon);

        deleteForm.appendChild(csrfInput);
        deleteForm.appendChild(certIdInput);
        deleteForm.appendChild(removeBtn);

        card.appendChild(h3);
        card.appendChild(deleteForm);

        certificationsList.appendChild(card);
        form.querySelector(`option[value="${id}"]`).remove();
    }
});

// Specialization delete
document.querySelectorAll('.specializations').forEach(attachDeleteSpecializationsListener);

// Certification delete
document.querySelectorAll('.certifications').forEach(attachDeleteCertificationsListener);