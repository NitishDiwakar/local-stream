/*
# Author  : Nitish Kumar Diwakar
# Twitter : https://x.com/NitishDiwakar
# Github  : https://github.com/NitishDiwakar
# Project : LAN File Sharing
# Licence : MIT
*/

const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");
const uploadBtn = document.getElementById("uploadBtn");
const progressBar = document.getElementById("progressBar");
const statusText = document.getElementById("status");
const selectedFileName = document.getElementById("selectedFileName");

// let selectedFile = null;
let selectedFiles = [];

/* Open file selector ONLY when clicking text area */
document.getElementById("dropText").addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", (e) => {
    /*selectedFile = e.target.files[0];
    if (selectedFile) {
        selectedFileName.innerText = "Selected: " + selectedFile.name;
    }*/

    selectedFiles = Array.from(e.target.files);

    if (selectedFiles.length > 0) {
        selectedFileName.innerText = "Selected: " + selectedFiles.length + " files";
    }

});

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("dragover");
});

dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("dragover");
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("dragover");
    /*selectedFile = e.dataTransfer.files[0];

    if (selectedFile) {
        selectedFileName.innerText = "Selected: " + selectedFile.name;
    }*/
    selectedFiles = Array.from(e.dataTransfer.files);

    if (selectedFiles.length > 0) {
        selectedFileName.innerText = "Selected: " + selectedFiles.length + " files";
    }

});

uploadBtn.addEventListener("click", () => {
    /*if (!selectedFile) {
        alert("Please select a file.");
        return;
    }

    const fileExt = selectedFile.name.split('.').pop().toLowerCase();

    if (!allowedExtensions.includes(fileExt)) {
        alert("File type not allowed.");
        return;
    }

    uploadBtn.disabled = true;

    const formData = new FormData();
    formData.append("file", selectedFile);*/

    if (selectedFiles.length === 0) {
    alert("Please select files.");
    return;
    }

    const formData = new FormData();

    selectedFiles.forEach(file => {
        const ext = file.name.split('.').pop().toLowerCase();
        if (allowedExtensions.includes(ext)) {
            formData.append("file[]", file);
        }
    });

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);

    xhr.upload.addEventListener("progress", (e) => {
        if (e.lengthComputable) {
            const percent = (e.loaded / e.total) * 100;
            progressBar.style.width = percent + "%";
        }
    });

    xhr.onload = () => {
        if (xhr.status === 200) {
            statusText.innerText = "Upload complete!";
            // selectedFile = null;
            // fileInput.value = "";

            selectedFiles = [];
            fileInput.value = "";

            selectedFileName.innerText = "";
            progressBar.style.width = "0%";
            uploadBtn.disabled = false;
            setTimeout(() => location.reload(), 800);
        } else {
            statusText.innerText = xhr.responseText;
            uploadBtn.disabled = false;
        }
    };

    xhr.send(formData);
});