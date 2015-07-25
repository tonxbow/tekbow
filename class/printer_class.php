<?php

class printer {

    public function connect($port, $baudrate, $parity, $data, $stop, $to, $dtr, $rts) {

    }

    public function PrintLogo() {
        return chr(28) . chr(112) . chr(1) . chr(1);
    }

    public function AlignCenter() {
        return chr(27) . chr(97) . chr(49);
    }

    public function AlignLeft() {
        return chr(27) . chr(97) . chr(48);
    }

    public function AlignRight() {
        return chr(27) . chr(97) . chr(50);
    }

    public function BigText() {
        return chr(27) . chr(33) . chr(48);
    }

    public function ScaleText() {
        return chr(29) . chr(33) . chr(68);
    }

    public function PrintEnter() {
        return chr(13) . chr(10);
    }

    public function TextNormal() {
        return chr(27) . chr(33) . chr(00);
    }

    public function CutPaper() {
        return chr(29) . chr(86) . chr(48);
    }

    public function PrintBar() {
        return "__________________________________________\r\n";
    }

    public function PrintHeader() {
        $header = "";
        $header .= self::AlignCenter();
        $header .= self::BigText();
        $header .= "TOKO FIRDAUS";
        $header .= self::PrintEnter();
        $header .= self::TextNormal();
        $header .= self::AlignCenter();
        $header .= "Jln.Padalarang No.80-82, Bandung\r\n";
        $header .= "Telp: 022-7656000\r\n";
        $header .= self::PrintBar();
        $header .= self::TextNormal();
        $header .= self::PrintEnter();
        $header .= self::AlignLeft();

        return $header;
    }

    public function print_data($port, $data) {
        $port = strtolower($port);
        exec("mode $port: BAUD=9600 PARITY=n DATA=8 STOP=1 to=off dtr=off rts=off", $response);
        //var_dump($response);
        //exit();
        //exec("mode '$port': BAUD=9600 PARITY=n DATA=8 STOP=1 to=off dtr=off rts=off");
        $fp = fopen($port, "w");
        //$fp = fopen('/dev/ttyUSB0','r+'); //use this for Linux
        fwrite($fp, $data); //write string to serial
        fclose($fp);
    }

}

?>
