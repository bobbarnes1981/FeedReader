<?php

// Html helper
class Html
{
	public static function style($path)
	{
        return '<link href="'.Config::get('site', 'base_uri').'/'.$path.'" rel="stylesheet" />';
	}

	public static function script($path)
	{
        return '<script src="'.Config::get('site', 'base_uri').'/'.$path.'"></script>';
	}

	public static function link($title, $path, $attributes = array())
	{
		return '<a href="'.$path.'"'.Html::expandAttributes($attributes).'>'.$title.'</a>';
	}

	public static function expandAttributes($source)
	{
		$attributes = '';
		foreach ($source AS $key => $value)
		{
			$attributes .= ' '.$key.'="'.$value.'"';
		}
		return $attributes;
	}
}

