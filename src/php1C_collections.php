<?php
/**
* Модуль работы c универсальными коллекциями значений 1С
* 
* Модуль для работы с массивами, структурами  в 1С и функциями для работы с ними
* для будущего (соответствиями, списком значений, таблица значений)
* 
* @author  sikuda admin@sikuda.ru
* @version 0.1
*/

namespace php1C;
use Exception;
require_once('php1C__tokens.php');


/**
* Массив названий типов для работы с коллекциями переименовании
* @return array of string - Массив названий функций работы с коллекциями или пустые.
*/
function typesPHP_Collection(){
	return array('Array1C','Structure1C','ValueTable');
}

/**
* Массив названий английских функций для работы с датой. Соответстует элементам русским функций.
* @return string[] Массив названий функций работы с датой.
*/   
function functionsPHP_Collections(){
	return  array('UBound(',   'Insert(',   'Add(',      'Count(',      'Find(',  'Clear('  , 'Get(',      'Del(',    'Set(',       'Property(','LoadColumn(',     'UnloadColumn(',      'FillValues(',      'IndexOf(','Total(','Find(','FindRows(',    'Clear(',   'GroupBy(',  'Move(',    'Copy(',       'CopyColumns(',          'Sort(',       'Del(');
}

/**
* Вызывает функции и функции объектов 1С работы с коллекциями
*
* @param string $key строка названии функции со скобкой
* @param array $arguments аргументы функции в массиве
* @return возвращает результат функции или выбрасывает исключение
*/
function callCollectionType($key, $arguments){
	switch ($key) {
		case 'Array1C': return Array1C($arguments);
		case 'Structure1C': return Structure1C($arguments);
		case 'ValueTable': return ValueTable($arguments);
		default:
			throw new Exception('Пока тип в коллекциях не определен '.$key);
			break;
	}
}	

/**
* Вызывает функции и функции объектов 1С работы с коллекциями
*
* @param object $context объект для вызова функции или null
* @param string $key строка названии функции со скобкой
* @param array $arguments аргументы функции в массиве
* @return возвращает результат функции или выбрасывает исключение
*/
function callCollectionFunction($context=null, $key, $arguments){
	if($context === null){
		switch($key){
		//case 'func(':
		//	break;
		default:
			throw new Exception("Неизвестная функция работы с коллекциями ".$key."");
		}	
	}
	else{
		if( method_exists($context, substr($key, 0, -1) )){ 
			switch($key){
			case 'UBound(': return $context->UBound();
			case 'Insert(': return $context->Insert($arguments[0], $arguments[1]);
			case 'Add(':    if(isset($arguments[0])) return $context->Add($arguments[0]);
							else return $context->Add();
			case 'Count(':  return $context->Count();
			case 'Find(':   return $context->Find($arguments[0]);
			case 'Clear(':  return $context->Clear();
			case 'Get(':    return $context->Get($arguments[0]);	
			case 'Del(':    return $context->Del($arguments[0]);
			case 'Set(':    return $context->Set($arguments[0], $arguments[1]);
			case 'Property(': return $context->Property($arguments[0], $arguments[1]);
			case 'LoadColumn(': return $context->LoadColumn($arguments[0], $arguments[1]);
			case 'UnloadColumn(': return $context->UnloadColumn($arguments[0]);
			case 'FillValues(': return $context->FillValues($arguments[0], $arguments[1]);
			case 'IndexOf(': return $context->IndexOf($arguments[0]);
			case 'Total(': return $context->Total($arguments[0]);
			case 'Find(': return $context->Total($arguments[0],$arguments[1]);
			case 'FindRows(': return $context->FindRows($arguments[0]);
			case 'Clear(': return $context->Clear();
			case 'GroupBy(': return $context->GroupBy($arguments[0], $arguments[1]);
			case 'Move(': return $context->Move($arguments[0], $arguments[1]);
			case 'Copy(': return $context->Copy($arguments[0], $arguments[1]);
			case 'CopyColumns(': return $context->CopyColumns($arguments[0]);
			case 'Sort(': return $context->Sort($arguments[0], $arguments[1]);
			case 'Del(': return $context->Del($arguments[0]);
			default:
				throw new Exception("Нет обработки функции для объекта коллекции ".$key."");
			}
		}else{
			throw new Exception("Не найдена функция у объекта коллекции  ".$key."");
		}
	}
}


