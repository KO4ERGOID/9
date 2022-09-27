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

$iterator = CIBlockElement::GetPropertyValues(6, array('ACTIVE' => 'Y'), true,);
?>
<h4>Выбрать текущего сотрудника</h4>
<form action='' method='GET'>
    <select name='SelectStaff'>
        <?
        while ($row = $iterator->Fetch()) {
            $i++; ?>
            <option name='<? echo $i; ?>'><? echo $row['32'] ?></option><br>
        <? } ?>
    </select>
    <br>Забронировать машину c: <br><input type='text' name='ot'>
    <br>До:<br><input type='text' name='do'>
    <input type='submit' value='Бронь'>
</form>
<?
$ot = $_GET['ot'];
$do = $_GET['do'];
$hours1 = hours($ot, $do);

$arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = array("IBLOCK_ID" => 6, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize" => 50), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if ($_GET['SelectStaff'] == $arFields['NAME']) {
        $StaffID = $arFields['ID'];
    }
}
if ($ot >= 0 && $ot <= 23 && $do >= 0 && $do <= 23) {
    $iterator = CIBlockElement::GetPropertyValues(6, array('ACTIVE' => 'Y'), true, array('ID' => array(31, 32)));
    while ($row = $iterator->Fetch()) {
        if ($row['IBLOCK_ELEMENT_ID'] == $StaffID) {
            $iterator2 = CIBlockElement::GetPropertyValues(5, array('ACTIVE' => 'Y'), true, array('ID' => array(28, 29, 30, 35, 36)));
            while ($car = $iterator2->Fetch()) {
                $MatchCounter = 0;
                if ($row[31] == $car[28]) {
                    $ot1 = $car[35];
                    $do1 = $car[36];
                    $hours2 = hours($ot1, $do1);

                    print_r('<br> Категория авто: ' . SearchElementByID($car[28]) . '<br>');
                    print_r('Наименование: ' . $car[29] . '<br>');
                    print_r('Водитель: ' . SearchElementByID($car[30]) . '<br>');

                    foreach ($hours1 as $value1) {
                        foreach ($hours2 as $value2) {
                            if ($value1 == $value2) {
                                $MatchCounter++;
                            }
                        }
                    }
                    if ($ot !== '') {
                        if ($MatchCounter > 1) {
                            echo 'Машина занята в это время <br>';
                        } else {
                            echo 'Машина свободна в это время <br>';
                        }
                    }
                }
            }
        }
    }
} else {
    echo 'Введите корректное время';
}
