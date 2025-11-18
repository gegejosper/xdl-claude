"use strict";

var KTSigninGeneral = (function() {
    var form, submit_button, validator;
    console.log($('#kt_sign_in_form').data('redirect-url'));
    return {
        init: function() {
            form = document.querySelector("#kt_sign_in_form");
            submit_button = document.querySelector("#kt_sign_in_submit");

            // Initialize validation
            validator = FormValidation.formValidation(form, {
                fields: {
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "Please enter a valid email address"
                            },
                            notEmpty: {
                                message: "Email address is required"
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "Password is required"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });

            // Handle submit
            submit_button.addEventListener("click", function(e) {
                e.preventDefault();

                validator.validate().then(function(status) {
                    if (status === "Valid") {
                        submit_button.setAttribute("data-kt-indicator", "on");
                        submit_button.disabled = true;

                        axios.post(form.getAttribute("action"), new FormData(form), {
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                            }
                        })
                        // .then(function(response) {
                        //     if (response.data && response.data.redirect) {
                        //         window.location.href = response.data.redirect; // Laravel decides
                        //     } else {
                        //         window.location.reload(); // fallback
                        //     }
                        // })
                        .then(function(response) {
                            const redirect_url = response.data.redirect_url;
                            if (redirect_url) {
                                window.location.href = redirect_url;
                            } else {
                                window.location.reload();
                            }
                        })
                        .catch(function(error) {
                            let message = "Sorry, invalid credentials. Please try again.";

                            if (error.response && error.response.status === 422) {
                                message = "Validation error: please check your inputs.";
                            }

                            Swal.fire({
                                text: message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        })
                        .finally(() => {
                            submit_button.removeAttribute("data-kt-indicator");
                            submit_button.disabled = false;
                        });
                    } else {
                        Swal.fire({
                            text: "Please correct the errors and try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
        }
    };
})();

KTUtil.onDOMContentLoaded(function() {
    KTSigninGeneral.init();
});