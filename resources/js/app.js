import "./bootstrap";
import "./calendar";
import * as bootstrap from "bootstrap";
import Sortable from "sortablejs";
window.bootstrap = bootstrap;

document.addEventListener("DOMContentLoaded", () => {
    const stickyWall = document.getElementById("stickyWall");

    if (!stickyWall) return;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const makeNoteEditable = (noteElement) => {
        const titleElement = noteElement.querySelector(".card-title");
        const contentElement = noteElement.querySelector(".card-text");

        const titleText = titleElement?.textContent ?? "";
        const contentText =
            contentElement?.innerHTML.replace(/<br>/g, "\n") ?? "";

        titleElement.outerHTML = `<input class="form-control mb-2" value="${titleText}" autofocus>`;
        contentElement.outerHTML = `<textarea class="form-control" rows="4">${contentText}</textarea>`;

        const inputTitle = noteElement.querySelector("input");
        const inputContent = noteElement.querySelector("textarea");

        const saveChanges = () => {
            const id = noteElement.dataset.id ?? null;
            const newTitle = inputTitle.value.trim() || "Nueva Nota";
            const newContent = inputContent.value.trim();

            fetch("/stickywall/store-or-update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    id,
                    title: newTitle,
                    content: newContent,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        noteElement.dataset.id = data.note.id;
                        inputTitle.outerHTML = `<h5 class="card-title">${data.note.title}</h5>`;
                        inputContent.outerHTML = `<p class="card-text">${data.note.content.replace(/\n/g, "<br>")}</p>`;
                    }
                })
                .catch((error) =>
                    console.error("Error guardando nota:", error),
                );
        };

        inputTitle.addEventListener("blur", saveChanges);
        inputContent.addEventListener("blur", saveChanges);
    };

    new Sortable(stickyWall, {
        animation: 150,
        ghostClass: "bg-light",
        filter: "#addNote",
        preventOnFilter: false,
        onEnd: function () {
            const noteElements = [
                ...stickyWall.querySelectorAll(".sticky-note:not(#addNote)"),
            ];
            const noteIds = noteElements.map((note) => note.dataset.id);

            fetch("/stickywall/update-order", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ note_ids: noteIds }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        console.log("Orden actualizado correctamente.");
                    }
                })
                .catch((error) =>
                    console.error("Error actualizando orden:", error),
                );
        },
    });

    stickyWall.addEventListener("dblclick", function (e) {
        const note = e.target.closest(".sticky-note:not(#addNote)");
        if (note) {
            makeNoteEditable(note);
        }
    });

    document.getElementById("addNote")?.addEventListener("click", function () {
        const newNote = document.createElement("div");
        newNote.className = "sticky-note card";
        newNote.innerHTML = `
            <div class="card-body">
                <input class="form-control mb-2" placeholder="Título" autofocus>
                <textarea class="form-control" placeholder="Contenido..." rows="4"></textarea>
            </div>
        `;
        stickyWall.insertBefore(newNote, document.getElementById("addNote"));

        const inputTitle = newNote.querySelector("input");
        const inputContent = newNote.querySelector("textarea");

        const saveNewNote = () => {
            const newTitle = inputTitle.value.trim() || "Nueva Nota";
            const newContent = inputContent.value.trim();

            fetch("/stickywall/store-or-update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ title: newTitle, content: newContent }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        newNote.dataset.id = data.note.id;
                        inputTitle.outerHTML = `<h5 class="card-title">${data.note.title}</h5>`;
                        inputContent.outerHTML = `<p class="card-text">${data.note.content.replace(/\n/g, "<br>")}</p>`;
                    }
                })
                .catch((error) =>
                    console.error("Error guardando nueva nota:", error),
                );
        };

        inputTitle.addEventListener("blur", saveNewNote);
        inputContent.addEventListener("blur", saveNewNote);
    });
});
