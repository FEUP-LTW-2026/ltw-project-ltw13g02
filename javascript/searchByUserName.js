// Search functionality for filtering users by username
const searchBar = document.getElementById('search-bar');
const tableRows = document.querySelectorAll('.data-table tbody tr');

if (searchBar) {
  searchBar.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();

    tableRows.forEach(row => {
      const usernameCell = row.querySelector('td:first-child');
      const username = usernameCell ? usernameCell.textContent.toLowerCase() : '';

      if (username.includes(searchTerm)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
}