//---------------------------------------------------------------------------------------------------------
function Array1C($args=null){
	return new Array1C($args);
}

/**
* Класс для работы с массивом 1С
*
*/
class Array1C{
	/**
	* @var array внутренее хранение массива
	*/
	private $value; //array of PHP 

	function __construct($counts=null, $copy=null){

		if(is_array($copy)) $this->value = $copy;
		else{	
			$this->value = array();
			$cnt = 0;
			if(is_array($counts) && (count($counts)>0)){
				if( count($counts) > 1 ) throw new Exception("Многомерные массивы пока не поддерживаются");
				$cnt = $counts[0];
				if( is_numeric($cnt) && $cnt > 0 ){
					for ($i=0; $i < $cnt; $i++) $this->value[i] = null;
				}
			} 
		}
	}

	function __toString(){
		return "Массив";
	}

	function toArray(){
		return $this->value;
	}

	function UBound(){
		$key = count($this->value);
		if(is_null($key) ) return -1;
		else return $key-1;  
	}

	function Insert($index, $val){
		$this->value[$index] = $val;
	}

	function Add($val){
		$this->value[] = $val;
		return $this;
	}

	function Count(){
		return count($this->value);
	}

	function Find($val){
		$key = array_search($val, $this->value);
		if($key === FALSE) return new undefined1C();
		else return $key;
	}

	function Clear(){
		//tocheck
		unset($this->value);
		$this->value = array();
		//return array_filter($this->value, function(){ return FALSE;});
	}

	function Get($index){
		return $this->value[$index];
	}

	function Del($index){
		//array_splice($this->value, $index, 1);
	    unset($this->value[$index]);
	}

	function Set($index, $val){
		$this->value[$index] = $val;
	}
}

//------------------------------------------------------------------------------------------

/**
* Получение структуры 1С 
*
* @param array $cnt аргументы функции в массиве
* @return возвращает новый объект массива 1С
*
*/
function Structure1C($args=null){
	return new Structure1C($args);
}

/**
* Класс для работы со структурой 1С
*/
class Structure1C{
	/**
	* @var array внутренее хранение массива
	*/
	private $value; //array of PHP 

	function __construct($args=null,$copy=null){

		if(is_array($copy)) $this->value = $copy;
		else{	
			$this->value = array();
			if( (count($args) > 0) && is_string($args[0])){
				$keys = explode(',',$args[0]);
				for ($i=0; $i < count($keys); $i++) {
					$k = strtoupper(trim ($keys[$i]));
					if( fEnglishVariable ) $k = str_replace(php1C_LetterLng, php1C_LetterEng, $k);
					if(!isset($args[$i+1])) $this->value[$k] = null;
					else $this->value[$k] = $args[$i+1];
				}
			}
		}
	}

	function toArray(){
		return $this->value;
	}

	function __toString(){
		return "Структура";
	}

	function Insert($key, $val=null){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$this->value[strtoupper($key)] = $val;
	}

	function Count(){
		return count($this->value);
	}

	function Property($key, $value=null){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$key = strtoupper($key);
		$value = $this->value[$key];
		return array_key_exists($key, $this->value);
	}

	function Clear(){
		//tocheck
		unset($this->value);
		$this->value = array();
	}

	function Del($key){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$key = strtoupper($key);
		unset($this->value[$key]);
	}

	//Для получения данных через точку
	function Get($key){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$key = strtoupper($key);
		return $this->value[$key];
	}

	//Для установки данных через точку
	function Set($key, $val=null){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$key = strtoupper($key);
		if(array_key_exists($key, $this->value)) $this->value[$key] = $val;
		else throw new Exception("Не найден ключ структуры ".$key);
	}	
}

//----------------------------------------------------------------------------------------------

/**
* Получение ТаблицыЗначений
*
* @param array $args аргументы функции в массиве
* @return возвращает новый объект ТаблицаЗначений1С
*
*/
function ValueTable($args=null){
	return new ValueTable($args);
}

/**
* Класс для работы с таблицей значений 1С8
*
*/
class ValueTable{
	
