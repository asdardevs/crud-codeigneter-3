<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800 mt-5">Mahasiswa</h1>


        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-body">
                <div class=" d-sm-flex align-items-center justify-content-between">
                    <h5>Data Mahasiswa</h5>
                    <div class="row">
                        <a href="#" type="button" class="btn btn-primary btn-sm add">Tambah Mahasiswa</a>
                    </div>

                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabel" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Noe</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Jurusan</th>
                                <th>Aksi</th>

                            </tr>
                        </thead>

                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>


<!-- Tambah / edit peserta -->
<div class="modal fade right" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="heading lead" id="status"></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" name="kode" id="kode">
                    <input type="hidden" name="rcstat" id="rcstat">
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">NIM</label>
                        <div class="col-sm-7">
                            <input type="text" id="nim" name="nim" class="form-control">
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Nama </label>
                        <div class="col-sm-7">
                            <input type="text" id="nama" name="nama" class="form-control">
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                        <label class="col-sm-3 form-control-label">Jurusan </label>
                        <div class="col-sm-7">
                            <input type="text" id="jurusan" name="jurusan" class="form-control">
                        </div>
                    </div>
                    <div class="line"></div>

                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <a type="button" class="btn btn-primary save" id="simpan">Tambah </a>
                <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Keluar</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //datatables
        table = $('#tabel').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo base_url(); ?>mahasiswa/get",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0, 3], //first column / numbering column
                "orderable": false, //set not orderable
            }, ],

        });

    });


    $(".add").click(function() {
        $('#form').each(function() {
            this.reset();
        });
        $('#rcstat').val(1);
        $('#add').modal('show');
        $('#info').hide();
        $('#status').html('Tambah Mahasiswa');
        $('#simpan').html('Tambah Mahasiswa<i class="fas fa-save text-white ml-2"></i>');
    });
    $(".save").click(function() {

        var formData = new FormData($('#form')[0]);
        var form = $('#form');
        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');

        $.ajax({
            type: "POST",
            url: '<?= base_url('mahasiswa/simpan'); ?>',
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response) {

                if (response.success == true) {
                    $('#add').modal('hide');
                    $('#form').each(function() {
                        this.reset();
                    });
                    var table = $('#tabel').DataTable();
                    table.row(this).remove().draw(false);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Berhasil',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return false;
                } else {
                    $.each(response.messages, function(key, value) {

                        if (value != '') {

                            $('#' + key)
                                .closest('.form-control')
                                .addClass('is-invalid')
                                .after('<div  class="invalid-feedback" >  ' + value + '</div>')
                        }

                    })
                }
            },


        });
    });



    function edit(id) {
        $('#status').html('Ubah Mahaiswa');
        $('#simpan').html('<i class="fa fa-save"></i> Simpan Perubahan');
        $('#kode').val(id);
        $('#rcstat').val('2');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: ({
                id
            }),
            url: "<?= base_url('mahasiswa/edit'); ?>",
            success: function(data) {
                $('#nim').val(data.nim);
                $('#nama').val(data.nama);
                $('#jurusan').val(data.jurusan);


                $('#add').modal('show');
            }
        })
    }

    function hapus(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f00',
            cancelButtonText: 'Batal',
            cancelButtonColor: '#D0D0D0',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    data: ({
                        tabel: 'mahasiswa',
                        field: 'id',
                        id
                    }),
                    url: "<?= base_url('welcome/hapus'); ?>",
                    success: function(data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berhasil',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return false;

                    }
                });
            }


        })

    }
</script>