<?php
/**
 * Fieldtype_spotify
 * Spotify embedding
 *
 * @author  Jason Varga <jason@pixelfear.com>
 *
 * @copyright  2013
 * @link       http://pixelfear.com
 */
class Fieldtype_spotify extends Fieldtype
{
    /**
     * Meta data for this fieldtype
     * @var array
     */
    public $meta = array(
        'name'       => 'Spotify',
        'version'    => '1.0',
        'author'     => 'Jason Varga',
        'author_url' => 'http://pixelfear.com'
    );

	public function render()
	{
		$attributes = array(
			'name' => $this->fieldname,
			'id' => $this->field_id,
			'tabindex' => $this->tabindex,
			'value' => HTML::convertSpecialCharacters($this->field_data),
			'class' => 'spotify-input',
			'placeholder' => 'Spotify URI'
		);

		$field = HTML::makeInput('text', $attributes, $this->is_required);
		$response = '<div class="spotify-response"></div>';

		$html = '<div class="spotify-field">' . $field . $response . '</div>';

		return $html;
	}

}