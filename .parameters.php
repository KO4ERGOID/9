<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("iblock"))
	return;

$arTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks = array();
$db_iblock = CIBlock::GetList(array("SORT" => "ASC"), array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while ($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];

$arProperty_LNS = array();
if (count($arCurrentValues["IBLOCKS"]) == 1) {
	$rsProp = CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"), array("ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues["IBLOCKS"][0]));
	while ($arr = $rsProp->Fetch()) {
		$arProperty[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
		if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S"))) {
			$arProperty_LNS[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
		}
	}
}

$arSorts = array(
	"ASC" => GetMessage("T_IBLOCK_DESC_ASC"),
	"DESC" => GetMessage("T_IBLOCK_DESC_DESC"),
);

$arSortFields = array(
	"ID" => GetMessage("T_IBLOCK_DESC_FID"),
	"NAME" => GetMessage("T_IBLOCK_DESC_FNAME"),
	"ACTIVE_FROM" => GetMessage("T_IBLOCK_DESC_FACT"),
	"SORT" => GetMessage("T_IBLOCK_DESC_FSORT"),
	"TIMESTAMP_X" => GetMessage("T_IBLOCK_DESC_FTSAMP")
);

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => 'Тип инфоблока',
			"TYPE" => "LIST",
			"VALUES" => $arTypes,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCKS" => array(
			"PARENT" => "BASE",
			"NAME" => 'Код инфоблока',
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"MULTIPLE" => "Y",
			"REFRESH" => "Y",
		),
	)
);
