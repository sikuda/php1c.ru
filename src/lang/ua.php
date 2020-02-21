<?php
/**
* Модуль Украинского языка для получения кода PHP из 1С
* 
* @author  sikuda admin@sikuda.ru
* @version 0.1
*/
namespace php1C;

const php1C_Identifiers = '/^[_A-Za-zА-Яа-яҐґЄєЇї][_0-9A-Za-zА-Яа-яҐґЄєЇї]*/u';

//Для преобразования имен в английский
const php1C_LetterLng = array('А','Б','В','Г','Ґ','Д','Е', 'Є', 'Ж','З','И','I', 'Ї', 'Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х' ,'Ц', 'Ч', 'Ш' , 'Щ','Ь','Ю' ,'Я',
							  'а','б','в','г','ґ','д','е', 'є', 'ж','з','и','i', 'ї', 'й','к','л','м','н','о','п','р','с','т','у','ф','х' ,'ц', 'ч', 'ш' , 'щ','ь','ю' ,'я');
const php1C_LetterEng = array('A','B','V','G','G','D','E','EH','ZH','Z','I','I','JI','JJ','K','L','M','N','O','P','R','S','T','U','F','KH','C','CH','SH','SHH','','YU','YA',
							  'a','b','v','g','g','d','e','eh','zh','z','i','i','ji','jj','k','l','m','n','o','p','r','s','t','u','f','kh','c','ch','sh','shh','','yu','ya');

const php1C_Keywords = array(
	'НЕВИЗНАЧЕНО',  //keyword_undefined = 0
	'ЩОПРАВДА',     //keyword_true   = 1;
	'БРЕХНЯ',       //keyword_false  = 2;
	'ЯКЩО',         //keyword_if     = 3;
	'ТОДІ',         //keyword_then = 4; 
	'ІНАКШЕЯКЩО',   //keyword_elseif = 5;
	'ІНАКШЕ',       //keyword_else = 6;
	'КIНЕЦЬЯКЩО',   //keyword_endif = 7; 
	'ПОКИ',         //keyword_while = 8;
	'ДЛЯ',          //keyword_for = 9;
	'КОЖНОГО',      // keyword_foreach = 10;
	'ПО',           // keyword_to = 11;
	'В',            //keyword_in = 12; 
	'З',            //keyword_from = 13;
	'ЦИКЛ',         //keyword_circle = 14;
	'КIНЕЦЬЦИКЛУ',  //keyword_endcircle = 1
	'ПЕРЕРВАТИ',    // keyword_break = 16;
	'ПРОДОВЖИТИ',   // keyword_continue = 17
	'ФУНКЦIЯ',      // keyword_function = 18
	'ПРОЦЕДУРА',    //keyword_procedure = 1
	'КIНЕЦЬФУНКЦIЇ',   //keyword_endfunction =
	'КIНЕЦЬПРОЦЕДУРИ', //keyword_endprocedure 
	'ПОВЕРНЕННЯ',     // keyword_return = 22;
	'ПЕРЕМ',          //keyword_var = 23;
	'СИМВОЛИ',        // keyword_chars = 24;
	'ЕКСПОРТ',        //keyword_export  = 25;
	'ЗНАЧ');          //keyword_val     =26;

/**
* Массив названий русских типов для работы с коллекциями
* @return array of string - Массив названий функций работы с коллекциями.
*/
const php1C_types_Collection = array('Масив','Структура','ТаблицяЗначень');

/**
* Массив общих русских функций для общей работы с 1С
*/
const php1C_functions_Com = array(
	'Передавати(', 
	'Шукати(', 
	'ЗначенняЗаповнене(', 
	'Тип(', 
	'ТипЗнч(',
	'Стрічка('),
	'Число('
);

/**
* Массив названий русских функций для работы со строками
*/
const php1C_functions_String = array('СтрДлина(','СокрЛ(','СокрП(','СокрЛП(','Лев(','Прав(','Сред(','СтрНайти(','НРег(','ВРег(', 'ТРег(', 'Символ(','КодСимвола(','ПустаяСтрока(','СтрЗаменить(','СтрЧислоСтрок(','СтрПолучитьСтроку(','СтрЧислоВхождений(', 'СтрСравнить(','СтрНачинаетсяС(','СтрЗаканчиваетсяНа(', 'СтрРазделить(', 'СтрСоединить(');

/**
* Массив названий русских функций для работы с числами
*/
const php1C_functions_Number = array('Цел(','Окр(', 'Log(','Log10(','Sin(','Cos(','Tan(','ASin(','ACos(','ATan(','Exp(','Pow(','Sqrt(','Формат(', 'ЧислоПрописью(', 'НСтр(', 'ПредставлениеПериода(', 'СтрШаблон(', 'СтрокаСЧислом('  );

/**
* Массив названий русских функций для работы с датой
*/
const php1C_functions_Date = array('Дата(','ТекущаяДата(', 'Год(', 'Месяц(','День(', 'Час(',  'Минута(', 'Секунда(','НачалоГода(','НачалоКвартала(','НачалоМесяца(','НачалоНедели(','НачалоДня(','НачалоЧаса(','НачалоМинуты(','КонецГода(','КонецКвартала(','КонецМесяца(','КонецНедели(','КонецДня(','КонецЧаса(','КонецМинуты(','НеделяГода(', 'ДеньГода(', 'ДеньНедели(', 'ДобавитьМесяц(');

/**
* Массив названий русских функций для работы с датой
* @return string[] Массив
*/
const php1C_functions_Collections = array('ВГраница(', 'Вставить(', 'Добавить(', 'Количество(', 'Найти(', 'Очистить(','Получить(', 'Удалить(','Установить(','Свойство(','ЗагрузитьКолонку(','ВыгрузитьКолонку(', 'ЗаполнитьЗначения(','Индекс(', 'Итог(', 'Найти(','НайтиСтроки(','Очистить(','Свернуть(', 'Сдвинуть(','Скопировать(', 'СкопироватьКолонки(','Сортировать(','Удалить(');

const php1C_Undefined = "Невизначано";
const php1C_Bool = array("Да","Ні");


// ЯКЩО ЩОПРАВДА <> БРЕХНЯ ТОДІ
// 	Передавати("ЦЕ НЕ БРЕХНЯ");
// ІНАКШЕ
// 	Передавати("ЦЕ БРЕХНЯ");
// КIНЕЦЬЯКЩО



?>