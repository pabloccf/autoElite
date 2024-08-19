$(document).ready(function() {
    $('.btn-delete').click(function(e) {
        e.preventDefault();

        let row = $(this).parents('tr');
        let id = row.data('id'); // Recuperamos el id de tr

        let form = $('#form-delete');
        let url = form.attr('action').replace(':USER_ID', id);
        let data = form.serialize();

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-progress').removeClass('hidden');

                $.post(url, data, function (result) {

                    $('#delete-progress').addClass('hidden');
                    if (result.status == 1) {
                        row.fadeOut();
                        Swal.fire(
                            'Eliminado!',
                            'El usuario ha sido eliminado.',
                            'success'
                        );
                    }
                }).fail(function() {
                    $('#delete-progress').addClass('hidden');
                    Swal.fire(
                        'Error',
                        'No se ha podido eliminar el usuario',
                        'error'
                    );
                    row.show();
                });
            }
        });

        /*bootbox.confirm(message, function (res) {
            if (res == true) {
                $('#delete-progress').removeClass('hidden');
                $.post(url, data, function (result) {

                    $('#delete-progress').addClass('hidden');
                    if (result.status == 1) {
                        row.fadeOut();
                    }
                }).fail(function (result){

                    $('#delete-progress').addClass('hidden');
                    let resultResponse = result.responseJSON;

                    alert(resultResponse.message);
                    row.show();
                });
            }
        });*/
    });
});