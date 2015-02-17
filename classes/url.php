<?php

// Url helper
class Url 
{
	public static function get($page, $view)
	{
		return Config::get('site', 'base_uri').'/'.$page.'/'.$view;
	}
}

