<?php
date_default_timezone_set("Asia/Jakarta");
function Soap_request($String_Choice, $Choice_soap)
{
    $Key = "0";
    $id = "";
    $fn = "";
    $name = "";

    if ($String_Choice == "log_absen") {
        switch ($Choice_soap) {
                // download log data
            case 1:
                $Soap_request = "<GetAttLog> 
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN xsi:type=\"xsd:integer\">All</PIN>
                                            </Arg>
                                        </GetAttLog>";
                break;
                // clear log data
            case 2:
                $Soap_request = "<ClearData>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <Value xsi:type=\"xsd:integer\">3</Value>
                                            </Arg>
                                        </ClearData>";
                break;
            default:
                $Soap_request = "";
                break;
        }
    }
    if ($String_Choice == "sidik_jari") {
        switch ($Choice_soap) {
                // download sidik jari
            case 1:
                $Soap_request = "<GetUserTemplate>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN xsi:type=\"xsd:integer\">" . $id . "</PIN>
                                                <FingerID xsi:type=\"xsd:integer\">" . $fn . "</FingerID>
                                            </Arg>
                                        </GetUserTemplate>";
                break;
                // upload sidik jari
            case 2:
                $Soap_request = "<SetUserTemplate>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN xsi:type=\"xsd:integer\">" . $id . "</PIN>
                                                <FingerID xsi:type=\"xsd:integer\">" . $fn . "</FingerID>
                                                <Size>" . strlen($temp) . "</Size>
                                                <Valid>1</Valid>
                                                <Template>" . $temp . "</Template>
                                            </Arg>
                                        </SetUserTemplate>";
                break;
                // hapus sidik jari
            case 3:
                $Soap_request = "<DeleteTemplate>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN xsi:type=\"xsd:integer\">" . $id . "</PIN>
                                            </Arg>
                                        </DeleteTemplate>";
                break;
            default:
                $Soap_request = "";
                break;
        }
    }

    if ($String_Choice == "user_data") {
        switch ($Choice_soap) {
                // Upload nama
            case 1:
                $Soap_request = "<SetUserInfo>
                                            <ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN>" . $id . "</PIN>
                                                <Name>" . $name . "</Name>
                                            </Arg>
                                        </SetUserInfo>";
                break;
                // Delete user
            case 2:
                $Soap_request = "<DeleteUser>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <PIN xsi:type=\"xsd:integer\">" . $id . "</PIN>
                                            </Arg>
                                        </DeleteUser>";
                break;
            default:
                $Soap_request = "";
                break;
        }
    }

    if ($String_Choice == "device") {
        switch ($Choice_soap) {
                // restart device
            case 1:
                $Soap_request = "<Restart>
                                            <ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                        </Restart>";
                break;
                //sync time
            case 2:
                $Soap_request = "<SetDate>
                                            <ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey>
                                            <Arg>
                                                <Date xsi:type=\"xsd:string\">" . date("Y-m-d") . "</Date>
                                                <Time xsi:type=\"xsd:string\">" . date("H:i:s") . "</Time>
                                            </Arg>
                                        </SetDate>";
                break;
            default:
                $Soap_request = "";
                break;
        }
    }

    return $Soap_request;
}

function Parse_Data($data, $p1, $p2)
{
    $data = " " . $data . " ";
    $hasil = "";

    $awal = strpos($data, $p1);
    // mencari p1 didalam string data
    if ($awal != "") {
        $akhir = strpos(strstr($data, $p1), $p2);
        if ($akhir != "") {
            $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
        }
    }

    return $hasil;
}
