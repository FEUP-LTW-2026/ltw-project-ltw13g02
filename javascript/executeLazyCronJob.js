// On any user page load, the lazy cron job is executed, meaning that the database internally maintains its consistency
// The database will be updated anytime a user accesses the main page.
// That said its not as reliable as a real cron job, but it's a good solution for this project
// since the server won't be running 24/7

document.addEventListener('DOMContentLoaded', function(){
    fetch('../utils/lazy_cron_job.php')
});