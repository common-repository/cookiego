document.addEventListener('DOMContentLoaded', function() {
    var connectBtn = document.getElementById('connectDashboardBtn');
    var signinBtn = document.getElementById('signinBtn');
    var childWindow;

    connectBtn.addEventListener('click', function() {
        var cookieId = cookiegoData.widget_id;
        var url = 'https://cookiego.myprivacylock.io/connect?platform=wordpress&cookieId=' + encodeURIComponent(cookieId);
        childWindow = window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
    });

    signinBtn.addEventListener('click', function() {
        var cookieId = cookiegoData.widget_id;
        var url = 'https://cookiego.myprivacylock.io/signin?platform=wordpress&cookieId=' + encodeURIComponent(cookieId);
        childWindow = window.open(url, '_blank', 'toolbar=0,location=0,menubar=0');
    });

    window.addEventListener('message', function(event) {
        console.log('Received data:', event.data);
        var loginData = event.data;

        fetch(cookiegoData.custom_login_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: loginData.email,
                password: loginData.password
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Login successful:', data);
            if (data.sessionToken) {
                fetch(cookiegoData.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=store_user_session&sessionToken=' + encodeURIComponent(data.sessionToken) + '&nonce=' + cookiegoData.nonce
                })
                .then(response => response.text())
                .then(response => {
                    console.log('Session token stored:', response);
                    window.location.href = cookiegoData.redirect_url;
                    if (childWindow) {
                        childWindow.close();
                    }
                });
            }
        })
        .catch(error => {
            console.error('Login failed:', error);
        });
    }, false);
});
