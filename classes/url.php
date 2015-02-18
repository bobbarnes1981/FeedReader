<?php

// Url helper
class Url 
{
	public static function get($page, $view, $query = array())
	{
		return Config::get('site', 'base_uri').'/'.$page.'/'.$view.Url::expandQuery($query);
	}

	public static function expandQuery($source)
	{
		$query = '?';
		foreach ($source AS $key => $value)
		{
			$query .= $key.'='.$value.'&';
		}
		return $query;
	}

	public static function go($page, $view)
	{
		header('Location: '.Url::get($page, $view));
		exit();
	}
}

