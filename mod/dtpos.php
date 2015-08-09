<?php
//echo $objFunction->get_daydate();
//$data_obat = Array(
//    array('id_data_obat'=>'obt.0001','nama'=>'bodrex','satuan_besar'=>'1','satuan_kecil'=>2,'jumlah_satuan_kecil'=>'10',)
//);
$data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
$satuan = $db->get_data($db, 'satuan', '*', '', '', '');
$setting = $db->get_data($db, 'setting', '*', '', '', '');
//$objFunction->debugArray($data_obat);
//$objFunction->debugArray($satuan);
$running_text = "Selamat Datang " . $_SESSION['username'] . " Di Toko Obat Firdaus";

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
        height: 420px;
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
        <div class="col-sm-3 text-center" style="background-color: #999"> <h4 style="color: #fff;">Point Of Sales</h4></div>
        <div class="col-sm-2 text-right"><label class="lbl">Tanggal Transaksi:</label></div>
        <div class="col-sm-2"><input type="text" id="tgl_trx" style="text-align: center;" class="form-control" readonly value="<?php echo $objFunction->get_daydate(); ?>"></div>
        <div class="col-sm-1 text-right"><label class="lbl">Status :</label></div>
        <div class="col-sm-2"><select type="text" id="sts_trx" class="form-control" ><option value="<?php echo $setting[0]['margin_umum']; ?>">Umum</option><option value="<?php echo $setting[0]['margin_toko']; ?>">Toko</option></select></div>
        <div class="col-sm-2"><button id="btn_new_transaksi" class="btn btn-primary btn-block"><i class="fa fa-plus-circle"></i> Transaksi Baru (F1)</button></div>
        <div class="col-sm-2"><button id="btn_cancel_transaksi" class="btn btn-danger btn-block"><i class="fa fa-plus-circle"></i> Batal Transaksi</button></div>
    </div>

    <div class="row pemisah" id="view_pos">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading text-center" style="padding: 1px 0 0 0">
                    <h4 style="color: #fff;">Input Transaksi</h4>
                </div>
                <div class="panel-body">
                    <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;" title="(F2)">Kode:</div></label></div>
                    <div class="col-sm-10"><input type="text" id="kode_obat" class="form-control" maxlength="20" title="Kode Obat (F2)" onkeyup="return detect_enter(event, this)"></div>

                    <div class="col-sm-12 form-entry"></div>

                    <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Nama :</div></label></div>
                    <div class="col-sm-10">
                        <!--
                        <select type="text" id="nama_obat" class="selection" >
                            <option value="0">-=Pilih Obat=-</option>
                        <?php
                        for ($i = 0; $i < count($data_obat); $i++) {
                            //$sisa_stock = $data_obat[$i]['stock_masuk']-$data_obat[$i]['stock_keluar'];
                            echo '<option value="' . $data_obat[$i]['id_data_obat'] . '">' . $data_obat[$i]['nama'] . '</option>';
                        }
                        ?>

                        </select>-->
                        <input type="text" id="nama_obat" class="form-control" title="Nama Obat">
                    </div>

                    <div class="col-sm-12 form-entry"></div>
                    <div id="keterangan_obat">
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Jumlah :</div></label></div>
                        <div class="col-sm-4"><input type="number" id="jml_obat" class="form-control" value="1" min="1"></div>




                        <div class="col-sm-6">
                            <select type="text" id="satuan_obat" class="form-control">
                                <!--
                                <option value="">-=Pilih Satuan=-</option>
                                <?php
                                for ($i = 0; $i < count($satuan); $i++) {
                                    echo '<option value="' . $satuan[$i]['id_satuan'] . '">' . $satuan[$i]['nama'] . '</option>';
                                }
                                ?>-->
                            </select>
                        </div>
                        <div class="col-sm-12 form-entry"></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Stock :</div></label></div>
                        <div class="col-sm-4"><input type="text" id="stock" class="form-control" readonly></div>
                        <div class="col-sm-2 text-right"><label class="lbl"><div style="white-space: nowrap;">Harga :</div></label></div>
                        <div class="col-sm-4"><input type="text" id="harga_obat" class="form-control" readonly></div>

                        <input type="hidden" id="txt_temp_stock" class="form-control">
                        <input type="hidden" id="txt_stock" class="form-control">

                    </div>
                    <div class="col-sm-12 text-center"><p id="txt_keterangan" style="font-size: 17px;color: red;"><p></div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-block btn-primary" id="btn_tambah_obat"><i class="fa fa-plus-circle"></i> Tambah Obat (F8)</button>
                    <button class="btn btn-danger btn-block" onclick="reset_pos()">Reset Input (F9)</button>
                </div>
            </div>

            <div style="background-color: #999;min-height: 165px; padding: 1px 1px 1px 1px;">
                <div class="col-sm-4 text-right">
                    <h4 style="color: #fff; white-space: nowrap;">Grand Total :</h4>
                </div>
                <div class="col-sm-8">
                    <h4 style="color: #fff;" id="grand_total">Rp.0</h4>
                </div>

                <div class="col-sm-4 text-right">
                    <h4 style="color: #fff; white-space: nowrap;" title="F4">Total Bayar :</h4>
                </div>
                <div class="col-sm-8">
                    <input  min="0" class="form-control" style="font-size: 20px; height: 40px;" type="text" id="total_bayar" title="Total Bayar (F4)">
                </div>

                <div class="col-sm-4 text-right">
                    <h4 style="color: #fff; white-space: nowrap;" >Kembali :</h4>
                </div>
                <div class="col-sm-8">
                    <h4 style="color: #fff;" id="kembali_uang">-</h4>
                </div>
                <div class="col-sm-12">
                    <button class="btn btn-block btn-primary" id="btn_bayar"><i class="fa fa-dollar"></i> Bayar</button>
                </div>

            </div>

        </div>
        <div class="col-sm-8">
            <table class="table table-hover table-striped scroll">
                <thead>
                    <tr>
                        <th  style="width: 45px;">Act</th>
                        <th  style="width: 280px;">Nama</th>
                        <th  style="width: 150px;">Kode</th>
                        <th  style="width: 50px;">Jumlah</th>
                        <th  style="width: 80px;">Satuan</th>
                        <th  style="width: 120px;">Harga</th>
                        <th  style="width: 120px;">Total</th>
                    </tr>
                </thead>

                <tbody id="isi_data">
                </tbody>

                <tfoot>
                    <tr>
                        <th  colspan="2" style="width: 300px; white-space: nowrap;"><div style="font-size: 20px;" id="total_item">Total Item : 0</div></th>
                <th  colspan="3" style="width: 50%; vertical-align: middle; text-align: left;"><i class="fa fa-file"></i> id transaksi : <b id="id_trx"></b></th>
                <th  colspan="3" style="width: 50%; vertical-align: middle; text-align: left;"><i class="fa fa-print"></i> Printer : <?php echo $setting[0]['port'] . ',' . $setting[0]['baudrate'] ?></th>

                <th  style="width: 250px; white-space: nowrap;"rowspan="2"><div style="font-size: 20px;" >-</div></th>

                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div style="width: 100%; border: #999 solid 1px"></div>

    <div style="text-align: center;font-family: sans-serif;font-size: 100px; margin-top: 50px;" id="dashboard">
        <b style="font-size: 80px; color: red;">Toko Obat Firdaus</b>
        <br/>
        <b style="font-size: 40px;"><?php echo $objFunction->get_date_format($objFunction->get_daydate()); ?></b>
        <br/>
        <i class="fa fa-clock-o"></i>
        <b id="datetime"></b>
        <div style="font-size: 20px;"> </div>
        <b style="font-size: 20px; color: blue; " id="dash_grand_total"></b>
        <br/>
        <a href="login.php?ask=<?php echo $objEnkrip->encode('closing') . '&id=' . $objEnkrip->encode($_SESSION['id_user']); ?>"><button class="btn btn-info" style="width: 330px; font-size: 20px; margin-top: 10px;">Closing</button></a>
    </div>

    <div style="width: 95%; background-color: #999;height: 40px;padding-top: 10px;position: absolute;top: 720px;">
        <marquee style="font-size: 15px; color: #fff;"><div id="running_text"></div></marquee>
    </div>
