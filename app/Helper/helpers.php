<?php

use Illuminate\Support\HtmlString;
use Carbon\Carbon;

// HTTP request
function preventHttp() {
    if(!request()->ajax()) {
        die('Not ajax request');
    }
    csrf_field();
}

// Form helpers
function label($id, $label) {
	return new HtmlString("<label for=\"{$id}\">{$label}</label>");
}

function inputGroupBegin($id, $label) {
	return new HtmlString("<div class=\"input-group\"><div class=\"input-group-prepend\"><label for=\"{$id}\" class=\"input-group-text bg-white\">{$label}</label></div>");
}

function inputGroupEnd() {
	return new HtmlString("</div>");
}

function input($errors, $type, $name, $id, $value = '', $class = '', $attrs = []) {
	if($type === 'textarea') {
		$tag = 'textarea';
		$typestring = '';
	} else {
		$tag = 'input';
		$typestring = " type=\"{$type}\"";
	}

	$htmlname = htmlName($name);
	$namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

	$idstring = $id === '' ? '' : " id=\"{$id}\"";

	$fullvalue = old($name, $value);
	$valuestring = ($type === 'textarea' || $fullvalue === '') ? '' : " value=\"{$fullvalue}\"";



	if($type === 'hidden') {
		$classstring = $class === '' ? '' : " class=\"{$class}\"";
	} else {
		$classstring = ' class="form-control' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';
	}
	$attrstring = '';
	foreach($attrs as $attr => $val) {
		if($val === false) continue;
		if($val === true) {
			$attrstring .= " {$attr}";
		} else {
			$attrstring .= " {$attr}=\"{$val}\"";
		}
	}

	$inputstring = "<{$tag}{$typestring}{$namestring}{$idstring}{$valuestring}{$classstring}{$attrstring}>";
	if($type === 'textarea') {
		$inputstring .= "{$fullvalue}</{$tag}>";
	}
	return new HtmlString($inputstring);
}

function disabledInput($errors, $type, $name, $id, $value = '', $class = '', $attrs = []) {
	$attrs = array_merge($attrs, ['disabled' => true]);
	return input($errors, $type, $name, $id, $value, $class, $attrs);
}

function select($errors, $name, $id, $value, $options, $class = '', $attrs = [], $optionvalue = 'id', $optiontext = 'name') {
	$htmlname = htmlName($name);
	$namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

	$idstring = $id === '' ? '' : " id=\"{$id}\"";

	$fullvalue = old($name, $value);

	$classstring = ' class="form-control' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

	$attrstring = '';
	foreach($attrs as $attr => $val) {
		if($val === false) continue;
		if($val === true) {
			$attrstring .= " {$attr}";
		} else {
			$attrstring .= " {$attr}=\"{$val}\"";
		}
	}

	$inputstring = "<select{$namestring}{$idstring}{$classstring}{$attrstring}>";
	if((array_key_exists('not_selected_label', $attrs) && $attrs['not_selected_label'] === true) || !array_key_exists('required', $attrs) || $attrs['required'] === false)
		$inputstring .= '<option value="">(বাছাই করুন)</option>';
	foreach($options as $option) {
		$selectedstring = $option->$optionvalue == $value ? ' selected' : '';
		$inputstring .= "<option value=\"{$option->$optionvalue}\"{$selectedstring}>{$option->$optiontext}</option>";
	}
	$inputstring .= "</select>";

	return new HtmlString($inputstring);
}

function disabledSelect($errors, $name, $id, $value, $options, $class = '', $attrs = [], $optionvalue = 'id', $optiontext = 'name') {
	$attrs = array_merge($attrs, ['disabled' => true]);
	return select($errors, $name, $id, $value, $options, $class, $attrs, $optionvalue, $optiontext);
}

function checkbox($errors, $name, $id, $checked = false, $class = '', $attrs = []) {
	$htmlname = htmlName($name);
	$namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

	$idstring = $id === '' ? '' : " id=\"{$id}\"";

	$classstring = ' class="' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

	$attrstring = '';
	foreach($attrs as $attr => $val) {
		if($val === false) continue;
		if($val === true) {
			$attrstring .= " {$attr}";
		} else {
			$attrstring .= " {$attr}=\"{$val}\"";
		}
	}

	$inputstring = "<input type=\"checkbox\"{$namestring}{$idstring}{$classstring}{$attrstring}>";
	return new HtmlString($inputstring);
}

function radio($errors, $name, $id, $checked = false, $class = '', $attrs = []) {
	$htmlname = htmlName($name);
	$namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

	$idstring = $id === '' ? '' : " id=\"{$id}\"";

	$classstring = ' class="' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

	$attrstring = '';
	foreach($attrs as $attr => $val) {
		if($val === false) continue;
		if($val === true) {
			$attrstring .= " {$attr}";
		} else {
			$attrstring .= " {$attr}=\"{$val}\"";
		}
	}

	$inputstring = "<input type=\"radio\"{$namestring}{$idstring}{$classstring}{$attrstring}>";
	return new HtmlString($inputstring);
}

function error($errors, $name) {
	if($errors->has($name)) {
		return new HtmlString('<div class="invalid-feedback">' . $errors->first($name) . '</div>');
	}
}

function htmlName($laravelName) {
	$parts = explode('.', $laravelName);
	$string = '';

	foreach($parts as $i => $part) {
		if($i == 0) {
			$string .= $part;
		} else {
			$string .= "[{$part}]";
		}
	}
	return $string;
}

function orderUrl($field, $orderby, $order) {
	if($field == $orderby) {
		$neworder = $order == 'asc' ? 'desc' : 'asc';
	} else {
		$neworder = 'asc';
	}
	return new HtmlString(request()->fullUrlWithQuery(['orderby' => $field, 'order' => $neworder]));
}

function orderArrow($field, $orderby, $order) {
	if($field != $orderby) {
		return '';
	}
	if($order == 'asc') {
		return new HtmlString('&#x25B2;');
	} else {
		return new HtmlString('&#x25BC;');
	}
}

// Storage helpers
function imagePath($filename = '') {
    if(empty($filename))
        return public_path('images/small-thumbnail/');
    	return public_path('images/small-thumbnail/' . $filename);
}

function tempImagePath($filename = '') {
    if(empty($filename))
        return public_path('images/small-thumbnail/');
    return public_path('images/small-thumbnail/' . $filename);
}

// Image helpers
function randomImageFileName() {
	return date('YmdHis') . uniqid() . '.jpg';
}

function imageRoute($filename, $template = 'small') {
	return route('imagecache', compact('template', 'filename'));
}

// String format
function toFixed($number, $digits = 2) {
	if($number === '') {
		return $number;
	}
	return number_format((float)$number, $digits, '.', '');
}
function dateFormat($datestring, $format = 'd/m/Y', $inputFormat = 'Y-m-d H:i:s') {
    return Carbon::parse($datestring)->format($format);
}

function dateTimeFormat($datestring, $format = 'd/m/Y h:i:s A', $inputFormat = 'Y-m-d H:i:s') {
    return Carbon::parse($datestring)->format($format);
}

// Bangla helpers
function toBangla($string)
{
    if(App::getLocale() == 'en') return $string;
    return str_replace(
        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
        $string
    );
}