<?php

namespace input;

class input {
	
	public $rawVals = [];
	
	public $vals = [];
	
	public $errors = [];
	
	private $native_filters = [];
	
	public function __construct() {
		$this->native_filters = filter_list();
	}
	
	public function filter($data, array $inputs) {
		foreach ($inputs as $input => $filters) {
			$val = isset($data[$input])? $data[$input] : null;
			$this->rawVals[$input] = $val;
			$this->vals[$input] = $this->filter_val($val, $filters)?: null;
		}
	}
	
	private function filter_val($val, $filters) {
		foreach ($filters as $filter_key => $filter_val) {
			if (is_int($filter_key)) {
				$filter = $filter_val;
				$options = [];
				$message = null;
			} else {
				$filter = $filter_key;
				
				if (is_string($filter_val)) {
					$options = [];
					$message = $filter_val;
				} else {
					$filter_val = (array) $filter_val;
					$options = isset($filter_val['options'])? $filter_val['options'] : [];
					$message = isset($filter_val['message'])? $filter_val['message'] : '';
				}
			}

			if (in_array($filter, $this->native_filters)) {
				// стандартный фильтр
				$result = filter_var($val, filter_id($filter), $options);
			} elseif (is_string($filter) && is_callable(__NAMESPACE__.'\\'.$filter)) {
				// функция в локальном пространстве имён
				$result = call_user_func_array(__NAMESPACE__.'\\'.$filter, array_merge([$val], $options));
			} elseif (is_callable($filter)) {
				// глобальная функция или колбэк
				$result = call_user_func_array($filter, array_merge([$val], $options));
			} else {
				trigger_error('Неизвестный фильтр', E_USER_WARNING);
			}
			
			if (is_bool($result)) {
				if ($result === false) {
					$this->errors[$filter][] = $message;
					break;
				}
			} else {
				$val = $result;
			}
		}
		
		return $result;
	}
	
}

function filter($data, $inputs) {
	$input = new input();
	$input->filter($data, $inputs);
	
	return $input;
}

function required($val) {
	return ! empty($val);
}
