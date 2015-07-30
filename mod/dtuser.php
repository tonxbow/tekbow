<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
    }

    .lbl {
        margin-top: 8px;
    }
    .pemisah
    {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .form-entry
    {
        margin-top: 2px;
        margin-bottom: 5px;
    }
</style>

<?php
$judul = 'Data User';
?>

<!-- page start-->
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-primary">
            <header class="panel-heading" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Data User</h4>
            </header>
            <div class="panel-body" style="height: 75%;">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <button class="btn btn-primary " id="btn_add" ><i class="fa fa-plus-circle"></i> Tambah Data User</button>
                    </div>
                    <!--TABLE DATA-->
                    <div id="loading" style="width: 100%; text-align: center; position: fixed;z-index: 999; top: 50%; color: red;"><h3>Loading...</h3></div>
                    <div id="data_content"></div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
$labelClass = "lbl col-sm-3 text-right";
$inputClass = "col-sm-9";
?>
<!--Modal Data Obat-->
<div class="modal fade" id="mod_add_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #428bca; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="txt_judul_modal"></h4>
            </div>
            <div class="modal-body" style="color:#000;">
                <div  class="form-horizontal">

                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Username :</label>
                        <div class = "<?php echo $inputClass; ?>"><input type = "text" name = "username" class = "form-control" id = "tb_username"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Nama :</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="text" name="nama_user" id="tb_nama_user" class="form-control"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Password :</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="password" name="password" id="tb_password" class="form-control"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Re-Type :</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="password" name="re_password" id="tb_re_password" class="form-control"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Role :</label>
                        <div class = "<?php echo $inputClass; ?>">
                            <select class="form-control" name="role" id="cb_role">
                                <option value = "1">Administrator</option>
                                <option value = "2">Kasir</option>
                            </select>
                        </div>
                    </div>


                    <input style="display:none;" id="txt_act">
                    <input style="display:none;" id="tb_id">

                    <div class = "form-group">
                        <p style="font-size: 15px; color: red; text-align: center;" id="txt_new_keterangan"></p>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-5 text-right">
                            <button class="btn btn-primary btn-block" id="btn_new_simpan">Simpan</button>

                        </div>
                        <div class="col-sm-offset-1 col-sm-5 text-right">
                            <button data-dismiss="modal" class="btn btn-danger btn-block" type="button">Batal</button>
                        </div>
                    </div>
                    <div class="panel-footer">
                        *Jika Password tidak diubah, maka kosongkan kolom isian Password.
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css" />
<script src="js/jquery.dataTables.min.js"></script>
<script>


    $('#loading').hide();

    get_content();

    function input_new_false(pesan)
    {
        $('#txt_new_keterangan').text(pesan);
    }

    function input_new_check(action)
    {
        var username = $('#tb_username').val();
        var nama_user = $('#tb_nama_user').val();
        var password = $('#tb_password').val();
        var re_password = $('#tb_re_password').val();
        var role = $('#cb_role').val();
        var status = true;
        $('#txt_new_keterangan').text("");

        if (username == "")
        {
            input_new_false("Username Harus Diisi Dengan Benar");
            $('#tb_username').focus();
            status = false;
        }
        else if (nama_user == "")
        {
            input_new_false("Nama Harus Diisi !");
            $('#tb_nama_user').focus();
            status = false;
        }
        else if (password != re_password)
        {
            input_new_false("Password Tidak Sama !");
            $('#tb_re_password').focus();
            status = false;
        }

        var act = $('#txt_act').text();

        return status
    }
    function get_content()
    {
        $('#loading').show();
        $.ajax({
            type: "GET",
            url: "mod/content.php?request=<?php echo $objEnkrip->encode('data_user'); ?>",
            success: function (data) {
                $('#data_content').empty().append(data);
                $('#loading').hide();
            }
        });
    }

    jQuery(document).ready(function () {
        $('#btn_add').on("click", function ()
        {
            $('#tb_nama_user').val('');
            $('#tb_username').val('');
            $('#tb_password').val('');
            $('#tb_re_password').val('');
            $('#txt_act').text("add");
            $('#txt_judul_modal').text("Tambah Data");
            $('#mod_add_data').modal("show");
        });

        $('#btn_new_simpan').on('click', function () {
            var username = $('#tb_username').val();
            var nama_user = $('#tb_nama_user').val();
            var password = $('#tb_password').val();
            var re_password = $('#tb_re_password').val();
            var role = $('#cb_role').val();
            var id_user = $('#tb_id').text();
            var act = $('#txt_act').text();
            if (input_new_check('1'))
            {
                var act = $('#txt_act').text();
                var action = "";
                switch (act)
                {
                    case 'add':
                        action = "<?php echo $objEnkrip->encode('add_data_user'); ?>";
                        break;
                    case 'edit':
                        action = "<?php echo $objEnkrip->encode('update_data_user'); ?>";
                        break;
                }

                var data_send = username + ';' + nama_user + ';' + password + ';' + role + ';' + id_user;
                $.post("mod/action.php", {request: action, data: data_send})
                        .done(function (result) {
                            if (result == "fail")
                                alert("Penyimpanan Gagal, Keterangan : \n\n " + result);
                            else
                            {
                                $('#mod_add_data').modal("hide");
                                get_content();
                                alert("Berhasil !");
                            }
                        });
            }
        });


    });
</script>
