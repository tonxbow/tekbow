<?php
//------------------------------------VARIALBLE
$username = $_SESSION['username'];
//aan
?>

<?php
$menu = array('POS', 'Obat Masuk', 'Data Obat', 'Report', 'Data Referensi');
$authmenu = array(
    array('1', '2'), //POS
    array('1', '2'), //Obat Masuk
    array('2'),
    array('1', '2'), //Report
    array('1'), //Data Referensi
);
$submenu = array(
    array(), //POS
    array(), //Obat Masuk
    array(), //Data Obat
    array('Report Penjualan', 'Report Obat'), //Report
    array('Data Obat', 'User', 'Setting'), //Data Refernesi
);

$indexActiveMenu = -1;

for ($x = 0; $x < count($menu); $x++) {
    for ($y = 0; $y < count($submenu[$x]); $y++) {
        if ($reqMenu == createFileName($submenu[$x][$y]))
            $indexActiveMenu = $x;
    }
}

function createFileName($filename) {
    $filename = strtolower($filename);
    $filename = str_replace(' ', '_', $filename);
    $filename = 'dt' . $filename . '.php';
    return $filename;
}
?>
<!--
<script type="text/javascript">
    $(function () {
        $(document).on("click", ".icon-notification-nonactive", function () {
            $(this).removeClass("icon-notification-nonactive").addClass("icon-notification-active");

            $(".box-notification").slideDown();
            $(".cover").show();
            loadNotification();
        });

        $(document).on("click", ".icon-notification-active", function () {
            $(this).removeClass("icon-notification-active").addClass("icon-notification-nonactive");

            $(".box-notification").slideUp();
            $(".cover").hide();
        });

        $(document).on("click", ".cover", function () {
            $(".icon-notification-active").removeClass("icon-notification-active").addClass("icon-notification-nonactive");

            $(".box-notification").slideUp();
            $(".cover").hide();
        });

        setInterval(function () {
            $.ajax({
                type: "GET",
                url: "index.php",
                success: function (data) {
                    $(".load-notification").empty().append($(data).find(".load-notification").html());
                },
                error: function (data) {

                }
            });
        }, 5000);
    });

    function loadNotification() {
        $.ajax({
            type: "GET",
            url: "mod/notification.php",
            success: function (data) {
                $(".box-notification ul").empty().append(data);
            },
            error: function (data) {

            }
        });
    }
</script>
-->
<header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand" style="width:180px; background-color: #aaa;">
        <a class="logo">
            <img style="height:50px;" alt="brand" src="images/brand.png" alt="" onclick="location.reload()">
        </a>

    </div>
    <!--logo end-->
    <div class="horizontal-menu">
        <ul class="nav navbar-nav">
            <?php
            for ($i = 0; $i < count($menu); $i++) {
                if (in_array($_SESSION['role'], $authmenu[$i])) {
                    if (count($submenu[$i]) < 1) {
                        echo '<li><a href="index.php?ask=' . $objEnkrip->encode(createFileName($menu[$i])) . '">' . $menu[$i] . '</a>';
                    } else {
                        echo '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">' . $menu[$i] . ' <b class=" fa fa-angle-down"></b></a>';
                        echo '<ul class="dropdown-menu">';
                        for ($j = 0; $j < count($submenu[$i]); $j++) {
                            echo '<li><a href="index.php?ask=' . $objEnkrip->encode(createFileName($submenu[$i][$j])) . '">' . $submenu[$i][$j] . '</a></li>';
                        }
                        echo '</ul></li>';
                    }
                }
            }
            ?>
        </ul>

    </div>

    <div class="top-nav clearfix">
        <ul class="nav pull-right top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#"  style="background-color: #eee;">
                    <span class="username" style="margin: 0 0 20px 20px; color: #000;"><?php echo $username; ?></span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li><a href="index.php?ask=<?php echo $objEnkrip->encode('dtubah_password.php'); ?>"><i class=" fa fa-suitcase"></i> Ubah Password</li>
                    <li><a href="login.php?ask=logout"><i class="fa fa-key"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>



