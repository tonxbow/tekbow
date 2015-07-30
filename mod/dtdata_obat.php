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
/* * ************************************************************
 * Untuk membuat Form baru :
 * 1. Seuaikan variable dengan kebutuhan
 * 2. Buat Form
 * 3. Buat Validasi
 *
 *
 *  VARIABLE
 * *********************************************************** */
$filename = 'dtdata_obat.php';
$judul = 'Data Obat';
$stsCetak = false;
$actionForm = 'mod/act.php?ask=' . $objEnkrip->encode('actdata_obat.php');
$jenisPesan = '';

$tbl = 'data_obat';
$fldSelect = "*";
$criteriaField = 'id_data_obat';

//Header Tabel
$dataKolom = array('Nama', 'Kode', 'Sat. Besar', 'Sat. Kecil', 'Isi Sat.', 'Harga Beli', 'Harga Dasar', 'Obat In', 'Obat Out', 'Stock', 'Tgl Masuk', 'Action'); //Judul Kolom
$dataField = array('nama', 'barcode', 'satuan_besar', 'satuan_kecil', 'jumlah_satuan_kecil', 'harga_dasar', 'harga_jual', 'stock_masuk', 'stock_keluar', 'stock', 'tanggal_terakhir_masuk');
//field primary key

$classLabel = 'col-xs-4 control-label';
$classInput = 'col-xs-8';

$data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
$satuan = $db->get_data($db, 'satuan', '*', '', '', '');

$crt = '';
?>

