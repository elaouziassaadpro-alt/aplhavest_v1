import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


Alpine.start();