	private $rows;   //array of ValueTableRow
	public $COLUMNS; //ValueTableColumnCollection - collection of ValueTableColumn
	public $КОЛОНКИ;
	public $KOLONKI;
	public $INDEXES; //CollectionIndexes коллекция из CollectionIndex
	public $ИНДЕКСЫ;
	public $INDEKSYY;
	
	function __construct($args=null,$copy=null){

		if(is_array($copy)) $this->rows = $copy;
		else{	
			$this->rows = array();
			$this->COLUMNS = new ValueTableColumnCollection($this);
			$this->КОЛОНКИ = &$this->COLUMNS;
			$this->KOLONKI = &$this->COLUMNS;
			$this->INDEXES = new CollectionIndexes($this);
			$this->ИНДЕКСЫ = &$this->INDEXES;
			$this->$INDEKSYY = &$this->INDEXES;
		}
	}

	function __toString(){
		if (fEnglishTypes) return "ValueTable";
		else return "ТаблицаЗначений";
	}

	function toArray(){
		return $this->rows;
	}

	//Добавить новую строку в таблицу
	function Add(){
		$row = new ValueTableRow($this);
		$this->rows[] = $row;
		return $row;
	}

	//Вставить новую строку в таблицу
	function Insert($index){
		if(is_int($index)){
			$row = new ValueTableRow($this);
			$this->rows[$index] = $row;
			return $row;
		}
		else  new Exception("Индекс задан неверно");	
	}

	//Выгрузка колонки в Array1C
	function UnloadColumn($col){
		if(is_int($col)){
			$col = $this->COLUMNS->cols[$col];
		}elseif (is_string($col)) {
			if( fEnglishVariable ) $col = str_replace(php1C_LetterLng, php1C_LetterEng, $col);
			$col = $this->COLUMNS->cols[strtoupper($col)];
		}
		else throw new Exception("Не задана колонка для выгрузки ".$col);
		if(is_object($col) && get_class($col) === 'php1C\ValueTableColumn'){
			$array = new Array1C;
			foreach ($this->rows as $key => $value) {
				$val = $value->Get($col->NAME);
				$array->Add($val);
			}
			return $array;
		}
	}

	//Загрузка колонки из Array1C
	function LoadColumn($arr, $col){
		if(!is_object($arr) || get_class($arr) !== 'php1C\Array1C')
			throw new Exception("Первый аргумент должен быть массивом ".$arr);
		if(isset($col)){
			if(is_int($col)){
				$col = $this->COLUMNS->cols[$col];
			}elseif (is_string($col)) {

				$col = $this->COLUMNS->cols[strtoupper($col)];
			}	
			if(is_object($col) && get_class($col) === 'php1C\ValueTableColumn'){
				$k = 0;
				foreach ($this->rows as $key => $value) {
					$value->Set($col->NAME, $arr[$k]);
					$k++;
				}
				return;
			}
		}
		throw new Exception("Не найдена колонка для загрузки ".$col);
	}

	//Заполним имя всех столбцов
	function GetAllColumns(){
		$strcols = '';
		foreach ($this->COLUMNS->cols as $val) {
			$strcols .= $val->NAME.',';
		}	
		return substr($strcols,0,-1); //уберем последнюю запятую	
	}

	//Заполнить значениями таблицу
	function FillValues($value, $strcols=null){
		if(!isset($strcols)) $strcols = $this->GetAllColumns(); 
		if( fEnglishVariable ) $strcols = str_replace(php1C_LetterLng, php1C_LetterEng, $strcols);
		$keys = explode(',',$strcols);
		for ($i=0; $i < count($keys); $i++){
			$col = strtoupper(trim($keys[$i]));
			foreach ($this->rows as $val) {
				$val->Set($col, $value);
			}
		}
	}

	//Возвратить индекс строки в таблице
	function IndexOf($row){
		$key = array_search( $row, $this->rows);
		if( $key === FALSE ) $key = -1;
		return $key;
	}

	//Возвратить итог по колонке
	function Total($col){
		if( fEnglishVariable ) $col = str_replace(php1C_LetterLng, php1C_LetterEng, $col);
		$col = strtoupper($col);
		$sum = 0;
		foreach ($this->rows as $key => $value) {
			$val = $value->Get($col);
			if(is_numeric($val)){
				$sum += toNumber1C($val);
			}	
		}
		return $sum;
	}

