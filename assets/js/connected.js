document.addEventListener('DOMContentLoaded', function() {
    fetchWidgetData();
    var logoutButton = document.getElementById('logoutButton');
    logoutButton.addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', cookiegoData.ajax_url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                window.location.href = '?page=CookieGo&view=settings';
            }
        };
        xhr.send('action=handle_logout&nonce=' + cookiegoData.nonce);
    });

    function fetchWidgetData() {
        var cookiesWidgetId = cookiegoData.widget_id;
        
        fetch('https://obcb26vr7i.execute-api.us-east-1.amazonaws.com/wordpress/getWidgetData', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cookiesWidgetId: cookiesWidgetId })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Check the actual structure of the data received
            if (data && data.data && data.data.widget) {
                const widget = data.data.widget;
                
                // Update GDPR
                const regulationElement = document.querySelector('.regulation');
                regulationElement.textContent = widget.law.toUpperCase();
                
                // Update Location
                const locationElement = document.querySelector('.location');
                switch (widget.geo.choice) {
                    case 'world':
                        locationElement.textContent = 'Worldwide';
                        break;
                    case 'eu':
                        locationElement.textContent = 'EU Countries & UK';
                        break;
                    case 'custom':
                        locationElement.textContent = widget.geo.list.join(', ');
                        break;
                    default:
                        locationElement.textContent = 'Not Specified';
                        break;
                }
                
                const cookiesElement = document.querySelector('.cookies-count');
                let totalCookies = 0;
                if (widget.cookiesData) {
                    totalCookies = Object.values(widget.cookiesData).reduce((acc, category) => {
                        const num = Object.keys(category).length ? 1 : 0;
                        return acc + num;
                    }, 0);
                }
                cookiesElement.textContent = totalCookies || '0';
            }
        })
        .catch(error => {
            console.error('Error fetching widget data:', error);
        });
    }
});
