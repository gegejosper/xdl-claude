document.querySelectorAll('.kt_unbind_alert').forEach(button => {

    button.addEventListener('click', e => {
        e.preventDefault();

        Swal.fire({
            html: `Are you sure you want to unbind the device?`,
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: 'Exit',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: true,
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {

            if (result.isConfirmed) {
                button.closest('form').submit();
            }

        });
    });

});


document.querySelectorAll('.kt_restrict_alert').forEach(button => {

    var msg = $('#restrict_msg').val();
    button.addEventListener('click', e => {
        e.preventDefault();

        Swal.fire({
            html: `Are you sure you want to  ${msg} this user to one device?`,
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: 'Exit',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: true,
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {

            if (result.isConfirmed) {
                button.closest('form').submit();
            }

        });
    });

});