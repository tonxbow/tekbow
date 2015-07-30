<?php
session_start();
include 'setting.php';
require_once ('../class/database_class.php');
require_once ('../class/printer_class.php');
require_once ('../class/function_class.php');
require_once ('../class/enkripsi_class.php');

$db = new db($dbserver, $dbuser, $dbpass, $dbname);
$objFunction = new myfunction();
$objEnkrip = new Encryption();
$printer = new printer();

$request = $objEnkrip->decode($_REQUEST['request']);
$satuan = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');

switch ($request) {
    case 'data_obat' :
        $tbl = 'data_obat';
        $fldSelect = "*";
        $criteriaField = 'id_data_obat';

//Header Tabel
        $dataKolom = array('Nama', 'Kode', 'Sat. Besar', 'Sat. Kecil', 'Isi Sat.', 'Harga Beli', 'Harga Dasar', 'Obat In', 'Obat Out', 'Stock', 'Tgl Masuk', 'Action'); //Judul Kolom
        $dataField = array('nama', 'barcode', 'satuan_besar', 'satuan_kecil', 'jumlah_satuan_kecil', 'harga_dasar', 'harga_jual', 'stock_masuk', 'stock_keluar', 'stock', 'tanggal_terakhir_masuk');
//field primary key
        ?>
        <table class = "table table-striped table-hover table-bordered display" id = "table-data" cellspacing = "0" style = "font-size: 12px;color: #000;">
            <thead>
                <tr>
                    <?php
                    echo '<th class="text-center">No</th>';
                    for ($i = 0; $i < count($dataKolom); $i++) {
                        echo '<th class="text-center">' . $dataKolom[$i] . '</th>';
                    }
                    ?>
                </tr>
            </thead>

            <tbody id="tbl_body">
                <?php
                //
                $crt = '';
                $user = $db->get_data($db, 'user', '*', '', '', '');
                $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'nama ASC', '');
                $grand_total = 0;

                for ($i = 0; $i < count($ArrayDt); $i++) {
                    $no = $i + 1;
                    //edit module
                    echo '<tr>';
                    echo '<td width="40px" class="text-center">' . $no . '</td>';
                    for ($x = 0; $x < count($dataField); $x++) {


                        switch ($dataField[$x]) {
                            case 'harga_dasar' :
                                echo '<td class="text-right"  id="txt_harga_dasar_' . $i . '">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                break;
                            case 'harga_jual' : echo '<td class="text-right" id="txt_harga_jual_' . $i . '">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                break;
                            case 'nama' : echo '<td class="text-left" id="txt_nama_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'satuan_kecil' :
                                $satuan_kecil = $objFunction->search_by($satuan, 'id_satuan', $ArrayDt[$i][$dataField[$x]], 'nama');
                                echo '<td class="text-center" id="txt_satuan_kecil_' . $i . '">' . $satuan_kecil . '</td>';
                                break;
                            case 'satuan_besar' :
                                echo '<td class="text-center" id="txt_satuan_besar_' . $i . '">' . $objFunction->search_by($satuan, 'id_satuan', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                                break;
                            case 'jumlah_satuan_kecil' :
                                echo '<td class="text-center" id="txt_jumlah_satuan_kecil_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'barcode' :
                                echo '<td class="text-center" id="txt_barcode_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'stock' :
                                $stock = ($ArrayDt[$i]['stock_masuk'] - $ArrayDt[$i]['stock_keluar']); //. ' ' . $satuan_kecil;
                                echo '<td class="text-center">' . $stock . '</td>';
                                break;
                            default:
                                echo '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                        }
                    }

                    echo '<td style="whitespace:nowrap;" class="text-center">'
                    . '<button title="Edit Data" id="btn_edit_' . $i . '" onClick="edit_click(' . $i . ')"><i class="fa fa-pencil"></i></button>'
                    . '<button title="Hapus Data" id = "btn_del_' . $i . '" onClick = "del_click(' . $i . ')"><i class = "fa fa-trash"></i></button>
                    </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
        <div id="dialog-confirm"></div>
        <script>
            function del_click(id) {
                var txt;
                var r = confirm("Anda Yakin Akan Menghapus Data Ini ? ");
                if (r == true) {
                    var action = "<?php echo $objEnkrip->encode('delete_data_obat'); ?>";
                    var data_send = search_by(obat, 'barcode', $('#txt_barcode_' + id).text(), 'id_data_obat');
                    $.post("mod/action.php", {request: action, data: data_send})
                            .done(function (result) {
                                if (result == "fail")
                                    alert("Hapus Data Gagal, Keterangan : \n\n " + result);
                                else
                                {
                                    get_content();
                                    alert("Hapus Data Obat Berhasil !");
                                }
                            });
                }

            }

            function edit_click(id)
            {

                var nama = $('#txt_nama_' + id).text();
                var kode = $('#txt_barcode_' + id).text();

                var satuan_besar = search_by(satuan, 'nama', $('#txt_satuan_besar_' + id).text(), 'id_satuan');
                var satuan_kecil = search_by(satuan, 'nama', $('#txt_satuan_kecil_' + id).text(), 'id_satuan');
                var jumlah_satuan_kecil = $('#txt_jumlah_satuan_kecil_' + id).text();
                var harga_dasar = clear_string($('#txt_harga_dasar_' + id).text());
                var harga_jual = clear_string($('#txt_harga_jual_' + id).text());
                var id_obat = search_by(obat, 'barcode', kode, 'id_data_obat');
                $('#tb_new_id_obat').text(id_obat);
                $('#tb_new_kode_obat').val(kode);
                $('#tb_new_nama_obat').val(nama);
                //$('#cb_new_satuan_besar_obat').find('option:selected').removeAttr('selected');
                $('#cb_new_satuan_besar_obat').val(satuan_besar).change();
                $('#tb_new_jumlah_satuan').val(jumlah_satuan_kecil);
                $('#cb_new_satuan_kecil_obat').val(satuan_kecil).change();
                $('#tb_new_harga_beli').val(harga_dasar);
                $('#tb_new_harga_jual').val(harga_jual);

                $('#txt_act').text("edit");
                $('#txt_judul_modal').text("Ubah Data Obat");
                $('#mod_add_data').modal("show");
                //console.log(nama + ',' + kode + ',' + satuan_besar + ',' + satuan_kecil + ',' + jumlah_satuan_kecil + ',' + harga_dasar + ',' + harga_jual + ',' + id_obat);
            }
            var tables = $('#table-data').DataTable(
                    {
                        "iDisplayLength": 10,
                        "aLengthMenu": [
                            [10, 50, 100, -1],
                            [10, 50, 100, "All"] // change per page values here
                        ],
                        "scrollCollapse": true,
                        "paging": true,
                        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                    }
            );

        </script>
        <?php
        break;
    case 'data_user' :
        $tbl = 'user';
        $fldSelect = "*";

        $dataKolom = array('User ID', 'Username', 'Nama', 'Role', 'Last Login', 'Action'); //Judul Kolom
        $dataField = array('id_user', 'username', 'nama', 'role', 'last_login');
        ?>
        <table class = "table table-striped table-hover table-bordered display" id = "table-data" cellspacing = "0" style = "font-size: 12px;color: #000;">
            <thead>
                <tr>
                    <?php
                    echo '<th class="text-center">No</th>';
                    for ($i = 0; $i < count($dataKolom); $i++) {
                        echo '<th class="text-center">' . $dataKolom[$i] . '</th>';
                    }
                    ?>
                </tr>
            </thead>

            <tbody id="tbl_body">
                <?php
                //
                $crt = '';
                $user = $db->get_data($db, 'user', '*', '', '', '');
                $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'nama ASC', '');
                $grand_total = 0;

                for ($i = 0; $i < count($ArrayDt); $i++) {
                    $no = $i + 1;
                    //edit module
                    echo '<tr>';
                    echo '<td width="40px" class="text-center">' . $no . '</td>';
                    for ($x = 0; $x < count($dataField); $x++) {


                        switch ($dataField[$x]) {
                            case 'role' :
                                if ($ArrayDt[$i][$dataField[$x]] == '1')
                                    $role = "Administrator";
                                else
                                    $role = "Kasir";
                                echo '<td class="text-center"  id="txt_role_' . $i . '">' . $role . '</td>';
                                break;
                            case 'id_user' :
                                echo '<td class="text-center"  id="txt_id_user_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'username' :
                                echo '<td class="text-center"  id="txt_username_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'nama' :
                                echo '<td class="text-center"  id="txt_nama_' . $i . '">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;

                            default:
                                echo '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                        }
                    }

                    echo '<td style="whitespace:nowrap;" class="text-center">'
                    . '<button title="Edit Data" id="btn_edit_' . $i . '" onClick="edit_click(' . $i . ')"><i class="fa fa-pencil"></i></button>'
                    . '<button title="Hapus Data" id = "btn_del_' . $i . '" onClick = "del_click(' . $i . ')"><i class = "fa fa-trash"></i></button>
                    </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
        <div id="dialog-confirm"></div>
        <script>
            function del_click(id) {
                var txt;
                var r = confirm("Anda Yakin Akan Menghapus Data Ini ? ");
                if (r == true) {
                    var action = "<?php echo $objEnkrip->encode('delete_data_user'); ?>";
                    var data_send = $('#txt_id_user_' + id).text();
                    $.post("mod/action.php", {request: action, data: data_send})
                            .done(function (result) {
                                if (result == "fail")
                                    alert("Hapus Data Gagal, Keterangan : \n\n " + result);
                                else
                                {
                                    get_content();
                                    alert("Hapus Data Berhasil !");
                                }
                            });
                }

            }

            function edit_click(id)
            {
                var isi_id_user = $('#txt_id_user_' + id).text();
                var isi_username = $('#txt_username_' + id).text();
                var isi_nama = $('#txt_nama_' + id).text();
                var isi_role = $('#txt_role_' + id).text();
                if (isi_role == "Administrator")
                    isi_role = "1";
                else
                    isi_role = "2";

                $('#tb_username').val(isi_username);
                $('#tb_nama_user').val(isi_nama);
                $('#cb_role').val(isi_role);
                $('#tb_id').text(isi_id_user);
                $('#tb_password').val('');
                $('#tb_re_password').val('');
                $('#txt_act').text("edit");
                $('#txt_judul_modal').text("Ubah Data");
                $('#mod_add_data').modal("show");
                //console.log(nama + ',' + kode + ',' + satuan_besar + ',' + satuan_kecil + ',' + jumlah_satuan_kecil + ',' + harga_dasar + ',' + harga_jual + ',' + id_obat);
            }
            var tables = $('#table-data').DataTable(
                    {
                        "iDisplayLength": 10,
                        "aLengthMenu": [
                            [10, 50, 100, -1],
                            [10, 50, 100, "All"] // change per page values here
                        ],
                        "scrollCollapse": true,
                        "paging": true,
                        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                    }
            );

        </script>
        <?php
        break;
    case 'detail_transaksi':
        $obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
        $id = $objEnkrip->decode($_REQUEST['id_transaksi']);
        $dataKolom = array('Obat', 'Jumlah', 'Satuan', 'Harga'); //Judul Kolom
        $dataField = array('id_data_obat', 'jumlah', 'satuan', 'harga_jual');
        ?>
        <table class = "table table-striped table-hover table-bordered display" cellspacing = "0" style = "font-size: 12px;color: #000;">
            <thead>
                <tr>
                    <?php
                    echo '<th class="text-center">No</th>';
                    for ($i = 0; $i < count($dataKolom); $i++) {
                        echo '<th class="text-center">' . $dataKolom[$i] . '</th>';
                    }
                    ?>
                </tr>
            </thead>

            <tbody id="tbl_body">
                <?php
                //
                $crt = 'id_transaksi = "' . $id . '"';
                $ArrayDt = $db->get_data($db, 'transaksi_detail', '*', $crt, '', '');

                for ($i = 0; $i < count($ArrayDt); $i++) {
                    $no = $i + 1;
                    //edit module
                    echo '<tr>';
                    echo '<td width="40px" class="text-center">' . $no . '</td>';
                    for ($x = 0; $x < count($dataField); $x++) {

                        switch ($dataField[$x]) {
                            case 'id_data_obat' :
                                echo '<td class="text-left" >' . $objFunction->search_by($obat, 'id_data_obat', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                                break;
                            case 'harga_jual' : echo '<td class="text-right" >' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                break;
                            case 'jumlah' : echo '<td class="text-center" >' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                            case 'satuan' :
                                echo '<td class="text-center" >' . $objFunction->search_by($satuan, 'id_satuan', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                                break;
                            default:
                                echo '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                break;
                        }
                    }

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        break;
}
?>

