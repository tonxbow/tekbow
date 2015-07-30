<?php
$labelClass = "lbl col-sm-offset-0 col-sm-3 text-right";
$inputClass = "col-sm-8";
?>

<style>
    .lbl {
        margin-top: 8px;
        white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <section class="panel panel-primary">
            <header class="panel-heading" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Ubah Password</h4>
            </header>
            <div class="panel-body" style="height: 220px;">
                <div  class="form-horizontal">
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Old Password:</label>
                        <div class = "<?php echo $inputClass; ?>"><input type = "password" name = "pass_lama" class = "form-control" id = "tb_pass_lama"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">New Password:</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="password" name="pass_baru" id="tb_pass_baru" class="form-control"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Re-Type: </label>
                        <div class = "<?php echo $inputClass; ?>"><input type="password" name="konfirmasi" id="tb_konfirmasi" class="form-control"/></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block" id="btn_simpan">Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<script>
    $('#btn_simpan').click(function () {
        var pass_lama = $('#tb_pass_lama').val();
        var pass_baru = $('#tb_pass_baru').val();
        var konfirmasi = $('#tb_konfirmasi').val();
        if (pass_baru != '')
        {
            if (pass_baru == konfirmasi)
            {
                var action = "<?php echo $objEnkrip->encode('update_password'); ?>";
                var data_send = pass_lama + ';' + pass_baru;
                $.post("mod/action.php", {request: action, data: data_send})
                        .done(function (result) {
                            //console.log(result + " >> " + result.length);
                            if (result == '1')
                            {
                                alert("Password Berhasil Di Ubah");
                                $('#tb_pass_lama').val('');
                                $('#tb_pass_baru').val('');
                                $('#tb_konfirmasi').val('');
                            }
                            else
                            {
                                if (result == '3')
                                {
                                    alert("Ubah Password Gagal : Password Lama Salah !");
                                    $('#tb_pass_lama').focus();
                                }
                                else
                                    alert("Ubah Password Gagal !");
                            }
                        });
            }
            else
            {
                alert("Password Baru dan Konfirmasi Tidak Sama!");
                $('#tb_konfirmasi').focus();
            }
        }
        else
        {
            alert("Password baru tidak boleh kosong!");
        }

    });
</script>

