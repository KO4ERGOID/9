<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
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

$StaffID = SearchStaffId($_GET['SelectStaff']);
if ($ot >= 0 && $ot <= 23 && $do >= 0 && $do <= 23) {
    SearchCar($StaffID, $ot, $do);
    $arComponent = SearchCar($StaffID, $ot, $do);
    $i = 0;
    while ($i < $arComponent['kolvo']) {
        $i++;
        print_r('<br> Категория авто: ' . SearchElementByID($arComponent['kategory' . $i]) . '<br>');
        print_r('Наименование: ' . $arComponent['name' . $i] . '<br>');
        print_r('Водитель: ' . SearchElementByID($arComponent['driver' . $i]) . '<br>');
        if ($arComponent['MatchCounter' . $i] > 1) {
            echo 'Машина занята в это время <br>';
        } else {
            echo 'Машина свободна в это время <br>';
        }
    }
} else {
    echo 'Введите корректное время';
}
