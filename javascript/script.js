document.getElementById('members').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const csrfToken = form.querySelector('input[name="csrf_token"]').value;
    const userId = form.querySelector('input[name="user_id"]').value;
    const userName = form.querySelector('input[name="user_name"]').value;
    const oldRoleTable = form.querySelector('input[name="old_role_table"]').value;
    const newRoleTable = form.querySelector('select[name="new_role_table"]').value;

    const response = await fetch('../api/action_changeRole.php', {
        method: 'post',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: encodeForAjax({ user_id: userId, user_name: userName, new_role_table: newRoleTable, csrf_token: csrfToken, old_role_table: oldRoleTable })
    });

    const data = await response.json();
    showMessage(data.message, data.messageType);
});