	//Возвратить количество строк
	function Count(){
		return count($this->rows);
	}

	//Найти значение в талцице и возврать строку или Неопределено(null)
	function Find($value, $strcols=null){
		if(!isset($strcols)) $strcols = $this->GetAllColumns();
		$keys = explode(',',$strcols);
		for ($i=0; $i < count($keys); $i++){
			$col = strtoupper(trim($keys[$i]));
			foreach ($this->rows as $row) {
				$val = $row->Get($col);
				if( $val === $value ) return $row;
			}
		}
		return null;
	}

	//Поиск по структуре возврат Array1C
	function FindRows($filter){
		if(!is_object($filter) || get_class($filter) !== 'php1C\Structure1C'){
			throw new Exception("Аргумент функции должен быть структурой ".$filter);
		} 
		$array_filter = $filter->toArray();
		$array = new Array1C();
		foreach ($this->rows as $key => $row){
			$found = true;
			foreach ($array_filter as $key_filter => $value_filter) {
				if( $row[$key_filter] == $value_filter ){
					$found = false;
				}
			}
			if($found) $array->Add($row); 	
		}
		return $array;
	}

	//Очистить значения таблицы
	function Clear(){
		$this->rows->setValueTable(null);
		unset($this->rows);
		$this->rows = array();
	}

	//Для получения данных через точку
	function Get($key){
		if(is_string($key)){
			if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
			$key = strtoupper($key);
			if($key === 'КОЛОНКИ' || $key === 'COLUMNS' || $key === 'KOLONKI'){
				return $this->COLUMNS;
			}	
		}
		if(is_numeric($key)){
		 	return $this->rows[$key];
		}
		throw new Exception("Не найден ключ для строки ТаблицыЗначений ".$key);
	}

	//Для установки данных через точку
	function Set($key, $val=null){
		if(is_string($key)){
			if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
			$key = strtoupper($key);
			if(($key === 'КОЛОНКИ' || $key === 'COLUMNS') && (is_object($val) && get_class($val) === 'ValueTableColumnCollection')){
				$this->COLUMNS = $val;
				$this->COLUMNS->setValueTable($this);
			}	
		}
		if(is_numeric($key) && (is_object($val) && get_class($val) === 'ValueTableRow')){
		 	$this->rows[$key] = $val;
		}
		throw new Exception("Не найден имя столба ТаблицыЗначений ".$key);
	}

	//Группируем данные таблицы значений 
	function GroupBy($colgr, $colsum){
		if( fEnglishVariable ) $colgr = str_replace(php1C_LetterLng, php1C_LetterEng, $colgr);
		if( fEnglishVariable ) $colsum = str_replace(php1C_LetterLng, php1C_LetterEng, $colsum);
		$grkeys = explode(',',$colgr);
		$sumkeys = explode(',',$colsum);
		$table = $this->CopyColumns($colgr.','.$colsum);
		$this->COLUMNS = $table->COLUMNS;
		$this->COLUMNS->setValueTable($this);
		foreach ($this->rows as $row) {

			//Поиск совпадений по группировке
			$fnew = true;
			foreach ($table->rows as $newrow){
				$found = true;
				foreach ($grkeys as $grkey){
					if($newrow->Get($grkey) != $row->Get($grkey)){
						$found = false;
						break;
					}
				}
				if($found){
					$fnew = false;
					break;
				} 
			}
			
			if($fnew){
				//новая строка
				$newrow = $table->Add($this);
				$newrow->setValueTable($this);
				foreach ($grkeys as $grkey){
					$newrow->Set($grkey, $row->Get($grkey));	
				}
				foreach ($sumkeys as $sumkey){
					$newrow->Set($sumkey, $row->Get($sumkey));	
				}
			}else{
				//суммируем данные в строку
				foreach ($sumkeys as $sumkey){
					$curr = $newrow->Get($sumkey);
					$newrow->Set($sumkey, $curr + $row->Get($sumkey));
				}
			}
		}
		unset($this->rows);
		$this->rows = $table->rows;
		unset($table);
	}

