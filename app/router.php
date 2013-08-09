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
		$this->route = $this->route_raw = $this->c['Request']->getScheme().'/'.($this->c['Request']->isXmlHttpRequest() ? 'Ajax' : '').'/'.ucwords($this->c['Request']->getMethod()).'/'.ltrim($this->c['Request']->getPathInfo(),'/');

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
