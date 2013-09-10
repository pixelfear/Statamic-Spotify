<?php

class Hooks_spotify extends Hooks
{
	/**
	* Creates CSS tags to add to the Control Panel's head tag
	*
	* @return string
	*/
	function control_panel__add_to_head()
	{
		if (URL::getCurrent() == '/publish') {
			return $this->css->link('spotify.css');
		}
	}


	/**
	* Creates JavaScript to add to the Control Panel's footer
	*
	* @return string
	*/
	function control_panel__add_to_foot()
	{
		if (URL::getCurrent() == '/publish') {
			$js = $this->js->link('spotify.js');
			$script = $this->js->inline('
				$(function(){
					$(".spotify-input").spotifyField();
					$("body").on("addRow", ".grid", function() {
						$(".spotify-input").spotifyField();
					});
				})();
			');
			return $js . $script;
		}
	}
}