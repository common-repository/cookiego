document.addEventListener('DOMContentLoaded', function() {
    var connectBtn = document.getElementById('connectDashboardBtn');
    connectBtn.addEventListener('click', function() {
        window.location.href = '?page=CookieGo&view=email_exists';
    });
});
