<?php

trait base_type {
	
	/**
	 * Геттер обеспечивает доступ на чтение ко всем защищённым свойствам потомков
	 * @param string $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		} else {
			throw new \Exception('Не найдено свойство '.$name);
		}
	}
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name) {
		return property_exists($this, $name);
	}
	
}