	//Сдвинуть строку $row на $offset
	function Move($row, $offset){
		if(is_object($row) && get_class($row) === 'php1C\ValueTableRow'){
			$row = $this->IndexOf($row);
		}
		if(!is_float($row) && !is_int($row)) throw new Exception("Первый параметр должен быть числом или строкой ТаблицыЗначений");
		if(!is_float($offset) && !is_int($offset)) throw new Exception("Второй параметр должен быть числом");
		$row_object = $this->rows[$row];
		array_splice($this->rows,$row,1);
		array_splice($this->rows,$row+$offset,0,array($row_object));
	}

	/** 
	* Скопировать таблицуЗначений с фильтрацией по строкам и колонкам
	*
	* @param Array1C $rows массив строк для выгрузки
	* @param string $cols строка перечисления колонок
	* @return возвращает новый объект ТаблицаЗначений1С
	*/
	function Copy($rows=null, $strcols=null){
		if(isset($row) && (!is_object($rows) || get_class($rows) !== 'php1C\Array1C')) throw new Exception("Первый параметр должен быть массивом строк или пустым");
		if(!isset($strcols)) $strcols = $this->GetAllColumns();
		if( fEnglishVariable ) $strcols = str_replace(php1C_LetterLng, php1C_LetterEng, $strcols);
		$array = $this->CopyColumns($strcols);
		if(!isset($rows)) $rows = $this->rows;
		else $rows = $rows->toArray();
		foreach ($rows as $row){
			$newrow = $array->Add();
			foreach ($array->COLUMNS->cols as $col){
				//var_dump($col);
				$newrow->Set($col->NAME, $row->Get($col->NAME));
			}
		}	
		return $array;
	}

	/** 
	* Скопировать пустые колонки ТаблицуЗначений в новую ТаблицуЗначений
	*
	* @param string $strcols строка перечисления колонок
	* @return возвращает новый объект ТаблицаЗначений1С
	*/
	function CopyColumns($strcols){
		if(!isset($strcols)) $strcols = $this->GetAllColumns();
		if( fEnglishVariable ) $strcols = str_replace(php1C_LetterLng, php1C_LetterEng, $strcols);
		$array = new ValueTable;
		$keys = explode(',',$strcols);
		for ($i=0; $i < count($keys); $i++){
			$col = strtoupper(trim($keys[$i]));
			$array->COLUMNS->Add($col);
		}
		return $array;
	}

	/**
	* Отсортировать таблицу значений по стоке с колонками
	* 
	* @param strcols string строка перечислений колонов и порядка сортировки ("Товар, Цена Убыв")
	* @param cmp_object объект сортировки //TODO
	*/
	function Sort($strcols, $cmp_object=null){

		if (isset($cmp_object)) throw new Exception("Пока нет реализации по объекту сравнения");

		if(!isset($strcols)) $strcols = $this->GetAllColumns();
		if( fEnglishVariable ) $strcols = str_replace(php1C_LetterLng, php1C_LetterEng, $strcols);
		if(!is_string($strcols)) throw new Exception("Первый параметр должен быть обязаельно заполнен наименованиями колонок");
		$this->sort = array();
		$this->sortdir = array();
		$pairs = explode(',',$strcols);
		foreach ($pairs as $pair) {
		 	$keys = explode(' ',$pair);
		  	$col = strtoupper(trim($keys[0]));
			$coldir = strtoupper(trim($keys[1]));
			//echo $coldir;
			if($coldir==='УБЫВ' || $coldir==="DESC") $this->sortdir[] =-1;
			else $this->sortdir[] = 1;
			$this->sort[] = $col;
		}
		usort($this->rows, function($a, $b){
			for($i=0;$i<count($this->sort);$i++){
				$vala = $a->Get($this->sort[$i]);
				$valb = $b->Get($this->sort[$i]);
				if($vala !== $valb) return $this->sortdir[$i] *(($vala < $valb) ? -1 : 1);
			}
			return 0;
		});
		unset($this->sortdir);
		unset($this->sort);
	}

