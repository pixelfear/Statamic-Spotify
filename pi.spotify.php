<?php
class Plugin_spotify extends Plugin {

	var $meta = array(
		'name'       => 'Spotify',
		'version'    => '1.0',
		'author'     => 'Jason Varga',
		'author_url' => 'http://pixelfear.com'
	);

	public $uri, $uri_segments, $uri_type, $uri_id;
    
	/**
	 * Save URIs
	 */
	private function _setURIs()
	{
		// entire uri
		$this->uri = $this->fetchParam('uri', NULL, NULL, NULL, FALSE);

		// uri segments
		$this->uri_segments = explode(":", $this->uri);
		$last_segment = end($this->uri_segments);
		$second_last_segment = prev($this->uri_segments);

		if ($last_segment == "starred")
		{
			$this->uri_id = $second_last_segment;
			$this->uri_type = $last_segment;
		}
		else
		{
			$this->uri_id = $last_segment;
			$this->uri_type = $second_last_segment;
		}
	}

	private function _buildWidget()
	{
		$width = $this->fetchParam('width', 250);
		$height = $this->fetchParam('height', 330);
		$theme = $this->fetchParam('theme');
		$show_art = $this->fetchParam('show_art', NULL, NULL, TRUE); 

		// ensure not above max dimensions
		$width = ($width > 640) ? 640 : $width;
		$height = ($height > 720) ? 720 : $height;

		// only allow 'white' or 'black' themes
		$is_allowed_color = preg_match('/white|black/i', $theme);
		$theme = ($is_allowed_color) ? $theme : FALSE;

		// build parameters
		$theme = ($theme) ? 'theme='.$theme : FALSE;
		$coverart = ($show_art) ? 'view=coverart' : FALSE;
		$params = implode('&', array_filter(array($theme, $coverart)));
		$params = (!empty($params)) ? '&'.$params : FALSE;
		$query = '?uri=' . $this->uri . $params;

		// output widget
		$widget = '<iframe src="https://embed.spotify.com/'.$query.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowtransparency="true"></iframe>';
		return $widget;
	}

	/**
	 * Play Button Widget
	 */
	public function widget()
	{
		$this->_setURIs();
		return $this->_buildWidget();
	}

	/**
	 * Play Button Widget
	 */
	public function play_button()
	{
		$this->_setURIs();
		return $this->_buildWidget();
	}

	/*
	 * Link
	 */
	public function link()
	{
		$this->_setURIs();

		$url = "http://open.spotify.com/";
		$username = $this->uri_segments[2];

		if ($this->uri_type == "starred") // user's starred playlist?
		{
			$link = $url . 'user/' . $username . '/starred';
		}
		elseif ($this->uri_type == "playlist") // playlist?
		{
			$link = $url . 'user/' . $username . '/' . $this->uri_type . '/' . $this->uri_id;
		}
		else
		{
			$link = $url . $this->uri_type . '/' . $this->uri_id;
		}

		return $link;
	}
}