<!-- page start-->
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-primary">
            <header class="panel-heading" style="padding-top: 10px; padding-bottom: 0;">
                <h4>Data Obat</h4>

            </header>
            <div class="panel-body" style="height: 75%;">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <button class="btn btn-primary " id="btn_add" ><i class="fa fa-plus-circle"></i> Tambah Data Obat</button>
                    </div>

                    <!--  RESPONSE MESSAGE-->
                    <?php
                    if ($jenisPesan != '') {
                        echo '<div class = "alert alert-block ' . $jenisPesan . ' fade in" style = "margin-top: 15px;">
                            <button data-dismiss = "alert" class = "close close-sm" type = "button"><i class = "fa fa-times"></i></button>
                            <strong>' . $statusPesan . ' </strong>' . $isiPesan .
                        '</div>';
                    }
                    ?>

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
                        <label for = "" class = "<?php echo $labelClass; ?>">Kode :</label>
                        <div class = "<?php echo $inputClass; ?>"><input type = "text" name = "kode_obat" class = "form-control" id = "tb_new_kode_obat"></div>
                    </div>
                    <div class = "form-group">
                        <label for = "" class = "<?php echo $labelClass; ?>">Nama :</label>
                        <!--<div class = "<?php echo $inputClass; ?>"><input type = "text" name = "nama_obat" class = "form-control" id = "tb_new_nama_obat"></div>-->
                        <div class = "<?php echo $inputClass; ?>"><input type="text" name="nama_obat" id="tb_new_nama_obat" class="form-control"/></div>
                    </div>
                    <div id="detail_new_obat">
                        <!--
                        <div class = "form-group">
                            <label for = "" class = "<?php echo $labelClass; ?>">Group Obat :</label>
                            <div class = "<?php echo $inputClass; ?>">
                                <select class="form-control" name="group_obat" id="cb_new_group_obat">
                        <?php
                        for ($i = 0; $i < count($group_obat); $i++) {
                            echo '<option value = "' . $objEnkrip->encode($group_obat[$i]['id_group_obat']) . '">' . $group_obat[$i]['nama'] . '</option>';
                        }
                        ?>
                                </select>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for = "" class = "<?php echo $labelClass; ?>">Jenis Obat :</label>
                            <div class = "<?php echo $inputClass; ?>">
                                <select class="form-control" name="jenis_obat" id="cb_new_jenis_obat">
                        <?php
                        for ($i = 0; $i < count($jenis_obat); $i++) {
                            echo '<option value = "' . $objEnkrip->encode($jenis_obat[$i]['id_jenis_obat']) . '">' . $jenis_obat[$i]['nama'] . '</option>';
                        }
                        ?>
                                </select>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for = "" class = "<?php echo $labelClass; ?>">Type Obat :</label>
                            <div class = "<?php echo $inputClass; ?>">
                                <select class="form-control" name="type_obat" id="cb_new_type_obat">
                        <?php
                        for ($i = 0; $i < count($type_obat); $i++) {
                            echo '<option value = "' . $objEnkrip->encode($type_obat[$i]['id_type_obat']) . '">' . $type_obat[$i]['nama'] . '</option>';
                        }
                        ?>
                                </select>
                            </div>
                        </div>-->
                        <div class = "form-group">
                            <label for = "" class = "col-sm-offset-2 lbl col-sm-1 text-right">Satu </label>
                            <div class = "col-sm-3">
                                <select class="form-control" name="satuan_besar_obat" id="cb_new_satuan_besar_obat">
                                    <?php
                                    for ($i = 0; $i < count($satuan); $i++) {
                                        echo '<option value = "' . $satuan[$i]['id_satuan'] . '">' . $satuan[$i]['nama'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <label for = "" class = "lbl col-sm-1 text-right">Berisi </label>
                            <div class = "col-sm-2"><input min="0" type = "number" name = "jumlah_satuan" class = "form-control" id = "tb_new_jumlah_satuan"></div>
                            <div class = "col-sm-3">
                                <select class="form-control" name="satuan_kecil_obat" id="cb_new_satuan_kecil_obat">
                                    <?php
                                    for ($i = 0; $i < count($satuan); $i++) {
                                        echo '<option value = "' . $satuan[$i]['id_satuan'] . '">' . $satuan[$i]['nama'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for = "" class = "lbl text-right col-sm-3">Harga Beli:</label>
                            <div class = "col-sm-3"><input min="0" type = "number" name = "harga_beli" class = "form-control" id = "tb_new_harga_beli" value="0"></div>
                            <label for = "" class = "lbl text-right col-sm-3">Harga Dasar:</label>
                            <div class = "col-sm-3"><input min="0"  type = "number" name = "harga_jual" class = "form-control" id = "tb_new_harga_jual" value="0"></div>
                        </div>
                        <input style="display:none;" id="txt_act">
                        <input style="display:none;" id="tb_new_id_obat">
                    </div>
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
                </div>
            </div>

        </div>
    </div>

</div>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css" />
<script src="js/jquery.dataTables.min.js"></script>
<script>
    var obat = <?php echo json_encode($data_obat, JSON_PRETTY_PRINT) ?>;
    var satuan = <?php echo json_encode($satuan, JSON_PRETTY_PRINT) ?>;
    $('#loading').hide();
    //get_obat();
    get_content();
    function get_obat()
    {
        $.getJSON("mod/action.php", {request: "<?php echo $objEnkrip->encode('get_data_obat'); ?>"})
                .done(function (result) {
                    obat = result;
                });
    }
    function input_new_false(pesan)
    {
        $('#txt_new_keterangan').text(pesan);
        //$('#detail_new_obat').slideUp();
        $('#btn_new_simpan').slideUp();
    }

    function input_new_check(action)
    {
        var kode = $('#tb_new_kode_obat').val();
        var nama = $('#tb_new_nama_obat').val();
        var isi = $('#tb_new_jumlah_satuan').val();
        var harga_beli = $('#tb_new_harga_beli').val();
        var harga_jual = $('#tb_new_harga_jual').val();
        var status = true;
        $('#txt_new_keterangan').text("");
        //console.log(kode);
        //console.log(nama);
        /*
         if (kode == "")
         {
         //input_new_false("Kode Harus Diisi !");
         input_new_false("Kode Harus Diisi");
         $('#tb_new_kode_obat').focus();
         status = false;
         }
         */ //else
        if (nama == "")
        {
            //input_new_false("Nama Harus Diisi !");
            input_new_false("Nama Harus Diisi Dengan Benar");
            //$('#tb_new_nama_obat').focus();
            status = false;
        }

        else if (!check_number(isi))
        {
            input_new_false("Jumlah Harus Diisi Dengan Benar!");
            $('#tb_new_jumlah_satuan').focus();
            status = false;
        }

        else if (!check_number(harga_beli))
        {
            input_new_false("Harga Harus Diisi Dengan Benar!");
            $('#tb_new_harga_beli').focus();
            status = false;
        }

        else if (!check_number(harga_jual))
        {
            input_new_false("Harga Harus Diisi Dengan Benar!");
            $('#tb_new_harga_jual').focus();
            status = false;
        }

        var act = $('#txt_act').text();
        if (action == 1 && act != "edit")
        {
            var i;
            for (i = 0; i < obat.length; i++)
            {
                if (obat[i]['nama'] == nama)
                {
                    input_new_false("Nama Obat Sudah Ada !");
                    status = false;
                }
                if (obat[i]['barcode'] == kode)
                {
                    input_new_false("Kode Obat Sudah Ada !");
                    status = false;
                }
            }
        }

        if (status)
            $('#btn_new_simpan').slideDown();

        return status
    }

    function search_by(dtArray, field_by, field_content, field_search) {
        var result = '';
        var i;
        for (i = 0; i < dtArray.length; i++) {
            if (field_content == dtArray[i][field_by]) {
                result = dtArray[i][field_search];
            }
        }

        return result;
    }

    function clear_string(str)
    {
        return str.replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
    }

    function check_string(str)
    {
        return str.replace(/[`~!@#$%^&*()_|+\-=?:'",<>\{\}\[\]\\\/]/gi, '');
    }

    function check_number(val_id)
    {
        var ret = true;
        if (isNaN(val_id) || val_id < 0 || val_id == '')
        {
            val_id = 0;
            ret = false;
        }
        return ret;
    }
    function get_content()
    {
        $('#loading').show();

        $.ajax({
            type: "GET",
            url: "mod/content.php?request=<?php echo $objEnkrip->encode('data_obat'); ?>",
            success: function (data) {

                $('#data_content').empty().append(data);
                $('#loading').hide();

            }
        });
        get_obat();
    }

    jQuery(document).ready(function () {
        $(document).on("change keyup", '#tb_new_kode_obat, #tb_new_nama_obat, #tb_new_jumlah_satuan', function ()
        {
            input_new_check('1');
        });
        //init_table();
        $('#btn_add').on("click", function ()
        {
            $('#tb_new_kode_obat').val('');
            $('#tb_new_nama_obat').val('');
            $('#tb_new_jumlah_satuan').val(1);
            $('#tb_new_harga_beli').val(0);
            $('#tb_new_harga_jual').val(0);
            $('#tb_new_nama_obat').focus();
            $('#txt_act').text("add");
            $('#txt_judul_modal').text("Tambah Data Obat");
            $('#mod_add_data').modal("show");
            //get_content();
        });



        $('#btn_new_simpan').on('click', function () {
            var act = $('#txt_act').text();
            if (input_new_check('1'))
            {
                var kode = check_string($('#tb_new_kode_obat').val());
                var nama = check_string($('#tb_new_nama_obat').val());
                var satuan_besar = $('#cb_new_satuan_besar_obat').val();
                var isi = check_string($('#tb_new_jumlah_satuan').val());
                var satuan_kecil = $('#cb_new_satuan_kecil_obat').val();
                var harga_beli = check_string($('#tb_new_harga_beli').val());
                var harga_jual = check_string($('#tb_new_harga_jual').val());
                var id_obat = $('#tb_new_id_obat').text();
                //var group = $('#cb_new_group_obat').val();
                //var jenis = $('#cb_new_jenis_obat').val();
                //var tipe = $('#cb_new_type_obat').val();
                var act = $('#txt_act').text();
                var action = "";
                switch (act)
                {
                    case 'add':
                        action = "<?php echo $objEnkrip->encode('add_data_obat'); ?>";
                        break;
                    case 'edit':
                        action = "<?php echo $objEnkrip->encode('update_data_obat'); ?>";
                        break;
                    case 'del':
                        action = "<?php echo $objEnkrip->encode('delete_data_obat'); ?>";
                        break;
                }

                var data_send = kode + ';' + nama + ';' + satuan_besar + ';' + isi + ';' + satuan_kecil + ';' + harga_beli + ';' + harga_jual + ';' + id_obat;//+ ';' + group + ';' + jenis + ';' + tipe;
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
