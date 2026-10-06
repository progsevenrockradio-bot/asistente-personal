// Registrar Service Worker para PWA (Samsung Galaxy S24 / Android / Desktop)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('PWA ServiceWorker registrado con éxito:', registration.scope);
            })
            .catch((error) => {
                console.log('Fallo en el registro del ServiceWorker:', error);
            });
    });
}
