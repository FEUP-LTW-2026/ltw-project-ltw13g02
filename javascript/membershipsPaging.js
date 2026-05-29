const membershipTableBody = document.getElementById('membership-table-body');
const nextPageButton = document.getElementById('next-membership-page');
const prevPageButton = document.getElementById('prev-membership-page');

async function getMembershipPage() {
    membershipTableBody.innerHTML = ''; // clear current rows
    const membershipPage = membershipTableBody.dataset.page;
    const membershipsPerPage = membershipTableBody.dataset.membershipsPerPage;
    const csrfToken = membershipTableBody.dataset.csrfToken;

    const response = await fetch('../api/get_membership_page.php?page=' + membershipPage + '&per_page=' + membershipsPerPage);
    const memberships = await response.json();

    memberships.forEach(membership => {
        const tierName = document.createElement('td');
        tierName.textContent = membership.tierName;

        const monthlyFee = document.createElement('td');
        monthlyFee.textContent = membership.monthlyFee + '€';

        const status = document.createElement('td');
        const statusSpan = document.createElement('span');
        statusSpan.classList.add('status');
        statusSpan.textContent = membership.status;
        status.appendChild(statusSpan);

        const startTimestamp = document.createElement('td');
        startTimestamp.textContent = membership.startTimestamp;

        const endTimestamp = document.createElement('td');
        endTimestamp.textContent = membership.endTimestamp;

        const actions = document.createElement('td');
        actions.classList.add('actions');

        if (membership.status === 'active' || membership.status === 'planned' || membership.status === 'pending') {

             if (membership.status === 'pending') { // Add a pay now button for pending (not paid) memberships
                  
                const form = document.createElement('form');
                form.action = '../actions/action_pay_membership.php';
                form.method = 'post';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const membershipIdInput = document.createElement('input');
                membershipIdInput.type = 'hidden';
                membershipIdInput.name = 'membership_id';
                membershipIdInput.value = membership.id;
                form.appendChild(membershipIdInput);

                const payButton = document.createElement('button');
                payButton.type = 'submit';
                payButton.classList.add('btn', 'medium', 'blue');
                payButton.textContent = 'Pay Now';
                form.appendChild(payButton);
                
                actions.appendChild(form);
            }

            if (membership.nextTierId !== null) { // Add an upgrade membership tier button
                const form = document.createElement('form');
                form.action = '../actions/action_upgrade_membership.php';
                form.method = 'post';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const membershipIdInput = document.createElement('input');
                membershipIdInput.type = 'hidden';
                membershipIdInput.name = 'membership_id';
                membershipIdInput.value = membership.id;
                form.appendChild(membershipIdInput);

                const nextTierIdInput = document.createElement('input');
                nextTierIdInput.type = 'hidden';
                nextTierIdInput.name = 'next_tier_id';
                nextTierIdInput.value = membership.nextTierId;
                form.appendChild(nextTierIdInput);

                const upgradeButton = document.createElement('button');
                upgradeButton.type = 'submit';
                upgradeButton.classList.add('btn', 'medium', 'available');
                upgradeButton.textContent = 'Upgrade to ' + membership.nextTierName;
                form.appendChild(upgradeButton);
                
                actions.appendChild(form);
            }
            
            // Add a cancel button for active, planned and pending memberships        
            const form = document.createElement('form');
            form.action = '../actions/action_cancel_membership.php';
            form.method = 'post';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const membershipIdInput = document.createElement('input');
            membershipIdInput.type = 'hidden';
            membershipIdInput.name = 'membership_id';
            membershipIdInput.value = membership.id;
            form.appendChild(membershipIdInput);

            const cancelButton = document.createElement('button');
            cancelButton.type = 'submit';
            cancelButton.classList.add('btn', 'medium', 'warning');
            cancelButton.textContent = 'Cancel Membership';
            form.appendChild(cancelButton);

            actions.appendChild(form);
        }

        const row = document.createElement('tr');
        row.appendChild(tierName);
        row.appendChild(monthlyFee);
        row.appendChild(status);
        row.appendChild(startTimestamp);
        row.appendChild(endTimestamp);
        row.appendChild(actions);

        membershipTableBody.appendChild(row);
    });
}

if (membershipTableBody) {
    const membershipsPerPage = Number(membershipTableBody.dataset.membershipsPerPage);
    const numMemberships = Number(membershipTableBody.dataset.numMemberships);
    
    document.addEventListener('DOMContentLoaded', async () => {
        await getMembershipPage();
        prevPageButton.style.display = 'none';

        if (membershipsPerPage >= numMemberships) {
            nextPageButton.style.display = 'none';
        }
    });
}

if (nextPageButton) {
    nextPageButton.addEventListener('click', async (event) => {
        prevPageButton.style.display = 'inline-block';

        const currentPage = Number(membershipTableBody.dataset.page);
        const numMemberships = Number(membershipTableBody.dataset.numMemberships);
        const perPage = Number(membershipTableBody.dataset.membershipsPerPage);
        const totalPages = Math.ceil(numMemberships / perPage);

        if (currentPage >= totalPages) return;
        if (currentPage + 1 >= totalPages) nextPageButton.style.display = 'none';

        membershipTableBody.dataset.page = String(currentPage + 1); // update page

        await getMembershipPage();
    });
}

if (prevPageButton) {

    const currentPage = Number(membershipTableBody.dataset.page);
    prevPageButton.addEventListener('click', async (event) => {
        nextPageButton.style.display = 'inline-block';
        const currentPage = Number(membershipTableBody.dataset.page);

        if (currentPage <= 1) return;
        if (currentPage - 1 <= 1) prevPageButton.style.display = 'none';

        membershipTableBody.dataset.page = String(currentPage - 1); // update page
        await getMembershipPage(); 
    });
}
