
$('.tombol-hapus').on('click', function(e){

    // e = event
    // mematikan funtion href yang seharusnya berjalan jika di klick
    e.preventDefault();
    
    //var href 
    const href = $(this).attr('href');

    Swal.fire({
                title: 'Are you sure?',
                text: "Menu data will be deteled!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete Menu!'
            }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
                Swal.fire(
                'Deleted!',
                'Your Menu has been deleted.',
                'success'
                )
            }
            })

});


$('.delete-submenu').on('click', function(e){

    // e = event
    // mematikan funtion href yang seharusnya berjalan jika di klick
    e.preventDefault();
    
    //var href 
    const href = $(this).attr('href');

    Swal.fire({
                title: 'Are you sure?',
                text: "Submenu data will be deteled!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete Submenu!'
            }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
                Swal.fire(
                'Deleted!',
                'Your Submenu has been deleted.',
                'success'
                )
            }
            })

});


$('.tombol-rolehapus').on('click', function(e){

    // e = event
    // mematikan funtion href yang seharusnya berjalan jika di klick
    e.preventDefault();
    
    //var href 
    const href = $(this).attr('href');

    Swal.fire({
                title: 'Are you sure?',
                text: "Role data will be deteled!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete Role!'
            }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
                Swal.fire(
                'Deleted!',
                'Your Role has been deleted.',
                'success'
                )
            }
            })

});

// modal edit
$(function(){
    $('.ModalEdit').on('click', function(){
    $('#labelMenuModal').html('Edit Menu Management');
    $('.modal-footer button[type=submit]').html('Edit Menu');
    
    $('.modal-body form').attr('action', 'http://localhost/CodeIgniter_3_2/menu/edit');
    const id = $(this).data('id');
    console.log(id);
    
    })


    // // ajax
    // $.ajax({
    //     url: 'http://localhost/CodeIgniter_3_2/menu/getedit',
    //     data: {id : id},
    //     method: 'post',
    //     dataType: 'json',
    //     success: function(data){
    //         $('#menu').val(data.menu);
    //     }
    // })

});