</div>

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
                <button class="btn btn-primary btn-block" id='btn_print'>Print</button>
            </div>
        </div>
    </div>
    <div id="loading" style="width: 100%; text-align: center; position: fixed;z-index: 999; top: 40%;"><img src="images/loading.gif" style="width: 150px;"></div>
</div>



<script>
//INISALISASI AWAL
    var obat = <?php echo json_encode($data_obat, JSON_PRETTY_PRINT) ?>;
    var satuan = <?php echo json_encode($satuan, JSON_PRETTY_PRINT) ?>;
    $('#running_text').text("Hi " + "<?php echo ucwords($_SESSION['username']); ?> " + ", Selamat Datang Di Aplikasi Tobat V.1.0 !");
    input_hide();
    $('#view_pos').hide();
    $('#btn_bayar').hide();
    $('#loading').hide();
    $('#btn_cancel_transaksi').hide();
    get_total();
    //$('#nama_obat').select2();

//SHORTCUT KEYBOARD
    document.addEventListener('keydown', function (e) {
        switch (e.keyCode)
        {
            case 112:
                new_transaksi();
                break;
            case 113:
                $('#kode_obat').focus();
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
            case 120:
                //alert('F8'); //F1
                reset_pos();
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

    function input_hide()
    {
        $('#btn_tambah_obat').hide();
        $('#keterangan_obat').slideUp("slow");
    }

    function input_show()
    {
        check_status_konsumen();
        $('#keterangan_obat').fadeIn();
    }

    function reset_pos()
    {
        $('#kode_obat').val("");
        $("#nama_obat").val('');
        $('#satuan_obat').find('option:selected').removeAttr('selected');
        $("#satuan_obat").val(0).change();
        $("#jml_obat").val('1');
        $("#harga_obat").val('');
        $('#kode_obat').focus();
        $('#btn_tambah_obat').slideUp();
    }

    function check_input()
    {
        var kode = $('#kode_obat').val();
        var nama = $('#nama_obat').val();
        var jumlah = $('#jml_obat').val();
        var satuan = $('#satuan_obat').val();
        var stock = $('#stock').val();
        var ret = false;
        var val_obat = search_by(obat, 'nama', nama, 'id_data_obat');
        /*
         console.log(kode);
         console.log(nama);
         console.log(jumlah);
         console.log(satuan);
         console.log(stock);*/
        if (jumlah < 0 || isNaN(jumlah))
        {
            jumlah = 1;
            $('#jml_obat').val(jumlah);
        }

        if (val_obat != '' && !isNaN(parseInt(jumlah)) && jumlah > 0 && satuan != '0' && stock > 0)
        {
            //console.log("masuk slide down");
            $('#btn_tambah_obat').slideDown();
            ret = true;
        }
        else
        {

            //console.log("masuk slide Up");
            if (val_obat == '' && satuan == '0' && isNaN(parseInt(jumlah)) && jumlah <= 0)
            {
                //console.log("masuk");
                input_hide();
            }
            //$('#btn_tambah_obat').slideUp();
        }

        $('#txt_keterangan').text("");

        if (val_obat != '')
        {
            if (parseInt(stock) == 1)
            {
                $('#txt_keterangan').text("Stock Hanya 1 Lagi !");
            }
            else if (stock <= 0)
            {
                $('#txt_keterangan').text("Stock Sudah Habis !");
            }
        }
        return ret;
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
        var satuan_obat = search_by(satuan, 'nama', $('#satuan_' + id).text(), 'id_satuan');
        var jumlah = $('#jml_' + id).val();
        var harga = parseInt($('#harga_' + id).text().replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",.<>\{\}\[\]\\\/]/gi, ''));
        var stock = $('#stock_' + id).text();
        var notifikasi = "";

        var jumlah_satuan_kecil = $('#jumlah_' + id).attr('title');
        var satuan_kecil = $('#satuan_' + id).attr('title');
        var jumlah_jual_satuan_kecil = 0;





        //console.log(stock);
        if (isNaN(jumlah) || parseInt(jumlah) > stock || jumlah == '')
        {
            if (parseInt(jumlah) > stock)
            {
                jumlah = clear_string(stock);
                //console.log(jumlah);
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

        if (satuan_obat == satuan_kecil)
            jumlah_jual_satuan_kecil = jumlah;
        else
            jumlah_jual_satuan_kecil = jumlah * jumlah_satuan_kecil;

        $('#jml_' + id).attr('title', jumlah_jual_satuan_kecil);
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
            if (ar_isi_data[i].search('total_') != -1)
            {
                ar_total[x] = ar_isi_data[i];
                x++;
            }

        }
        //console.log(ar_total);
        $('#total_item').text("Total Item : " + ar_total.length);
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

        $("#grand_total").text(toRp(Math.round(grand_total / 100) * 100));
        check_bayar();
    }

    function get_id_trx()
    {
        var url = "mod/action.php?request=<?php echo $objEnkrip->encode('id_transaksi'); ?>'&jenis='<?php echo $objEnkrip->encode('transaksi'); ?>";
        $.getJSON(url, function (result) {
            $('#id_trx').text(result['id_transaksi']);
        });

    }
    function get_total()
    {
        var url = "mod/action.php?request=<?php echo $objEnkrip->encode('get_grand_total'); ?>";
        $.getJSON(url, function (result) {
            $('#dash_grand_total').text("Total Transaksi Hari Ini : " + result['total']);
        });

    }

    function new_transaksi()
    {

        $('#btn_new_transaksi').hide();
        $('#dashboard').hide();
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

        ac_nama_obat = $.map(obat, function (value, key) {
            return {value: value['nama'], data: value['barcode']};
        });

        /*
         ac_kode_obat = $.map(obat, function (value, key) {
         return {value: value['barcode'], data: value['nama']};
         });

         $('#kode_obat').autocomplete({
         lookup: ac_kode_obat,
         lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
         var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
         return re.test(suggestion.value);
         },
         onSelect: function (suggestion) {

         if ($('#kode_obat').val() != '')
         {
         $("#nama_obat").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'nama')).change();
         var val_obat = search_by(obat, 'nama', $('#nama_obat').val(), 'id_data_obat');
         console.log("Value Obat : " + val_obat);
         get_satuan(val_obat);
         $("#harga_obat").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'harga_jual')).change();
         $("#txt_temp_stock").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'stock_masuk') - search_by(obat, 'barcode', $('#kode_obat').val(), 'stock_keluar'));
         $("#txt_stock").val(Math.floor($("#txt_temp_stock").val() / search_by(obat, 'barcode', $('#kode_obat').val(), 'jumlah_satuan_kecil')));
         $("#stock").val($("#txt_temp_stock").val());

         if ($("#nama_obat").val() != 0)
         input_show();

         }
         else
         {
         input_hide();
         }

         check_input();
         },
         onInvalidateSelection: function () {
         input_hide();
         check_input();
         }
         });
         */
        $('#nama_obat').autocomplete({
            lookup: ac_nama_obat,
            lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                return re.test(suggestion.value);
            },
            onSelect: function (suggestion) {
                var val_obat = search_by(obat, 'nama', $('#nama_obat').val(), 'id_data_obat');
                //console.log('asd');
                if (val_obat != 0)
                {
                    $("#kode_obat").val(search_by(obat, 'id_data_obat', val_obat, 'barcode'));
                    //$("#satuan_obat").val(search_by(obat, 'id_data_obat', val_obat, 'satuan_kecil')).change();
                    get_satuan(val_obat);
                    $("#harga_obat").val(search_by(obat, 'id_data_obat', val_obat, 'harga_jual'));
                    $("#txt_temp_stock").val(search_by(obat, 'id_data_obat', val_obat, 'stock_masuk') - search_by(obat, 'id_data_obat', val_obat, 'stock_keluar'));
                    $("#txt_stock").val(Math.floor($("#txt_temp_stock").val() / search_by(obat, 'id_data_obat', val_obat, 'jumlah_satuan_kecil')));
                    $("#stock").val($("#txt_temp_stock").val());
                    input_show();
                }
                else
                {
                    reset_pos();
                    input_hide();
                }

                check_input();
            },
            onInvalidateSelection: function () {
                input_hide();
                check_input();
            }
        });

        $('#kode_obat').focus();
    }

    function end_transaksi()
    {
        $('#view_pos').slideUp();
        $('#btn_new_transaksi').show();
        $('#btn_cancel_transaksi').hide();
        $('#dashboard').show();
        $('#tgl_trx').val(get_date());
        $('#sts_trx').removeAttr("disabled");
        get_total();
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

    function check_status_konsumen()
    {
        if ($("#harga_obat").val() != '')
        {
            var val_obat = search_by(obat, 'nama', $('#nama_obat').val(), 'id_data_obat');
            harga = parseInt(search_by(obat, 'id_data_obat', val_obat, 'harga_jual'));
            //console.log(harga);
            $('#harga_obat').val(harga + (harga * $('#sts_trx').val() / 100));
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
        return str.replace(/[Rp`~!@#$%^&*()_| +\-=?;:'",<>\{\}\[\]\\\/]/gi, '');
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
        var kode = $('#kode_obat').val();
        var nama = $('#nama_obat').val();
        t = document.getElementById("satuan_obat");
        var id_obat = search_by(obat, 'nama', $('#nama_obat').val(), 'id_data_obat');
        var satuan = t.options[t.selectedIndex].text;
        var jumlah = $('#jml_obat').val();
        var harga = $('#harga_obat').val();
        var stock = $('#stock').val();
        var count = parseInt($('#isi_data tr').length) + 1;
        var jumlah_satuan_kecil = search_by(obat, 'nama', $('#nama_obat').val(), 'jumlah_satuan_kecil');
        var satuan_kecil = search_by(obat, 'nama', $('#nama_obat').val(), 'satuan_kecil');
        var id_barcode = search_barcode(kode);
        var jumlah_jual_satuan_kecil = 0;

        if (satuan == satuan_kecil)
            jumlah_jual_satuan_kecil = jumlah;
        else
            jumlah_jual_satuan_kecil = jumlah * jumlah_satuan_kecil;
        //console.log(id_barcode);
        //console.log();
        var check_nama = search_by(obat, 'nama', nama, 'id_data_obat');
        var check_kode = search_by(obat, 'barcode', kode, 'id_data_obat');

        if (check_nama == check_kode && check_nama != '' && check_nama != '')
        {
            if (stock > 0)
            {
                if (!id_barcode)
                {
                    $('#isi_data').append(
                            '<tr id="row_' + count + '"><td style = "width: 40px; vertical-align:middle;" class = "text-center"> <button onclick="remove_row(' + count + ')"> <i class = "fa fa-trash-o"> </i></button> </td>' +
                            '<td id="stock_' + count + '" type="hidden" style="display:none;"> ' + stock + ' </td>' +
                            '<td id="id_' + count + '" type="hidden" style="display:none;"> ' + id_obat + ' </td>' +
                            '<td id="nama_' + count + '" style = "width: 300px; font-size:13px; vertical-align:middle;"> ' + nama + ' </td>' +
                            '<td id="kode_' + count + '" style = "width: 150px; font-size:13px; vertical-align:middle;"> ' + kode + ' </td>' +
                            '<td id="jumlah_' + count + '" style = "width: 50px; font-size:13px;" class = "text-center" title="' + jumlah_satuan_kecil + '"><input max="' + stock + '" id="jml_' + count + '" style="width:50px;" type="number" min="1" value="' + jumlah + '" onchange="ubah_jumlah(' + count + ')" onkeyup="ubah_jumlah(' + count + ')" title="' + jumlah_jual_satuan_kecil + '"></td>' +
                            '<td id="satuan_' + count + '" style = "width: 80px; font-size:13px; vertical-align:middle;" title="' + satuan_kecil + '"> ' + satuan + ' </td>' +
                            '<td id="harga_' + count + '" style = "width: 120px; font-size:15px; vertical-align:middle;"> ' + toRp(harga) + ' </td>' +
                            '<td class="total" id="total_' + count + '" style = "width: 120px; font-size:15px; vertical-align:middle;">' + toRp((parseInt(jumlah) * parseInt(harga))) + '</td></tr>'
                            );
                }
                else
                {
                    var id_bar = new Array();
                    id_bar = id_barcode.split('_');
                    add_jumlah(id_bar[1], jumlah);
                }
                get_grand_total();
                input_hide();
                reset_pos();
            }
            else
            {
                set_notifikasi('Stock Sudah Habis !');
            }
        }
        else
        {
            set_notifikasi("Nama atau Kode Obat Tidak Ada !");
        }

    }

    function detect_enter(ev, textbox)
    {
        //console.log(ev);
        switch (ev.keyCode)
        {
            case 13 :
                input_show();
                add_obat();

                break;
            default :
                //console.log(ev.keyCode);
                break;
        }
        return true;
    }

//FUNGSI EVENT
    $(function () {

        $('#total_bayar').on("keyup change", function () {
            check_bayar();
        });

        $('#satuan_obat').on("change", function () {
            var satuan = $('#satuan_obat').val();
            if (satuan != '')
            {
                $('#harga_obat').val(satuan);

                if ($('#stock').val() == $('#txt_stock').val())
                {
                    $('#stock').val($('#txt_temp_stock').val());
                }
                else
                {
                    $('#stock').val($('#txt_stock').val());
                }

                if (parseInt($('#jml_obat').val()) > parseInt($('#stock').val()))
                {
                    $('#jml_obat').val('1');
                }

            }
            check_input();
        });



        $('#kode_obat').on("keyup", function () {

            if ($('#kode_obat').val() != '')
            {
                $("#nama_obat").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'nama'));
                var val_obat = search_by(obat, 'barcode', $('#kode_obat').val(), 'id_data_obat');
                //$("#satuan_obat").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'satuan_kecil'));
                get_satuan(val_obat);
                $("#harga_obat").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'harga_jual'));
                $("#txt_temp_stock").val(search_by(obat, 'barcode', $('#kode_obat').val(), 'stock_masuk') - search_by(obat, 'barcode', $('#kode_obat').val(), 'stock_keluar'));
                $("#txt_stock").val(Math.floor($("#txt_temp_stock").val() / search_by(obat, 'barcode', $('#kode_obat').val(), 'jumlah_satuan_kecil')));
                $("#stock").val($("#txt_temp_stock").val());
                /*
                 if ($("#nama_obat").val() != 0)
                 input_show();*/

            }
            else
            {
                input_hide();
            }

            check_input();
            //var kode_obat = $('#kode_obat').val();
            //if (kode_obat.substring(kode_obat.length - 1) == "\n")
            //console.log("ok");
        });

        $('#nama_obat').on("click", function () {
            check_input();
        });

        $('#jml_obat').on("change keyup", function () {
            var jumlah = $('#jml_obat').val();
            var stock = $('#stock').val();
            //console.log(jumlah);

            if (!isNaN(jumlah))
            {
                var sisa_stock = stock - jumlah;
                if (sisa_stock <= 0)
                {
                    //console.log('Stock Habis');
                    $('#jml_obat').val(stock);
                }
            }
            else
            {
                //console.log("masuk");
                $('#jml_obat').val('1');
            }

        });

        $('#btn_bayar').on("click", function () {

            get_grand_total();
            var struk = "";

            var ar_isi_data = $("#isi_data").getIdArray();
            var ar_total = new Array();
            var temp = new Array();
            var x = 0, i;
            for (i = 0; i < ar_isi_data.length; i++)
            {
                //console.log(ar_isi_data[i].search('total_'));
                if (ar_isi_data[i].search('total_') != -1)
                {
                    temp = ar_isi_data[i].split('_');
                    ar_total[x] = temp[1];

                    x++;
                }

            }

            //console.log(ar_total);
            struk += "ID trx : " + $('#id_trx').text() + "\n";
            struk += "------------------------------\n";
            for (i = 0; i < ar_total.length; i++)
            {
                struk += $('#nama_' + ar_total[i]).text().substring(0, 30) + '\n';
                struk += '\tx' + $('#jml_' + ar_total[i]).val() + $('#satuan_' + ar_total[i]).text() + '\t@' + $('#harga_' + ar_total[i]).text() + '\t' + $('#total_' + ar_total[i]).text() + '\n';
            }

            struk += "------------------------------\n";
            struk += "Total\t\t\t" + $('#grand_total').text() + "\n";
            struk += "Bayar\t\t\t" + toRp($('#total_bayar').val()) + "\n";
            struk += "Kembali\t\t\t" + $('#kembali_uang').text() + "\n";
            $('#txt_print_preview').val(struk);
            $('#btn_print').focus();
            $('#mod_print_preview').modal("show");

        });

        $('#btn_print').on("click", function () {
            var struk = "";

            var ar_isi_data = $("#isi_data").getIdArray();
            var ar_total = new Array();
            var temp = new Array();
            var x = 0, i;
            for (i = 0; i < ar_isi_data.length; i++)
            {
                //console.log(ar_isi_data[i].search('total_'));
                if (ar_isi_data[i].search('total_') != -1)
                {
                    temp = ar_isi_data[i].split('_');
                    ar_total[x] = temp[1];

                    x++;
                }

            }

            //console.log(ar_total);

            var t = document.getElementById("sts_trx");
            var pembeli = t.options[t.selectedIndex].text;

            //console.log(pembeli);
            if (pembeli == "Umum")
                pembeli = '1';
            else
                pembeli = '2';

            struk += $('#id_trx').text() + ";";
            struk += pembeli + ";";
            struk += x + ";";
            struk += "<TONX>";
            for (i = 0; i < ar_total.length; i++)
            {
                struk += $('#nama_' + ar_total[i]).text().substring(0, 30) + ',';
                struk += $('#jml_' + ar_total[i]).val() + ',' + $('#satuan_' + ar_total[i]).text() + ',' + $('#harga_' + ar_total[i]).text() + ',' + $('#total_' + ar_total[i]).text() + ',' + $('#id_' + ar_total[i]).text() + ';';
            }
            struk += "<TONX>";
            struk += $('#grand_total').text() + ";";
            struk += toRp($('#total_bayar').val()) + ";";
            struk += $('#kembali_uang').text() + ";";
            struk = struk.trim();

            $('#loading').show();
            $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('transaksi'); ?>", data: struk})
                    .done(function (result) {
                        console.log("Status : " + result);

                    });
            end_transaksi();

            $('#loading').hide();
            $('#mod_print_preview').modal("hide");

        });

        $('#btn_new_transaksi').on("click", function () {
            new_transaksi();
        });

        $('#btn_cancel_transaksi').on("click", function () {
            end_transaksi();
        });

        $('#btn_tambah_obat').on("click", function () {
            add_obat();
        });
    });


//FUNGSI STANDARD
    function updateClock( )
    {
        var currentTime = new Date( );
        var currentHours = currentTime.getHours( );
        var currentMinutes = currentTime.getMinutes( );
        var currentSeconds = currentTime.getSeconds( );

        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
        currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

        var timeOfDay = (currentHours < 12) ? "AM" : "PM";

        currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

        currentHours = (currentHours == 0) ? 12 : currentHours;

        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


        $("#datetime").text(currentTimeString);

    }

    $(document).ready(function ()
    {
        setInterval('updateClock()', 1000);
    });

</script>
