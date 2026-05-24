document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.see-enrolled');
  const container = document.querySelector('.enrolled-members-container');
  const membersList = document.getElementById('enrolled-members-list');

  container.style.display = 'none';

  buttons.forEach(button => {
    button.addEventListener('click', async (event) => {
      const classId = button.getAttribute('data-class-id');

      // Toggle visibility if it's already loaded
      if (container.style.display === 'block' && button.textContent === 'Hide Enrolled Members') {
        container.style.display = 'none';
        button.textContent = 'See Enrolled Members';
        return;
      }

      // If another non-active button was clicked and the container is visible, reset all buttons to have "See Enrolled Members" text
      if (container.style.display === 'block') {
        buttons.forEach(btn => btn.textContent = 'See Enrolled Members');
      }

      // Fetch the data from your new endpoint
      try {
        const response = await fetch(`../actions/get_enrolled_members.php?class_id=${classId}`);
        const members = await response.json();

        // Clear previous content
        membersList.replaceChildren(); 

        if (members.length === 0) {
          const p = document.createElement('p');
          p.textContent = 'No members enrolled yet.';
          membersList.appendChild(p);
        } else {
          const table = document.createElement('table');
          table.className = 'enrolled-members-table';
          
          const thead = document.createElement('thead');
          const headerRow = document.createElement('tr');
          ['Photo', 'Name', 'Username', 'Email', 'Status'].forEach(text => {
            const th = document.createElement('th');
            th.textContent = text;
            headerRow.appendChild(th);
          });
          thead.appendChild(headerRow);
          table.appendChild(thead);
          
          const tbody = document.createElement('tbody');
          members.forEach(member => {
            const tr = document.createElement('tr');
            
            const tdPhoto = document.createElement('td');
            const img = document.createElement('img');
            img.src = member.photo_url;
            img.alt = 'Profile Photo';
            tdPhoto.appendChild(img);
            tr.appendChild(tdPhoto);

            const tdName = document.createElement('td');
            tdName.textContent = `${member.first_name} ${member.last_name}`;
            tr.appendChild(tdName);
            
            const tdUsername = document.createElement('td');
            tdUsername.textContent = member.username;
            tr.appendChild(tdUsername);
            
            const tdEmail = document.createElement('td');
            tdEmail.textContent = member.email;
            tr.appendChild(tdEmail);
            
            const tdStatus = document.createElement('td');
            tdStatus.textContent = member.status;
            tr.appendChild(tdStatus);
            
            tbody.appendChild(tr);
          });
          
          table.appendChild(tbody);
          membersList.appendChild(table);
        }

        // Show the container and change button text
        container.style.display = 'block';
        button.textContent = 'Hide Enrolled Members';
        
      } catch (error) {
        console.error('Failed to fetch members:', error);
        membersList.replaceChildren();
        const errorMsg = document.createElement('p');
        errorMsg.textContent = 'Error loading members.';
        membersList.appendChild(errorMsg);
        container.style.display = 'block';
      }
    });
  });
});