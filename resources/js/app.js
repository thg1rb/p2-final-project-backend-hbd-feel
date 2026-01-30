import './bootstrap';

import Alpine from 'alpinejs';

    const input = document.getElementById('documents');
    const preview = document.getElementById('file-preview');

    input.addEventListener('change', () => {
    preview.innerHTML = '';
    for (const file of input.files) {
    const li = document.createElement('li');
    li.textContent = file.name;
    preview.appendChild(li);
}
});
window.Alpine = Alpine;

Alpine.start();
