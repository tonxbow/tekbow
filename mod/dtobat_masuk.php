<?php
//echo $objFunction->get_daydate();
//$data_obat = Array(
//    array('id_data_obat'=>'obt.0001','nama'=>'bodrex','satuan_besar'=>'1','satuan_kecil'=>2,'jumlah_satuan_kecil'=>'10',)
//);
/*
  $firdausdb = new db('localhost', 'root', '', 'firdausdb');
  $type = $firdausdb->get_data($firdausdb, 'tbl_obat', '*', '', '', '');
  $type_obat = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');

  for ($i=1040;$i<count($type);$i++)
  {
  $data['id_data_obat'] = $type[$i]['autonum'];
  $data['id_group_obat'] = $type[$i]['kode_group'];
  $data['id_jenis_obat'] = $type[$i]['kode_jenis'];
  $data['id_type_obat'] = $type[$i]['kode_type'];
  $data['nama'] = $type[$i]['nama_obat'];
  $data['barcode'] = $type[$i]['kode_obat'];
  $data['satuan_besar'] = $objFunction->search_by($satuan,'nama',$type[$i]['satuan_besar'],'id_satuan');
  $data['satuan_kecil'] = $objFunction->search_by($satuan,'nama',$type[$i]['satuan_kecil'],'id_satuan');
  $data['jumlah_satuan_kecil'] = $type[$i]['isi_sb'];
  $data['harga_dasar'] = $type[$i]['harga_dasar'];
  $data['harga_jual'] = $type[$i]['harga_ppn'];

  $db -> add_data($db,'data_obat',$data);
  }

  $objFunction->debugArray($type);
  $objFunction->debugArray($type_obat);
  exit();
 *
 */



$data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
$satuan = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');
$group_obat = $db->get_data($db, 'group_obat', '*', '', 'nama ASC', '');
$type_obat = $db->get_data($db, 'type_obat', '*', '', 'nama ASC', '');
$jenis_obat = $db->get_data($db, 'jenis_obat', '*', '', 'nama ASC', '');
$user = $db->get_data($db, 'user', '*', '', 'nama ASC', '');
$setting = $db->get_data($db, 'setting', '*', '', '', '');
//$objFunction->debugArray($data_obat);
//$objFunction->debugArray($satuan);



