const attendedTableBody = document.getElementById('attended-classes-table-body');
const nextPageButtonClasses = document.getElementById('next-attended-classes-page');
const prevPageButtonClasses = document.getElementById('prev-attended-classes-page');

async function getAttendedClassesPage() {

    attendedTableBody.innerHTML = ''; // clear current rows
    const attendedPage = attendedTableBody.dataset.page;
    const attendedClassesPerPage = attendedTableBody.dataset.attendedClassesPerPage;
    const csrfToken = attendedTableBody.dataset.csrfToken;

    const response = await fetch('../api/classes/get_attended_classes_page.php?page=' + attendedPage + '&per_page=' + attendedClassesPerPage);
    const attendedClasses = await response.json();

    attendedClasses.forEach(attendedClass => {
        const classNameTd = document.createElement('td');
        classNameTd.textContent = attendedClass.name;

        const trainerNameTd = document.createElement('td');
        trainerNameTd.textContent = attendedClass.trainerName;

        const startTimestampTd = document.createElement('td');
        startTimestampTd.textContent = attendedClass.startTimestamp;

        const endTimestampTd = document.createElement('td');
        endTimestampTd.textContent = attendedClass.endTimestamp;

        const actionsTd = document.createElement('td');
        actionsTd.classList.add('actions');

        if (!attendedClass.hasReview) {
            // link to the class page's reviews section
            const link = document.createElement('a');
            link.href = '../pages/class.php?id=' + attendedClass.classTypeId + '#reviews';
            link.classList.add('btn', 'medium', 'blue');
            link.textContent = 'Leave a Review';
            actionsTd.appendChild(link);
        }

        const row = document.createElement('tr');
        row.appendChild(classNameTd);
        row.appendChild(startTimestampTd);
        row.appendChild(endTimestampTd);
        row.appendChild(actionsTd);
        attendedTableBody.appendChild(row);
    });
}

if (attendedTableBody) {
    const attendedClassesPerPage = Number(attendedTableBody.dataset.attendedClassesPerPage);
    const numAttendedClasses = Number(attendedTableBody.dataset.numAttendedClasses);
    document.addEventListener('DOMContentLoaded', async () => {
        await getAttendedClassesPage();
        if (prevPageButtonClasses) prevPageButtonClasses.style.display = 'none';

        if (attendedClassesPerPage >= numAttendedClasses) {
            if (nextPageButtonClasses) nextPageButtonClasses.style.display = 'none';
        }
    });
}

if (nextPageButtonClasses) {
    nextPageButtonClasses.addEventListener('click', async (event) => {
        if (!attendedTableBody) return;
        if (prevPageButtonClasses) prevPageButtonClasses.style.display = 'inline-block';

        const currentPage = Number(attendedTableBody.dataset.page);
        const numAttended = Number(attendedTableBody.dataset.numAttendedClasses);
        const perPage = Number(attendedTableBody.dataset.attendedClassesPerPage);
        const totalPages = Math.ceil(numAttended / perPage);

        if (currentPage >= totalPages) return;
        if (currentPage + 1 >= totalPages) nextPageButtonClasses.style.display = 'none';

        attendedTableBody.dataset.page = String(currentPage + 1); // update page

        await getAttendedClassesPage();
    });
}

if (prevPageButtonClasses) {
    prevPageButtonClasses.addEventListener('click', async (event) => {
        if (!attendedTableBody) return;
        if (nextPageButtonClasses) nextPageButtonClasses.style.display = 'inline-block';

        const currentPage = Number(attendedTableBody.dataset.page);
        if (currentPage <= 1) return;
        if (currentPage - 1 <= 1 && prevPageButtonClasses) prevPageButtonClasses.style.display = 'none';

        attendedTableBody.dataset.page = String(currentPage - 1); // update page

        await getAttendedClassesPage();
    });
}