<?php
class FormError {
	
	private $errors = array();

	public function add_error($field, $message) {
		$this->errors[] = array(
			"field"=>$field, 
			"message"=>$message
		);
	}

	public function has_any() {
		return count($this->errors) > 0;
	}

	public function has_error($field) {
		foreach ($this->errors as $key => $value) {
			if($value['field'] == $field) {
				return true;
			}
		}
		return false;
	}

	public function output_html() {
		$html = '<ul class="errors">';
		foreach ($this->errors as $key => $value) {
			$html .= '<li>'.$value['message'].'</li>';
		}
		$html .= '</ul>';
		return $html;
	}

}