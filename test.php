<?php
	require_once('src/php1C__code.php');

	//$str = 'ВидСубконто = ?(ТипЗнч(ИмяСубконто) = Тип("ПланВидовХарактеристикСсылка.ВидыСубконтоХозрасчетные"), ИмяСубконто, ПланыВидовХарактеристик.ВидыСубконтоХозрасчетные[ИмяСубконто]);';
	$str = 'Перем Результат; Процедура Сложение( d, Я) Возврат "4" + d + Я; КонецПроцедуры  Результат=Сложение(1, 2);';
	//$str = 'result = 1;'; //
	$result = php1C\makeCode($str, "Результат");
	
	echo $result;
?>