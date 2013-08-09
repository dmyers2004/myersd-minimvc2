<?php
class Router
{
	private $c;

	public $route;
	public $route_raw;
	public $route_matched;

	public function __construct(&$c)
	{
		$this->c = &$c;
	}

	public function route()
	{
		/* build our route URI format - it's a little different then most */
		/* [http|https]/[Ajax|]/[Get|Post|Delete|Put]/uri */
		$this->route = $this->route_raw = ($this->c['Request.extras']['is_https'] ? 'https' : 'http').'/'.($this->c['Request.extras']['is_ajax'] ? 'Ajax' : '').'/'.$this->c['Request.extras']['request'].'/'.$this->c['Request.extras']['uri'];

		/* rewrite dispatch route */
		foreach ($this->c['routes'] as $regexpath => $switchto) {
			if (preg_match($regexpath, $this->route)) {
				/* we got a match */
				$this->route = preg_replace($regexpath, $switchto, $this->route);
				$this->route_matched = $regexpath;
				break;
			}
		}

	}

} /* end router */
