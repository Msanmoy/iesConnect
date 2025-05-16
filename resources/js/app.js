import 'bootstrap/dist/js/bootstrap.bundle.min';
import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const stickyWall = document.getElementById('stickyWall');
    const addNoteBtn = document.getElementById('addNote');
    const trashZone = document.getElementById('trashZone');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!stickyWall) return;

    const makeNoteEditable = (noteElement) => {
        const titleElement = noteElement.querySelector('.card-title');
        const contentElement = noteElement.querySelector('.card-text');

        const titleText = titleElement?.textContent ?? '';
        const contentText = contentElement?.innerHTML.replace(/<br>/g, '\n') ?? '';

        titleElement.outerHTML = `<input class="form-control mb-2" value="${titleText}" autofocus>`;
        contentElement.outerHTML = `<textarea class="form-control" rows="4">${contentText}</textarea>`;

        const inputTitle = noteElement.querySelector('input');
        const inputContent = noteElement.querySelector('textarea');
        let hasSaved = false;

        const saveChanges = () => {
            if (hasSaved) return;
            hasSaved = true;

            const id = noteElement.dataset.id ?? null;
            const newTitle = inputTitle.value.trim() || 'Nueva Nota';
            const newContent = inputContent.value.trim();

            fetch('/stickywall/store-or-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id, title: newTitle, content: newContent }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        noteElement.dataset.id = data.note.id;
                        inputTitle.outerHTML = `<h5 class="card-title">${data.note.title}</h5>`;
                        inputContent.outerHTML = `<p class="card-text">${data.note.content.replace(/\n/g, '<br>')}</p>`;
                    }
                });
        };

        inputTitle.addEventListener('blur', saveChanges);
        inputContent.addEventListener('blur', saveChanges);
    };

    new Sortable(stickyWall, {
        animation: 150,
        ghostClass: 'bg-light',
        filter: '#addNote',
        preventOnFilter: false,

        onStart: () => {
            trashZone.classList.remove('d-none');
            trashZone.classList.add('active');
        },

        onEnd: (evt) => {
            trashZone.classList.remove('active');
            trashZone.classList.add('d-none');

            const note = evt.item;

            // Eliminar si se suelta sobre la papelera
            if (trashZone.contains(evt.originalEvent.target)) {
                const noteId = note.dataset.id;
                if (noteId) {
                    fetch(`/stickywall/delete/${noteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }).then(() => note.remove());
                }
                return;
            }

            // Si no se elimina, actualiza el orden
            const noteElements = [...stickyWall.querySelectorAll('.sticky-note:not(#addNote)')];
            const noteIds = noteElements.map((n) => n.dataset.id);

            fetch('/stickywall/update-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ note_ids: noteIds }),
            });
        },
    });

    stickyWall.addEventListener('dblclick', function (e) {
        const note = e.target.closest('.sticky-note:not(#addNote)');
        if (note) makeNoteEditable(note);
    });

    addNoteBtn?.addEventListener('click', () => {
        const newNote = document.createElement('div');
        newNote.className = 'sticky-note card';
        newNote.innerHTML = `
            <div class="card-body">
                <input class="form-control mb-2" placeholder="Título" autofocus>
                <textarea class="form-control" placeholder="Contenido..." rows="4"></textarea>
            </div>
        `;
        stickyWall.insertBefore(newNote, addNoteBtn);

        const inputTitle = newNote.querySelector('input');
        const inputContent = newNote.querySelector('textarea');
        let hasSaved = false;

        const saveNewNote = () => {
            if (hasSaved) return;
            hasSaved = true;

            const newTitle = inputTitle.value.trim() || 'Nueva Nota';
            const newContent = inputContent.value.trim();

            fetch('/stickywall/store-or-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ title: newTitle, content: newContent }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        newNote.dataset.id = data.note.id;
                        inputTitle.outerHTML = `<h5 class="card-title">${data.note.title}</h5>`;
                        inputContent.outerHTML = `<p class="card-text">${data.note.content.replace(/\n/g, '<br>')}</p>`;
                    }
                });
        };

        inputTitle.addEventListener('blur', saveNewNote);
        inputContent.addEventListener('blur', saveNewNote);
    });
});