$datafield_obat = array('id_data_obat', 'nama', 'satuan_besar', 'satuan_kecil', 'jumlah_satuan_kecil', 'harga_jual', 'stock_masuk', 'stock_keluar');
?>
<style>
    .pos{
        padding: 1px 20px 10px 20px;
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

    th{
        text-align: center;
        font-size: 13px;
    }
    td{
        font-size: 12px;
    }

    .selection
    {
        width: 100%;
        margin-top: 5px;
    }

    table.scroll {
        width: 100%;  /* Optional */
        //height: 550px;
    }

    table.scroll tbody,
    table.scroll thead, table.scroll tfoot { display: block; }

    table.scroll tbody {
        height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    table.scroll tbody td {
        white-space: nowrap;
    }

</style>
<div class="pos"  style="background-color: #ffffff; height: 85%">
    <div class="row pemisah" style="margin-bottom: 30px;">
        <!--
        <div class="col-sm-12" style="margin-bottom: 20px; background-color: #999999">

        </div>-->
        <div class="col-sm-3 text-center" style="background-color: #428bca"> <h4 style="color: #fff;">Obat Masuk</h4></div>
        <div class="col-sm-1 text-right"><label class="lbl">Tanggal :</label></div>
        <div class="col-sm-2"><input type="text" id="tgl_trx" style="text-align: center;" class="form-control" value="<?php echo $objFunction->get_daydate(); ?>"></div>
        <div class="col-sm-1 text-right"><label class="lbl">Vendor :</label></div>
        <div class="col-sm-3"><input type="text" id="tb_nama_vendor" style="" class="form-control"></div>
        <div class="col-sm-2"><button id="btn_new_transaksi" class="btn btn-primary btn-block"><i class="fa fa-plus-circle"></i> Transaksi Baru (F1)</button></div>
        <div class="col-sm-2"><button id="btn_cancel_transaksi" class="btn btn-danger btn-block"><i class="fa fa-plus-circle"></i> Batal Transaksi</button></div>
    </div>

    <div class="row pemisah" id="view_pos">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading text-center" style="padding: 1px 0 0 0">
                    <h4 style="color: #fff;">Input Obat</h4>
                </div>
                <div class="panel-body">
                    <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;" title="(F2)">Kode:</div></label></div>
                    <div class="col-sm-10"><input type="text" id="tb_kode_obat" class="form-control" maxlength="20" title="Kode Obat (F2)"></div>
                    <div class="col-sm-12 form-entry"></div>

                    <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Nama :</div></label></div>
                    <div class="col-sm-10">
                        <!--
                        <select type="text" id="cb_nama_obat" class="selection" >
                            <option value="0">-=Pilih Obat=-</option>
                        <?php
                        for ($i = 0; $i < count($data_obat); $i++) {
                            //$sisa_stock = $data_obat[$i]['stock_masuk']-$data_obat[$i]['stock_keluar'];
                            echo '<option value="' . $data_obat[$i]['id_data_obat'] . '">' . $data_obat[$i]['nama'] . '</option>';
                        }
                        ?>

                        </select>-->
                        <input type="text" id="cb_nama_obat" class="form-control">
                    </div>
                    <div id="keterangan_obat">
                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Batch :</div></label></div>
                        <div class="col-sm-10"><input type="text" id="tb_batch_number" class="form-control" maxlength="20" title="Batch Number"></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Expire :</div></label></div>
                        <div class="col-sm-10"><input type="text" id="tb_exp_date" class="form-control" maxlength="20" title="Expire Date"></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Jml Bsr :</div></label></div>
                        <div class="col-sm-4"><input type="number" id="tb_jml_besar" class="form-control"></div>
                        <div class="col-sm-6"><input type="text" id="tb_satuan_jml_besar" class="form-control" readonly=""></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Jml Kcl :</div></label></div>
                        <div class="col-sm-4"><input type="number" id="tb_jml_kecil" class="form-control"></div>
                        <div class="col-sm-6"><input type="text" id="tb_satuan_jml_kecil" class="form-control" readonly=""></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Harga :</div></label></div>
                        <div class="col-sm-5"><input type="number" id="tb_harga_beli" class="form-control"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Disc(%) :</div></label></div>
                        <div class="col-sm-3"><input min="0" max="100" type="number" id="tb_discount" class="form-control"></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-4 text-right"><label class="lbl"><div style="white-space: nowrap;">Harga Beli :</div></label></div>
                        <div class="col-sm-4"><input type="text" id="tb_harga_jual_satuan" class="form-control" readonly=""></div>
                        <div class="col-sm-4"><input type="text" id="tb_harga_beli_satuan" class="form-control" readonly="" title="harga beli sebelum"></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-4 text-right"><label class="lbl"><div style="white-space: nowrap;">Harga Dasar :</div></label></div>
                        <div class="col-sm-8"><input type="number" id="tb_harga_dasar_satuan" class="form-control"></div>

                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-4 text-right"><label class="lbl"><div style="white-space: nowrap;">Harga Jual (PPN) :</div></label></div>
                        <div class="col-sm-8"><input type="number" id="tb_harga_jual_ppn" class="form-control" readonly=""></div>


                    </div>
                    <div class="col-sm-12 form-entry"></div>
                    <div class="col-sm-12"><button class="btn btn-block btn-danger" id="btn_tambah_data_obat"><i class="fa fa-plus-circle"></i> Tambah Data Obat</button></div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-block btn-primary" id="btn_tambah_obat"><i class="fa fa-plus-circle"></i> Tambah Obat Masuk >>></button>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <table class="table table-bordered table-hover table-striped scroll">
                <thead>
                    <tr>
                        <th  style="width: 45px;">Act</th>
                        <th  style="width: 400px;">Nama</th>
                        <th  style="width: 150px;">Batch</th>
                        <th  style="width: 80px;">Exp</th>
                        <th  style="width: 50px;">Jml</th>
                        <th  style="width: 120px;">Satuan</th>
                        <th  style="width: 120px;">Harga</th>
                    </tr>
                </thead>

                <tbody id="isi_data">
                </tbody>
                <tfoot>
                    <tr>
                        <th  colspan="2" style="width: 300px; white-space: nowrap;"><div style="font-size: 20px;" id="total_item">Total Item : 0</div></th>
                <th  colspan="3" style="width: 50%; vertical-align: middle; text-align: left;"><i class="fa fa-file"></i> id transaksi : <b id="id_trx"></b></th>
                <th  colspan="3" style="width: 50%; vertical-align: middle; text-align: left;"><div style="font-size: 15px; color: red;" >Grand Total : <b id="grand_total">Rp. 0</b></div></th>
                <th  style="width: 250px; white-space: nowrap;"rowspan="2"><div style="font-size: 20px;">-</div></th>
                </tr>
                </tfoot>
            </table>
            <div class="col-sm-1 text-left"><label class="lbl"><div style="white-space: nowrap;">Penerima :</div></label></div>
            <div class="col-sm-3"><input type="text" id="tb_penerima" class="form-control" value="<?php echo $objFunction->search_by($user, 'id_user', $_SESSION['id_user'], 'nama'); ?>"></div>
            <div class="col-sm-offset-4 col-sm-4"><button class="btn btn-primary btn-block" id="btn_simpan_transaksi">Simpan</button></div>
        </div>
    </div>
    <div style="text-align: center;font-family: sans-serif;font-size: 100px; margin-top: 50px;" id="dashboard">
        <b style="font-size: 40px;"><?php echo $objFunction->get_date_format($objFunction->get_daydate()); ?></b>
        <br/>
        <i class="fa fa-clock-o"></i>
        <b id="datetime"></b>
    </div>

</div>

<?php
$labelClass = "lbl col-sm-3 text-right";
$inputClass = "col-sm-9";
?>
<!--Modal Data Obat-->
<div class="modal fade" id="mod_data_obat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #428bca; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tambah Data Obat</h4>
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
                            <div class = "col-sm-2"><input min="1" type = "number" name = "jumlah_satuan" class = "form-control" id = "tb_new_jumlah_satuan" value="1"></div>
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
<!--Modal Konfirmasi-->
<div class="modal fade" id="mod_konfirmasi" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #428bca; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <div class="modal-body" style="color:#000;">
                <div  class="form-horizontal">
                    <div class = "form-group">
                        <div id="txt_id_obat_ubah" style="display: none"></div>
                        <div id="txt_harga_ubah" style="display: none"></div>
                        <div id="txt_data_append" style="display: none"></div>
                        <p style="font-size: 15px; color: #000; text-align: center;" id="txt_mod_konfirmasi"></p>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-5 text-right">
                            <button class="btn btn-primary btn-block" id="btn_mod_konfirmasi_ok">OK</button>

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
<!--Modal Print Preview-->
<div class="modal fade" id="mod_print_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Print Preview</h4>
            </div>
            <div class="modal-body">
                <textarea id="txt_print_preview" style="width: 100%; resize: none;" readonly rows="10"></textarea>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-block" id='btn_print'>Simpan & Print</button>
                <button class="btn btn-danger btn-block" id='btn_save'>Simpan</button>
            </div>
        </div>
    </div>

</div>



<script>
//INISALISASI AWAL
    var obat = <?php echo json_encode($data_obat, JSON_PRETTY_PRINT) ?>;
    var satuan = <?php echo json_encode($satuan, JSON_PRETTY_PRINT) ?>;
    var ac_nama_obat = $.map(obat, function (value, key) {
        return {value: value['nama'], data: value['barcode']};
    });
    var ac_kode_obat = $.map(obat, function (value, key) {
        return {value: value['barcode'], data: value['nama']};
    });
    $('#tgl_trx').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    $('#tb_exp_date').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    input_hide();
    input_new_false("");
    $('#view_pos').hide();
    $('#btn_bayar').hide();
    $('#loading').hide();
    $('#btn_cancel_transaksi').hide();             //$('#cb_nama_obat').select2();
    //SHORTCUT KEYBOARD
    document.addEventListener('keydown', function (e) {
        switch (e.keyCode)
        {
            case 112:
                new_transaksi();
                break;
            case 113:
                $('#tb_kode_obat').focus();
                break;
            case 114:

                break;
            case 115:
                $('#total_bayar').focus();
                break;
            case 119:
                //alert('F8'); //F1
                if (check_input())
                    add_obat();
                break;
        }
    });

    //FUNGSI UMUM
    function toRp(a, b, c, d, e) {
        e = function (f) {
            return f.split('').reverse().join('')
        };
        b = e(parseInt(a, 10).toString());
        for (c = 0, d = ''; c < b.length; c++) {
            d += b[c];
            if ((c + 1) % 3 === 0 && c !== (b.length - 1)) {
                d += '.';
            }
        }
        return'Rp. ' + e(d);
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
    function get_date() {
        var currentTime = new Date();
        //console.log(currentTime);
        var Month = currentTime.getMonth();
        var Datez = currentTime.getDate().toString();
        Month += 1;
        Month = Month.toString();
        //console.log(Month.length);
        if (Month.length == 1)
            Month = '0' + Month;
        if (Datez.length == 1)
            Datez = '0' + Datez;
        return currentTime.getFullYear() + '-' + Month + '-' + Datez;
    }
    jQuery.fn.getIdArray = function () {
        var ret = [];
        $('[id]', this).each(function () {
            ret.push(this.id);
        });
        return ret;
    };
//FUNGSI KHUSUS
    function input_hide() {
        $('#btn_tambah_obat').hide();
        $('#keterangan_obat').slideUp("slow");
    }

    function input_show()
    {
        $('#keterangan_obat').fadeIn();
    }

    function reset_pos()
    {
        $('#tb_kode_obat').val("");
        $("#tb_batch_number").val('');
        $("#tb_exp_date").val('');
        $("#cb_nama_obat").val('');
        $("#tb_jml_besar").val('');
        $("#tb_jml_kecil").val('');
        $("#tb_harga_beli").val('');
        $("#tb_harga_dasar_satuan").val('');
        $("#tb_harga_jual_satuan").val('');
        $("#tb_discount").val('');
        $('#btn_tambah_obat').slideUp();
        $('#btn_tambah_data_obat').slideDown();

        $("#tb_new_harga_beli").val('');
        $("#tb_new_harga_jual").val('');
        $("#tb_new_kode_obat").val('');
        $("#tb_new_nama_obat").val('');
        $("#tb_new_jumlah_satuan").val('');
        $('#dashboard').show();
        //$("#btn_new_simpan").slideUp();
    }

    function check_input()
    {
        //var kode = $('#tb_kode_obat').val();
        var nama = $('#cb_nama_obat').val();
        var harga = $('#tb_harga_beli').val();
        var jumlah_kecil = $('#tb_jml_kecil').val();
        var ret = false;
        if (nama != '' && parseInt(harga) > 0 && parseInt(jumlah_kecil) > 0)
        {
            $('#btn_tambah_obat').slideDown();
            ret = true;
        }
        else
        {
            if (nama == '')
            {
                input_hide();
            }

            $('#btn_tambah_obat').slideUp();
        }
        if (nama == '')
            $('#btn_tambah_data_obat').slideDown();
        else
            $('#btn_tambah_data_obat').slideUp();
        return ret;
    }

    function input_new_true()
    {
        if ($('#tb_new_kode_obat').val() != '' && $('#tb_new_nama_obat').val() != '')
        {
            $('#txt_new_keterangan').text("");
            //$('#detail_new_obat').slideDown();
            $('#btn_new_simpan').slideDown();
        }
    }

    function input_new_false(pesan)
    {
        $('#txt_new_keterangan').text(pesan);
        //$('#detail_new_obat').slideUp();
        //$('#btn_new_simpan').slideUp();
    }

    function remove_row(id)
    {
        $('#row_' + id).remove();
        get_grand_total();
    }

    function set_notifikasi(teks)
    {
        $('#txt_keterangan').text(teks);
    }
    function ubah_jumlah(id)
    {
        var jumlah = $('#jml_' + id).val();
        var harga = parseInt($('#harga_' + id).text().replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",.<>\{\}\[\]\\\/]/gi, ''));
        var stock = $('#stock_' + id).text();
        var notifikasi = "";
        //console.log(stock);
        if (isNaN(jumlah) || parseInt(jumlah) > stock || jumlah == '')
        {
            if (parseInt(jumlah) > stock)
            {
                jumlah = clear_string(stock);
                console.log(jumlah);
                //$('#jml_' + id).val(stock);
                $('#jml_' + id).val(jumlah);
                notifikasi = "1";
            }
            else
            {
                jumlah = stock;
                //$('#jml_' + id).val(stock);
                $('#jml_' + id).val('1');
            }
        }

        $('#total_' + id).text(toRp(jumlah * harga));
        get_grand_total();
        if (notifikasi != "")
            alert("Jumlah Melebihi Stock !");
    }

    function get_grand_total()
    {
        var grand_total = 0;
        var subtotal = 0;
        //console.log(count);
        var i = 0, x = 0;
        var ar_isi_data = $("#isi_data").getIdArray();
        var ar_total = new Array();
        for (i = 0; i < ar_isi_data.length; i++)
        {
            //console.log(ar_isi_data[i].search('total_'));
            if (ar_isi_data[i].search('harga_') != -1)
            {
                ar_total[x] = ar_isi_data[i];
                x++;
            }
        }
        //console.log(ar_total);     $('#total_item').text("Total Item : " + ar_total.length);
        for (i = 0; i < ar_total.length; i++)
        {
            subtotal = parseInt($('#' + ar_total[i]).text().replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",.<>\{\}\[\]\\\/]/gi, ''));
            if (!isNaN(subtotal))
            {
                grand_total += subtotal;
            }
            //console.log('subtotal' + subtotal);
            //  console.log(grand_total);
        }

        if (grand_total <= 0)
        {
            $('#kembali_uang').text(toRp(0));
            $('#total_bayar').val('');
            $('#btn_bayar').slideUp();
        }

        $("#grand_total").text(toRp(grand_total));
        check_bayar();
    }

    function get_id_trx()
    {
        var url = "mod/action.php?request=<?php echo $objEnkrip->encode('id_transaksi'); ?>'&jenis='<?php echo $objEnkrip->encode('obat_masuk'); ?>";
        $.getJSON(url, function (result) {
            $('#id_trx').text(result['id_transaksi']);
        });
    }

    function new_transaksi()
    {
        $('#dashboard').hide();
        $('#btn_new_transaksi').hide();
        $('#btn_cancel_transaksi').show();
        get_id_trx();
        $('#sts_trx').attr("disabled", true);
        input_hide();
        $('#isi_data').empty();
        get_grand_total();
        $('#view_pos').slideDown("slow");
        $.getJSON("mod/action.php", {request: "<?php echo $objEnkrip->encode('get_data_obat'); ?>"})
                .done(function (result) {
                    obat = result;
                });
        $('#tb_kode_obat').focus();
//console.log("Obat : \n");
        //      console.log(obat);
        ac_nama_obat = $.map(obat, function (value, key) {
            return {value: value['nama'], data: value['barcode']};
        });
        ac_kode_obat = $.map(obat, function (value, key) {
            return {value: value['barcode'], data: value['nama']};
        });
        $('#mod_data_obat').modal("hide");
        //console.log("acc : \n" );
        // console.log(ac_nama_obat);

        $('#tb_kode_obat').autocomplete({
            lookup: ac_kode_obat,
            lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                return re.test(suggestion.value);
            },
            onSelect: function (suggestion) {
                $("#cb_nama_obat").val(search_by(obat, 'barcode', $('#tb_kode_obat').val(), 'nama'));
                $("#tb_satuan_jml_besar").val(search_by(satuan, 'id_satuan', search_by(obat, 'barcode', $('#tb_kode_obat').val(), 'satuan_besar'), 'nama'));
                $("#tb_satuan_jml_kecil").val(search_by(satuan, 'id_satuan', search_by(obat, 'barcode', $('#tb_kode_obat').val(), 'satuan_kecil'), 'nama'));
                $("#tb_harga_beli_satuan").val(search_by(obat, 'barcode', $('#tb_kode_obat').val(), 'harga_dasar'));
                $("#tb_harga_jual_ppn").val(search_by(obat, 'barcode', $('#tb_kode_obat').val(), 'harga_jual'));
                $("#tb_harga_jual_ppn").attr('title', $("#tb_harga_jual_ppn").val());
                var harga_jual = $("#tb_harga_jual_ppn").val();
                $("#tb_harga_dasar_satuan").val(Math.round(harga_jual / 1.1));
                if ($("#cb_kode_obat").val() != '')
                    input_show();
                check_input();
            },
            onInvalidateSelection: function () {
                reset_pos();
                input_hide();
                check_input();
            }
        });

        $('#cb_nama_obat').autocomplete({
            lookup: ac_nama_obat,
            lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                return re.test(suggestion.value);
            },
            onSelect: function (suggestion) {
                var val_obat = search_by(obat, 'nama', $('#cb_nama_obat').val(), 'id_data_obat');
                if (val_obat != 0)
                {
                    $("#tb_kode_obat").val(search_by(obat, 'id_data_obat', val_obat, 'barcode'));
                    $("#tb_satuan_jml_besar").val(search_by(satuan, 'id_satuan', search_by(obat, 'id_data_obat', val_obat, 'satuan_besar'), 'nama'));
                    $("#tb_satuan_jml_kecil").val(search_by(satuan, 'id_satuan', search_by(obat, 'id_data_obat', val_obat, 'satuan_kecil'), 'nama'));
                    $("#tb_harga_beli_satuan").val(search_by(obat, 'id_data_obat', val_obat, 'harga_dasar'));
                    $("#tb_harga_jual_ppn").val(search_by(obat, 'id_data_obat', val_obat, 'harga_jual'));
                    $("#tb_harga_jual_ppn").attr('title', $("#tb_harga_jual_ppn").val());
                    var harga_jual = $("#tb_harga_jual_ppn").val();
                    $("#tb_harga_dasar_satuan").val(Math.round(harga_jual / 1.1));

                    //console.log(harga_jual_sebelum);
                    input_show();
                }
                check_input();
            },
            onInvalidateSelection: function () {
                reset_pos();
                input_hide();
                check_input();
            }
        });

    }


    function end_transaksi()
    {
        $('#view_pos').slideUp();
        $('#btn_new_transaksi').show();
        $('#btn_cancel_transaksi').hide();
        $('#tgl_trx').val(get_date());
        $('#sts_trx').removeAttr("disabled");
        reset_pos();
    }

    function check_bayar()
    {
        var total = $('#grand_total').text().replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        var bayar = $('#total_bayar').val();
        if (isNaN(bayar))
        {
            $('#total_bayar').val('');
        }
        if (total > 0)
        {

            if (parseInt(bayar) >= parseInt(total))
            {

                $('#kembali_uang').text(toRp(bayar - total));
                $('#btn_bayar').slideDown();
            }
            else
            {
                //alert('masukx');
                $('#kembali_uang').text('0');
                $('#btn_bayar').slideUp();
            }
        }

    }

    function get_satuan(id_obat)
    {
        var harga_satuan_kecil = search_by(obat, 'id_data_obat', id_obat, 'harga_jual');
        var harga_satuan_besar = search_by(obat, 'id_data_obat', id_obat, 'harga_jual') * search_by(obat, 'id_data_obat', id_obat, 'jumlah_satuan_kecil');
        var laba = 1 + parseInt($('#sts_trx').val()) / 100;
        harga_satuan_kecil = Math.round(harga_satuan_kecil * laba);
        harga_satuan_besar = Math.round(harga_satuan_besar * laba);
        $('#satuan_obat').empty().append(
                '<option value="' + harga_satuan_kecil + '">' + search_by(satuan, 'id_satuan', search_by(obat, 'id_data_obat', id_obat, 'satuan_kecil'), 'nama') + '</option>'
                + '<option value="' + harga_satuan_besar + '">' + search_by(satuan, 'id_satuan', search_by(obat, 'id_data_obat', id_obat, 'satuan_besar'), 'nama') + '</option>'
                )
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

    function search_barcode(barcode)
    {
        var ar_isi_data = $("#isi_data").getIdArray();
        var ar_total = new Array();
        var temp, x = 0;
        var ret = false;
        for (i = 0; i < ar_isi_data.length; i++)
        {
            //console.log(ar_isi_data[i].search('total_'));
            if (ar_isi_data[i].search('kode_') != -1)
            {
                ar_total[x] = ar_isi_data[i];
                x++;
            }

        }
        var tbl_barcode;
        for (i = 0; i < ar_total.length; i++)
        {
            temp = '#' + ar_total[i];
            tbl_barcode = clear_string($(temp).text());
            //console.log(tbl_barcode + "==" + barcode);

            if (tbl_barcode == barcode)
            {
                ret = temp;
            }
        }

        return ret;
    }
    function add_jumlah(id, jumlah)
    {
        var jml = parseInt($('#jml_' + id).val()) + parseInt(jumlah);
        $('#jml_' + id).val(jml);
        ubah_jumlah(id);
    }

    function add_obat()
    {
        var kode = $('#tb_batch_number').val();
        var nama = $('#cb_nama_obat').val();
        var id_obat = search_by(obat, 'nama', $('#cb_nama_obat').val(), 'id_data_obat');
        var satuan = $('#tb_satuan_jml_kecil').val();
        var jumlah = $('#tb_jml_kecil').val();
        var harga = $('#tb_harga_beli').val();
        var expire = $('#tb_exp_date').val();
        var harga_satuan = $('#tb_harga_jual_satuan').val();
        var harga_sebelum = $('#tb_harga_beli_satuan').val();
        var harga_jual = $('#tb_harga_jual_ppn').val();
        var harga_jual_sebelum = $("#tb_harga_jual_ppn").attr('title');
        var count = parseInt($('#isi_data tr').length) + 1;
        var status = 0;
        //var id_barcode = search_barcode(kode);

        //console.log(id_barcode);
        var data_append = '<tr id="row_' + count + '"><td style = "width: 45px; vertical-align:middle;" class = "text-center"> <button onclick="remove_row(' + count + ')"> <i class = "fa fa-trash-o"> </i></button> </td>' + '<td id="id_' + count + '" type="hidden" style="display:none;"> ' + id_obat + ' </td>' +
                '<td id="nama_' + count + '" style = "width: 350px; font-size:12px; vertical-align:middle;"> ' + nama + ' </td>' +
                '<td id="kode_' + count + '" style = "width: 150px; font-size:13px; vertical-align:middle;"> ' + kode + ' </td>' +
                '<td id="expire_' + count + '" style = "width: 80px; font-size:13px;" class = "text-center">' + expire + '</td>' +
                '<td id="jumlah_' + count + '" style = "width: 50px; font-size:13px;" class = "text-center">' + jumlah + '</td>' +
                '<td id="satuan_' + count + '" style = "width: 120px; font-size:13px; vertical-align:middle;"> ' + satuan + ' </td>' +
                '<td id="harga_' + count + '" style = "width: 120px; font-size:15px; vertical-align:middle;"> ' + toRp(harga) + ' </td></tr>';

        if (harga_jual != harga_jual_sebelum)
        {
            $('#txt_id_obat_ubah').text(id_obat);
            $('#txt_mod_konfirmasi').text(
                    "Harga Jual Obat Mengalami Perubahan, Harga Obat Sebelum " + toRp(harga_jual_sebelum) + "/" + satuan + " Harga Obat Sekarang " + toRp(harga_jual) + "/" + satuan +
                    " ,Apakah Anda Akan Memperbaharui Database Harga Obat?"
                    );
            $('#mod_konfirmasi').modal("show");
            $('#txt_data_append').text(data_append);
            $('#txt_harga_ubah').text(harga_satuan + ";" + harga_jual);
            status = 1;
        }
        else if (harga_satuan != harga_sebelum)
        {
            var harga_obat = harga_satuan + ";"
            $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('update_harga_data_obat'); ?>", id_obat: id_obat, harga: harga_obat})
                    .done(function (result) {
                        if (result != "success")
                            console.log("Perubahan Gagal, Keterangan : \n\n " + result);
                    });
        }

        if (status != 1)
        {
            var i;

            for (i = 0; i < obat.length; i++)
            {
                if (obat[i]['nama'] == nama)
                {
                    status = 2;
                    i = obat.length + 1;
                }
            }
        }

        if (status == 2)
        {
            $('#isi_data').append(data_append);
            get_grand_total();
            input_hide();
            reset_pos();
        }
        else if (status == 0)
        {
            set_notifikasi("Nama Obat Tidak Ada");
            $('#cb_nama_obat').focus();
        }

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

        if (nama == "")
        {
            //input_new_false("Nama Harus Diisi !");
            input_new_false("Nama Harus Diisi Dengan Benar");
            $('#tb_new_nama_obat').focus();
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


        if (action == '1')
        {
            var i;

            for (i = 0; i < obat.length; i++)
            {
                //console.log(obat[i]['nama']);
                if (obat[i]['nama'] == nama.toUpperCase())
                {
                    //alert("masuk");
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

        //if (status)
        //$('#btn_new_simpan').slideDown();
        return status
    }

    function set_harga_beli()
    {
        var jumlah = $('#tb_jml_kecil').val();
        var harga_satuan = $('#tb_harga_beli_satuan').val();
        var diskon = $('#tb_discount').val();

        $('#tb_harga_beli').val(jumlah * harga_satuan);

        var harga_beli = $('#tb_harga_beli').val();
        var harga_beli_satuan = Math.round((harga_beli - (harga_beli * diskon / 100)) / jumlah);
        $('#tb_harga_jual_satuan').val(harga_beli_satuan);
    }

    function save_transaksi(print) {
        var struk = "";
        var ar_isi_data = $("#isi_data").getIdArray();
        var ar_total = new Array();
        var temp = new Array();
        var x = 0, i;
        for (i = 0; i < ar_isi_data.length; i++)
        {
            //console.log(ar_isi_data[i].search('total_'));
            if (ar_isi_data[i].search('row_') != -1)
            {
                temp = ar_isi_data[i].split('_');
                ar_total[x] = temp[1];
                x++;
            }
        }


        var vendor = $('#tb_nama_vendor').val();
        var id_trx = $('#id_trx').text();
        var tgl_trx = $('#tgl_trx').val();
        //id;tgl;vendor;total_item
        struk += id_trx + ";";
        struk += tgl_trx + ";";
        struk += vendor + ";";
        struk += x + ";";
        struk += "<TONX>";

        for (i = 0; i < ar_total.length; i++)
        {
            //nama,jumlah,satuan,batch,expire,harga,id_obat
            struk += $('#nama_' + ar_total[i]).text().substring(0, 30) + ',';
            struk += $('#jumlah_' + ar_total[i]).text() + ',' + $('#satuan_' + ar_total[i]).text() + ',' + $('#kode_' + ar_total[i]).text() + ',' + $('#expire_' + ar_total[i]).text() + ',' + $('#harga_' + ar_total[i]).text() + ',' + $('#id_' + ar_total[i]).text() + ';';
        }

        struk += "<TONX>";
        struk += $('#grand_total').text() + ";";
        struk += $('#tb_penerima').val() + ";";

        struk = struk.trim();

        $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('barang_masuk'); ?>", data: struk, print: print})
                .done(function (result) {
                    console.log("Status : " + result);
                    if (result == "success")
                        alert("Berhasil");
                    else
                        alert("Gagal");
                });
        end_transaksi();
        $('#mod_print_preview').modal("hide");
    }
    //FUNGSI EVENT
    $(function () {
        $('#btn_mod_konfirmasi_ok').on("click", function () {

            var id_obat = check_string($('#txt_id_obat_ubah').text());
            var harga_obat = check_string($('#txt_harga_ubah').text());

            $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('update_harga_data_obat'); ?>", id_obat: id_obat, harga: harga_obat})
                    .done(function (result) {
                        if (result != "success")
                            alert("Perubahan Gagal, Keterangan : \n\n " + result);
                        else
                        {
                            $('#isi_data').append($('#txt_data_append').text());
                            $('#mod_konfirmasi').modal("hide");
                            $('#txt_data_append').text();
                            get_grand_total();
                            input_hide();
                            reset_pos();
                        }

                    });


        });
        $('#total_bayar').on("keyup change", function () {
            check_bayar();
        });
        $('#tb_jml_besar').on("change keyup", function () {
            var jml_besar = $('#tb_jml_besar').val();
            var val_obat = search_by(obat, 'nama', $('#cb_nama_obat').val(), 'id_data_obat');
            if (check_number(jml_besar))
            {
                var jml_kecil = search_by(obat, 'id_data_obat', val_obat, 'jumlah_satuan_kecil') * jml_besar;
                $('#tb_jml_kecil').val(jml_kecil);
                set_harga_beli();
            }
            else
            {
                jml_besar = 0;
                $('#tb_jml_besar').val(jml_besar);
            }
            check_input();
        });
        $('#tb_jml_kecil').on("change keyup", function () {
            var jml_kecil = $('#tb_jml_kecil').val();
            var val_obat = search_by(obat, 'nama', $('#cb_nama_obat').val(), 'id_data_obat');
            if (!check_number(jml_kecil))
            {
                jml_kecil = '';
                $('#tb_jml_kecil').val(jml_kecil);

            }
            else
            {
                //console.log(jml_kecil);
                var jml_besar = Math.floor(jml_kecil / search_by(obat, 'id_data_obat', val_obat, 'jumlah_satuan_kecil'));
                $('#tb_jml_besar').val(jml_besar);
                set_harga_beli();
            }
            check_input();
        });
        $('#tb_harga_dasar_satuan').on("change keyup", function () {
            var harga_dasar = $('#tb_harga_dasar_satuan').val();
            if (!check_number(harga_dasar))
            {
                harga_dasar = '';
                $('#tb_harga_dasar_satuan').val(harga_dasar);
            }
            else
            {
                var harga_jual = Math.round(parseInt(harga_dasar) + (harga_dasar * 10 / 100));
                $('#tb_harga_jual_ppn').val(harga_jual);
            }
        });
        $(document).on("change keyup", "#tb_harga_beli, #tb_discount", function () {
            var harga_beli = $('#tb_harga_beli').val();
            var jml_kecil = $('#tb_jml_kecil').val();
            var diskon = $('#tb_discount').val();
            if (!check_number(harga_beli) || !check_number(jml_kecil))
            {
                harga_beli = '';
                $('#tb_harga_beli').val(harga_beli);
            }
            else
            {
                if (!check_number(diskon) || parseInt(diskon) > 100)
                {
                    diskon = '';
                    $('#tb_discount').val(diskon);
                }
                var harga_beli_satuan = Math.round((harga_beli - (harga_beli * diskon / 100)) / jml_kecil);
                $('#tb_harga_jual_satuan').val(harga_beli_satuan);
            }

            check_input();
        });
        $(document).on("change keyup", "#tb_new_jumlah_satuan, #tb_new_harga_beli, #tb_new_harga_jual", function () {
            input_new_check();
        });

        $('#btn_new_simpan').on('click', function () {
            if (input_new_check('1'))
            {
                var kode = check_string($('#tb_new_kode_obat').val());
                var nama = check_string($('#tb_new_nama_obat').val());
                var satuan_besar = $('#cb_new_satuan_besar_obat').val();
                var isi = check_string($('#tb_new_jumlah_satuan').val());
                var satuan_kecil = $('#cb_new_satuan_kecil_obat').val();
                var harga_beli = check_string($('#tb_new_harga_beli').val());
                var harga_jual = check_string($('#tb_new_harga_jual').val());
                //var group = $('#cb_new_group_obat').val();
                //var jenis = $('#cb_new_jenis_obat').val();
                //var tipe = $('#cb_new_type_obat').val();
                var data_send = kode + ';' + nama + ';' + satuan_besar + ';' + isi + ';' + satuan_kecil + ';' + harga_beli + ';' + harga_jual;//+ ';' + group + ';' + jenis + ';' + tipe;
                $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('add_data_obat'); ?>", data: data_send})
                        .done(function (result) {
                            if (result == "fail")
                                alert("Penyimpanan Gagal, Keterangan : \n\n " + result);
                            else
                            {
                                new_transaksi();
                                end_transaksi();
                                reset_pos();
                                alert("Berhasil");
                            }
                        });
            }
        });
        $('#btn_simpan_transaksi').on("click", function () {

            get_grand_total();
            var struk = "";
            var ar_isi_data = $("#isi_data").getIdArray();
            var ar_total = new Array();
            var temp = new Array();
            var x = 0, i;
            for (i = 0; i < ar_isi_data.length; i++)
            {
                //console.log(ar_isi_data[i].search('total_'));
                if (ar_isi_data[i].search('row_') != -1)
                {
                    temp = ar_isi_data[i].split('_');
                    ar_total[x] = temp[1];
                    x++;
                }

            }

            //console.log(ar_total);
            struk += "ID trx\t: " + $('#id_trx').text() + "\n";
            struk += "Vendor\t: " + $('#tb_nama_vendor').val() + "\n";
            struk += "Tgl trx\t: " + $('#tgl_trx').val() + "\n";
            struk += "------------------------------\n";
            for (i = 0; i < ar_total.length; i++)
            {
                struk += $('#nama_' + ar_total[i]).text().substring(0, 30) + '\n';
                struk += '\t\tx' + $('#jumlah_' + ar_total[i]).text() + $('#satuan_' + ar_total[i]).text() + '\t' + $('#harga_' + ar_total[i]).text() + '\n';
            }

            struk += "------------------------------\n";
            struk += "Total\t\t: " + $('#grand_total').text() + "\n";
            struk += "Penerima\t: " + $('#tb_penerima').val() + "\n";
            //struk += "Kembali\t\t\t" + $('#kembali_uang').text() + "\n";
            if (ar_total.length <= 0)
            {

                $('#btn_print').hide();
                $('#btn_save').hide();
            }
            else
            {
                $('#btn_print').show();
                $('#btn_save').show();
                $('#btn_print').focus();
            }
            $('#txt_print_preview').val(struk);
            $('#mod_print_preview').modal("show");
        });
        $('#btn_print').on("click", function () {
            save_transaksi("ok");
        });
        $('#btn_save').on("click", function () {
            save_transaksi("");
        });
        $('#btn_new_transaksi').on("click", function () {
            new_transaksi();
        });
        $('#btn_tambah_data_obat').on("click", function () {
            $('#mod_data_obat').modal("show");
        });
        $('#btn_cancel_transaksi').on("click", function () {
            end_transaksi();
        });
        $('#btn_tambah_obat').on("click", function () {
            add_obat();
        });
    });
//FUNGSI STANDARD
    function updateClock()
    {
        var currentTime = new Date();
        var currentHours = currentTime.getHours();
        var currentMinutes = currentTime.getMinutes();
        var currentSeconds = currentTime.getSeconds();
        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
        currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
        var timeOfDay = (currentHours < 12) ? "AM" : "PM";
        currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
        currentHours = (currentHours == 0) ? 12 : currentHours;
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        $("#datetime").html(currentTimeString);
    }

    $(document).ready(function ()
    {
        setInterval('updateClock()', 1000);
    });

</script>
