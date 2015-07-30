<?php
$labelClass = "lbl col-sm-offset-0 col-sm-3 text-right";
$inputClass = "col-sm-8";
$setting = $db->get_data($db, 'setting', '*', '', '', '');
?>

<style>
    .lbl {
        margin-top: 8px;
        white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-sm-3">
        <section class="panel panel-primary">
            <header class="panel-heading text-center" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Database</h4>
            </header>
            <div class="panel-body" style="height: 260px;">
                <div  class="form-horizontal">
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Server:</label>
                        <div class = "<?php echo $inputClass; ?>"><input type = "text" class = "form-control" id = "tb_server_database" value="<?php echo $dbserver; ?>"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">User:</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="text"  id="tb_user_database" class="form-control" value="<?php echo $dbuser; ?>"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Password: </label>
                        <div class = "<?php echo $inputClass; ?>"><input type="password"  id="tb_password_database" class="form-control" value="<?php echo $dbpass; ?>"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Database: </label>
                        <div class = "<?php echo $inputClass; ?>"><input type="text" id="tb_nama_database" class="form-control" value="<?php echo $dbname; ?>"/></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block" id="btn_simpan_database">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-sm-3">
        <section class="panel panel-primary">
            <header class="panel-heading text-center" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Margin Harga</h4>
            </header>
            <div class="panel-body" style="height: 260px;">
                <div  class="form-horizontal">
                    <div class = "form-group">
                        <div class = "col-sm-12 text-center"><input min="0" max="100" type = "text" class = "form-control text-center" value="Percent" readonly=""></div>

                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Umum:</label>
                        <div class = "col-sm-6"><input min="0" max="100" type = "number" class = "form-control" id = "tb_umum_margin" value="<?php echo $setting[0]['margin_umum'] ?>"></div>
                        <label for = "" class = "lbl col-sm-2 text-left">%</label>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Toko:</label>
                        <div class = "col-sm-6"><input min="0" max="100" type = "number" id="tb_toko_margin" class="form-control" value="<?php echo $setting[0]['margin_toko'] ?>"/></div>
                        <label for = "" class = "lbl col-sm-2 text-left">%</label>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">PPN</label>
                        <div class = "col-sm-6"><input min="0" max="100" type = "number"  id="tb_ppn" class="form-control" value="<?php echo $setting[0]['pajak'] ?>"/></div>
                        <label for = "" class = "lbl col-sm-2 text-left">%</label>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block" id="btn_simpan_margin">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-sm-6">
        <section class="panel panel-primary">
            <header class="panel-heading text-center" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Struk Print</h4>
            </header>
            <div class="panel-body" style="height: 260px;">
                <div  class="form-horizontal">

                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Nama Toko:</label>
                        <div class = "<?php echo $inputClass; ?>"><input maxlength="42" type = "text" class = "form-control" id = "tb_nama_toko"  value="<?php echo $setting[0]['nama_toko'] ?>"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Alamat:</label>
                        <div class = "<?php echo $inputClass; ?>"><input type="text"  maxlength="42" id="tb_alamat_toko" class="form-control"  value="<?php echo $setting[0]['alamat1'] ?>"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>"></label>
                        <div class = "<?php echo $inputClass; ?>"><input maxlength="42" type="text"  id="tb_alamat_toko_2" class="form-control"  value="<?php echo $setting[0]['alamat2'] ?>"/></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">No Telp: </label>
                        <div class = "<?php echo $inputClass; ?>"><input maxlength="42" type="text" id="tb_no_tlp" class="form-control"  value="<?php echo $setting[0]['telp'] ?>"/></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block" id="btn_simpan_struk">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-sm-3">
        <section class="panel panel-primary">
            <header class="panel-heading text-center" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Printer</h4>
            </header>
            <div class="panel-body" style="height: 170px;">
                <div  class="form-horizontal">
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Port:</label>
                        <div class = "col-sm-8"><input type = "text" class = "form-control" id = "tb_port" value="<?php echo $setting[0]['port'] ?>"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Baudrate:</label>
                        <div class = "col-sm-8"><input id="tb_baudrate" class="form-control" value="<?php echo $setting[0]['baudrate'] ?>"/></div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-block" id="btn_simpan_printer">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

</div>


<script>
    $('#btn_simpan_database').click(function () {
        var server = $('#tb_server_database').val();
        var user = $('#tb_user_database').val();
        var password = $('#tb_password_database').val();
        var database = $('#tb_nama_database').val();

        if (server != '' && user != '' && database != '')
        {
            var action = "<?php echo $objEnkrip->encode('update_database'); ?>";
            var data_send = server + ';' + user + ';' + password + ';' + database;
            $.post("mod/action.php", {request: action, data: data_send})
                    .done(function (result) {
                        //console.log(result + " >> " + result.length);
                        if (result == '1')
                        {
                            alert("Data Berhasil Di Ubah");
                        }
                        else
                        {
                            alert("Ubah Data Gagal !");
                        }
                    });

        }
        else
        {
            alert("Data Harus Diisi!");
        }

    });

    $('#btn_simpan_margin').click(function () {
        var umum = $('#tb_umum_margin').val();
        var toko = $('#tb_toko_margin').val();
        var ppn = $('#tb_ppn').val();

        if (umum != '' && toko != '' && ppn != '')
        {
            var action = "<?php echo $objEnkrip->encode('update_setting'); ?>";
            var data_send = umum + ';' + toko + ';' + ppn;
            $.post("mod/action.php", {request: action, data: data_send, tipe: '1'})
                    .done(function (result) {
                        //console.log(result + " >> " + result.length);
                        if (result == '1')
                        {
                            alert("Data Berhasil Di Ubah");
                        }
                        else
                        {
                            alert("Ubah Data Gagal !");
                        }
                    });

        }
        else
        {
            alert("Data Harus Diisi!");
        }

    });

    $('#btn_simpan_struk').click(function () {
        var nama_toko = $('#tb_nama_toko').val();
        var alamat1 = $('#tb_alamat_toko').val();
        var alamat2 = $('#tb_alamat_toko_2').val();
        var telp = $('#tb_no_tlp').val();

        if (nama_toko != '' && alamat1 != '' && telp != '')
        {
            var action = "<?php echo $objEnkrip->encode('update_setting'); ?>";
            var data_send = nama_toko + ';' + alamat1 + ';' + alamat2 + ';' + telp;
            $.post("mod/action.php", {request: action, data: data_send, tipe: '2'})
                    .done(function (result) {
                        //console.log(result + " >> " + result.length);
                        if (result == '1')
                        {
                            alert("Data Berhasil Di Ubah");
                        }
                        else
                        {
                            alert("Ubah Data Gagal !");
                        }
                    });

        }
        else
        {
            alert("Data Harus Diisi!");
        }

    });

    $('#btn_simpan_printer').click(function () {
        var port = $('#tb_port').val();
        var baudrate = $('#tb_baudrate').val();

        if (port != '' && baudrate != '')
        {
            var action = "<?php echo $objEnkrip->encode('update_setting'); ?>";
            var data_send = port + ';' + baudrate;
            $.post("mod/action.php", {request: action, data: data_send, tipe: '3'})
                    .done(function (result) {
                        //console.log(result + " >> " + result.length);
                        if (result == '1')
                        {
                            alert("Data Berhasil Di Ubah");
                        }
                        else
                        {
                            alert("Ubah Data Gagal !");
                        }
                    });

        }
        else
        {
            alert("Data Harus Diisi!");
        }

    });
</script>

