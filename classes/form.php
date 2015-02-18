<?php

// gorm helper
class Form
{
	// form method (post,get,put)
	private $method;

	// form action/url
	private $action;

	// request object
	private $request;

	// attributes
	private $attributes;

	// form items
	private $items = array();

	public static $TAG_INPUT = 'input';
	public static $TAG_BUTTON = 'button';

	public static $INPUT_TEXT = 'text';
	public static $INPUT_PASSWORD = 'password';

	public static $BUTTON_SUBMIT = 'submit';

	public function __construct($method, $action, $request, $attributes = array())
	{
		$this->method = $method;
		$this->action = $action;
		$this->request = $request;
		$this->attributes = $attributes;
	}

	public function add($tag, $type, $name, $attributes = array())
	{
		switch($this->method)
		{
			case \Request::$POST:
				$value = $this->request->post[$name];
				break;
			case \Request::$GET:
				$value = $this->request->get[$name];
				break;
		}
		$this->items[] = array(
			'tag' => $tag,
			'type' => $type,
			'name' => $name,
			'value' => $value,
			'attributes' => $attributes
		);
	}

	public function get($name)
	{
		return $this->getItem($name);
	}

	public function begin()
	{
		return '<form'.Html::expandAttributes($this->attributes).' action="'.$this->action.'" method="'.$this->method.'">';
	}

	public function end()
	{
		return '</form>';
	}

	private function getItem($name)
	{
		foreach ($this->items AS $item)
		{
			if ($item['name'] == $name)
			{
				return $item;
			}
		}
		// throw error?
		return null;
	}

	public function render($name)
	{
		$item = $this->getItem($name);
		switch($item['tag'])
		{
			case \Form::$TAG_INPUT:
				return '<'.$item['tag'].''.Html::expandAttributes($item['attributes']).' type="'.$item['type'].'" name='.$item['name'].' value="'.$item['value'].'"></'.$item['tag'].'>';
			case \Form::$TAG_BUTTON:
				return '<'.$item['tag'].''.Html::expandAttributes($item['attributes']).' type="'.$item['type'].'">'. $item['name'].'</'.$item['tag'].'>';
		}
	}
}

