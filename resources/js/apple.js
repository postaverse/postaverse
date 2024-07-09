document.addEventListener('DOMContentLoaded', (event) => {
    // Ensure apple-mobile-web-app-capable is set correctly
    let metaAppleWebApp = document.querySelector('meta[name="apple-mobile-web-app-capable"]');
    if (!metaAppleWebApp) {
        metaAppleWebApp = document.createElement('meta');
        metaAppleWebApp.setAttribute('name', 'apple-mobile-web-app-capable');
        document.getElementsByTagName('head')[0].appendChild(metaAppleWebApp);
    }
    metaAppleWebApp.setAttribute('content', 'yes');

    // Add other meta tags adjustments here
});