
[section id="" class="" style=""][/section]

[section_end]

<?php
/**
* Defines section shotcode to output <section> HTML tag in Gutenberg
* [section id="" class="" style=""][/section]
*/
function machete_section_func( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'id' => '',
		'class' => '',
		'style' => '',
	), $atts );

	if ( ! empty ( $a['id'] ) ) {
		$id = ' id="' . $a['id'] . '"';
	} else {
		$id = '';
	}

	if ( ! empty ( $a['class'] ) ) {
		$class = ' class="' . $a['class'] . '"';
	} else {
		$class = '';
	}
	if ( ! empty ( $a['style'] ) ) {
		$style = ' style="' . $a['style'] . '"';
	} else {
		$style = '';
	}

	return '<section' . $id . $class . $style . '>' . $content . '</section>';
}
add_shortcode( 'section', 'machete_section_func' );