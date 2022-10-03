<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
function hours($a, $b)
{
    $i = 1;
    $arr = array(
        $i => $a,
    );
    if ($b < $a) {
        $b = $b + 24;
        while ($b > $a) {
            if ($a >= 23) {
                $i++;
                $a++;
                $arr[$i] = $a - 24;
            } else {
                $i++;
                $a++;
                $arr[$i] = $a;
            }
        }
    } else {
        while ($b > $a) {
            if ($a >= 23) {
                $i++;
                $a++;
                $arr[$i] = $a - 24;
            } else {
                $i++;
                $a++;
                $arr[$i] = $a;
            }
        }
    }
    return $arr;
}

function SearchElementByID($id)
{
    $res = CIBlockElement::GetByID($id);
    if ($ar_res = $res->GetNext()) {
        return $ar_res['NAME'];
    }
}

function SearchStaffId($SelectStaff)
{
    $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
    $arFilter = array("IBLOCK_ID" => 6, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize" => 50), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        if ($SelectStaff == $arFields['NAME']) {
            $StaffID = $arFields['ID'];
        }
    }
    return $StaffID;
}

function SearchCar($StaffID, $ot, $do)
{
    $chet = 0;
    $hours1 = hours($ot, $do);
    $iterator2 = CIBlockElement::GetPropertyValues(6, array('ACTIVE' => 'Y'), true, array('ID' => array(31, 32)));
    while ($row = $iterator2->Fetch()) {
        if ($row['IBLOCK_ELEMENT_ID'] == $StaffID) {
            $iterator3 = CIBlockElement::GetPropertyValues(5, array('ACTIVE' => 'Y'), true, array('ID' => array(28, 29, 30, 35, 36)));
            while ($car = $iterator3->Fetch()) {
                if ($row[31] == $car[28]) {
                    $chet++;
                    $ot1 = $car[35];
                    $do1 = $car[36];
                    $hours2 = hours($ot1, $do1);
                    $MatchCounter = 0;
                    $arComponent['kategory' . $chet] = $car[28];
                    $arComponent['name' . $chet] = $car[29];
                    $arComponent['driver' . $chet] = $car[30];
                    $arComponent['ot' . $chet] = $car[35];
                    $arComponent['do' . $chet] = $car[36];
                    $arComponent['kolvo'] = $chet;
                    foreach ($hours1 as $value1) {
                        foreach ($hours2 as $value2) {
                            if ($value1 == $value2) {
                                $MatchCounter++;
                                $arComponent['MatchCounter' . $chet] = $MatchCounter;
                            }
                        }
                    }
                }
            }
        }
    }
    return $arComponent;
}

$this->IncludeComponentTemplate();
