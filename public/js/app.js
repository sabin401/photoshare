// Send asynchronous API requests

function sendRequest(method, url, data, callback) {

    const xhr = new XMLHttpRequest();

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    const apiToken = document.querySelector(
        'meta[name="api-token"]'
    )?.content;

    xhr.open(method, url, true);

    xhr.setRequestHeader("Accept", "application/json");

    if (csrfToken) {
        xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    }

    if (apiToken) {
        xhr.setRequestHeader(
            "Authorization",
            "Bearer " + apiToken
        );
    }

    xhr.onload = function () {

        let response;

        try {

            response = JSON.parse(xhr.responseText);

        } catch (error) {

            response = {
                message: "Unexpected server response."
            };

        }

        callback(xhr.status, response);

    };

    xhr.onerror = function () {

        callback(0, {
            message: "Network connection failed."
        });

    };

    xhr.send(data);
}


// Alpine.js framework

document.addEventListener("alpine:init", function () {

    // Store authentication state

    Alpine.store("auth", {

        loggedIn: false,

        user: null,

        load() {

            sendRequest(
                "GET",
                "/api/auth-state",
                null,
                (status, response) => {

                    if (status === 200) {

                        this.loggedIn = response.loggedIn;
                        this.user = response.user;

                    }

                }
            );

        }

    });

    Alpine.store("auth").load();


    // Dynamic image gallery

    Alpine.data("gallery", function (endpoint) {

        return {

            images: [],

            loading: true,

            message: "",

            success: false,

            loadImages() {

                this.loading = true;

                sendRequest(
                    "GET",
                    endpoint,
                    null,
                    (status, response) => {

                        this.loading = false;

                        if (status === 200) {

                            this.images = response;

                        } else {

                            this.message =
                                response.message ||
                                "Unable to load images.";

                            this.success = false;

                        }

                    }
                );

            },

            deleteImage(image) {

                if (!confirm(
                    "Are you sure you want to delete this image?"
                )) {
                    return;
                }

                sendRequest(
                    "DELETE",
                    image.delete_url,
                    null,
                    (status, response) => {

                        this.message = response.message;

                        this.success = status === 200;

                        if (this.success) {

                            this.images = this.images.filter(
                                item => item.id !== image.id
                            );

                        }

                    }
                );

            }

        };

    });


    // Asynchronous image publishing

    Alpine.data("publishImage", function () {

        return {

            title: "",

            description: "",

            filter: "none",

            preview: "",

            message: "",

            success: false,

            submitting: false,

            previewImage(event) {

                const file = event.target.files[0];

                if (this.preview) {
                    URL.revokeObjectURL(this.preview);
                }

                this.preview = "";

                if (file) {

                    this.preview = URL.createObjectURL(file);

                }

            },

            publish(event) {

                const form = event.target;

                const file = form.querySelector(
                    'input[name="image"]'
                ).files[0];

                if (!file) {

                    this.message = "Please select an image.";
                    this.success = false;

                    return;

                }

                if (file.size > 5 * 1024 * 1024) {

                    this.message = "Image must be smaller than 5 MB.";
                    this.success = false;

                    return;

                }

                const formData = new FormData(form);

                this.submitting = true;

                sendRequest(
                    "POST",
                    form.action,
                    formData,
                    (status, response) => {

                        this.submitting = false;

                        this.success = status === 201;

                        this.message =
                            response.message ||
                            "Image publishing failed.";

                        if (this.success) {

                            form.reset();

                            this.title = "";
                            this.description = "";
                            this.filter = "none";

                            if (this.preview) {
                                URL.revokeObjectURL(this.preview);
                            }

                            this.preview = "";

                        }

                    }
                );

            }

        };

    });

});


// Existing image editing functionality

document.addEventListener("DOMContentLoaded", function () {

    const editForm = document.getElementById("edit-image-form");

    const imageInput = document.getElementById("image");

    const preview = document.getElementById("image-preview");

    const filter = document.getElementById("filter");

    let previewUrl = null;

    let originalImage = preview?.getAttribute("src");

    if (editForm && imageInput && preview && filter) {

        imageInput.addEventListener("change", function () {

            const file = imageInput.files[0];

            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }

            if (file) {

                previewUrl = URL.createObjectURL(file);

                preview.src = previewUrl;

            } else {

                preview.src = originalImage;

            }

        });

        filter.addEventListener("change", function () {

            preview.className =
                "image-preview filter-" + filter.value;

        });

        editForm.addEventListener("submit", function (event) {

            event.preventDefault();

            const formData = new FormData(editForm);

            const submitButton = editForm.querySelector(
                'button[type="submit"]'
            );

            submitButton.disabled = true;

            sendRequest(
                "POST",
                editForm.action,
                formData,
                function (status, response) {

                    submitButton.disabled = false;

                    const messageBox = document.getElementById(
                        "response-message"
                    );

                    messageBox.textContent =
                        response.message || "Update failed.";

                    messageBox.className =
                        status === 200 ? "success" : "error";

                    if (status === 200) {

                        if (previewUrl) {
                            URL.revokeObjectURL(previewUrl);
                            previewUrl = null;
                        }

                        originalImage = response.image_url;

                        preview.src = originalImage;

                        preview.className =
                            "image-preview filter-" +
                            response.filter;

                        imageInput.value = "";

                    }

                }
            );

        });

    }

});