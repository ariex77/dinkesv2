// Service Worker untuk E-Presensi GPS V2
// TIDAK akan cache file apapun - semua data selalu fresh dari network
// FIX: Tidak mengintervensi navigation request (HTML) untuk mencegah blank page

// Install event - tidak cache apapun
self.addEventListener('install', event => {
    // Skip waiting untuk update service worker lebih cepat
    self.skipWaiting();
});

// Activate event - clear semua cache yang ada
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        return caches.delete(cacheName);
                    })
                );
            })
            .then(() => {
                // FIX: Jangan gunakan clients.claim() - biarkan halaman yang sudah load tetap di kontrol browser
                // Hanya halaman baru yang akan dikontrol oleh service worker
                // Ini mencegah blank page saat pertama kali akses
                return Promise.resolve();
            })
    );
});

// Background sync untuk presensi offline (opsional)
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync-presensi') {
        //console.log('Service Worker: Background sync for presensi');
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    // Implementasi sync data presensi jika diperlukan
    // console.log('Service Worker: Performing background sync');
}

// Push notification (opsional)
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/assets/img/favicon/favicon-192x192.png',
            badge: '/assets/img/favicon/favicon-96x96.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            },
            actions: [
                {
                    action: 'explore',
                    title: 'Buka Aplikasi',
                    icon: '/assets/img/icons/checkmark.png'
                },
                {
                    action: 'close',
                    title: 'Tutup',
                    icon: '/assets/img/icons/xmark.png'
                }
            ]
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Message handler untuk komunikasi dengan main thread
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: '1.0.0-no-cache' });
    }
});

console.log('Service Worker: No Cache Mode initialized');