	//Удалить строку из таблицы
	function Del($row){
		if(is_int($row)){
			$row = $this->rows[$row];
		}elseif(!is_object($row) && get_class($row) !== 'php1C\ValueTableRow'){
			throw new Exception("Параметр может быть либо строкой либо числом");
		}
		$key = $this->IndexOf($row);
		if($key !== -1){
			$row->setValueTable(null);
			unset($this->rows[$key]);
		}	
	}
}

/**
* Класс колекции колонок таблицы значений 1С
*
*/
class ValueTableColumnCollection{

	/**
	* @var array коллекция ValueTableColumn
	*/
	private $ValueTable;
	public $cols; 

	function __construct($parent){
		$this->ValueTable = &$parent;
		$this->cols = array();
	}

	function toArray(){
		return $this->cols;
	}

	function setValueTable($parent){
		$this->ValueTable = &$parent;
	}

	function __toString(){
		return "КоллекцияКолонокТаблицыЗначений";
	}

	function Add($key=null){
		if(!isset($key)) $key = ''; //пустые имена колонок в 1С допустимы.
		if(is_string($key)){
			if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);		
			$key = strtoupper($key);
			$this->cols[$key] = new ValueTableColumn($key);
		}
		else  new Exception("Имя колонки должно быть строкой");	
	}

	function Count(){
		return count($this->cols);
	}
}

/**
* Класс колонки таблицы значений 1С
*
*/
class ValueTableColumn{

	/**
	* @var array коллекция значений в колонке
	*/
	public $NAME; 
	public $ИМЯ;

	function __construct($val=null){
		$this->NAME = $val;
		$this->ИМЯ  = &$this->NAME;
	}

	function __toString(){
		return "КолонкаТаблицыЗначений";
	}

}

/**
* Класс строки для таблицы значений 1С
*
*/
class ValueTableRow{

	/**
	* @var array коллекция значений в строке
	*/
	private $ValueTable; //parent  
	private $row;        //array of fields

	function __construct($args=null){
		if(isset($args)) $this->ValueTable = &$args;
		$this->row = array();
	}

	function __toString(){
		return "СтрокаТаблицыЗначений";
	}

	function setValueTable($parent){
		$this->ValueTable = &$parent;
	}

	//Для получения данных через точку
	function Get($key){
		if(is_string($key)){
			if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
			$key = strtoupper($key);
			$array = $this->ValueTable->COLUMNS->cols;
			if(array_key_exists($key, $array)){
				$key = strtoupper($key);
				return $this->row[$key];
			} 	
		}
		throw new Exception("Поле объекта не обнаружено у строки таблицы ".$key);
	}

	//Для установки данных через точку
	function Set($key, $value=null){
		if(is_string($key)){
			if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
			$key = strtoupper($key);
			$this->row[$key] = $value;	
		}
		else throw new Exception("Нет такой колонки в таблице");
	}
}

/**
* Коллекция индексов(пока пустая реальзация для ТаблицыЗначений)
*/
class CollectionIndexes{
	/**
	* @var array коллекция значений в строке
	*/
	private $ValueTable;
	private $indexs;

	function __construct($parent){
	 	$this->ValueTable = &$parent;
	 	$this->indexs = array();
	}

	function __toString(){
	 	return "ИндексыКоллекции";
	}

	function toArray(){
		return $this->indexs;
	}

	function Add($name){
		if(is_string($key)){
			if( fEnglishVariable ) $keyl = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
			$key = strtoupper($key);
			$this->cols[$key] = new CollectionIndex($name);
		}
		else  new Exception("Имя колонки должно быть строкой");	
	}

	function Count(){
		return count($this->indexs);
	}

	function Clear(){
		//tocheck
		unset($this->indexs);
		$this->indexs = array();
	}

	function Del($key){
		if( fEnglishVariable ) $key = str_replace(php1C_LetterLng, php1C_LetterEng, $key);
		$key = strtoupper($key);
		unset($this->indexs[$key]);
	}
}

/**
* Индекс коллекции(пока пустая реальзация для ТаблицыЗначений)
*/
class CollectionIndex{
	/**
	* @var array коллекция значений в строке
	*/
	private $name;
	function __construct($col){
	 	$this->name = $col;
	}

	function __toString(){
	 	return "ИндексКоллекции";
	}	
}
