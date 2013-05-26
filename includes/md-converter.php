<?php

class MD_Converter {

	private $in_header_section;
	private $in_code_block;
	private $current_section;
	private $current_section_name;
	private $new_lines = array();
	private $link_screenshots;
	private $screenshot_prefix;
	private $screenshot_extension;
	private $magic_quotes_enabled;

	public function MD_Converter( $settings = array() ) {
		$this->reset( $settings );
	}

	private function reset( $settings = array() ) {
		$this->in_header_section = false;
		$this->in_code_block = false;
		$this->new_lines = array();

		$settings = array_merge( self::get_default_settings(), $settings );

		$this->link_screenshots = $settings['link-screenshots'];
		$this->screenshot_prefix = $settings['screenshot-prefix'];
		$this->screenshot_extension = $settings['screenshot-extension'];
		$this->magic_quotes_enabled = $settings['magic-quotes-enabled'];
	}

	private static function get_default_settings() {
		return array(
			'link-screenshots' => true,
			'screenshot-prefix' => '',
			'screenshot-extension' => 'png',
			'magic-quotes-enabled' => get_magic_quotes_gpc(),
		);
	}

	public function convert( $content ) {

		$this->new_lines = array();

		if ( $this->magic_quotes_enabled ) {
			$content = stripslashes( $content );
		}

		$lines = preg_split( "/\r?\n/", $content );

		foreach ( $lines as $line_string ) {
			$matches = array();
			if ( preg_match( '/^===(.+)===$/', $line_string, $matches ) ) {
				$this->in_header_section = true;
				$this->current_section = '_header';
				$this->current_section_name = trim( $matches[1] );
				$section = trim($matches[1]);
				$this->new_lines[] = $section;
				$this->new_lines[] = preg_replace( '/./', '=', $section );
			} elseif ( preg_match( '/^==(.+)==$/', $line_string, $matches ) ) {
				$this->is_section_header = true;
				$this->in_header_section = false;
				$section = trim($matches[1]);
				$this->current_section = strtolower( $section );
				$this->current_section_name = strtolower( $section );
				$this->new_lines[] = $section;
				$this->new_lines[] = preg_replace( '/./', '-', $section );
			} elseif ( preg_match( '/^=(.+)=$/', $line_string, $matches ) ) {
				$this->new_lines[] = '#### ' . trim( $matches[1] ) . ' ####';
			} else {
				self::handle_line( $line_string );
			}
		}

		return implode( "\n", $this->new_lines );
	}

	private function handle_line( $line_string ) {
		$matches = array();
		if ( $this->is_header_line( $line_string ) ) {
			$this->new_lines[] = '* ' . self::format_header_line($line_string);
		} elseif ( $this->link_screenshots
			&& 'screenshots' === $this->current_section
			&& preg_match( '/([0-9]+)\. (.*)/', $line_string, $matches ) ) {

			$number = $matches[1];
			$raw_caption = $matches[2];

			// Replace characters that might interfere with the Markdown
			$caption = str_replace( '*', '&#42;', $raw_caption );
			$caption = str_replace( '[', '&#91;', $caption );
			$caption = str_replace( ']', '&#93;', $caption );
			$caption = str_replace( '"', '&quot;', $caption );

			if ( '' !== $this->new_lines[count($this->new_lines)-1] ) {
				$this->new_lines[] = '';
			}
			$this->new_lines[] = "![$caption]({$this->screenshot_prefix}screenshot-$number.{$this->screenshot_extension} \"$caption\")";
			$this->new_lines[] = '';
			$this->new_lines[] = '*' . str_replace( '*', '&#42;', $caption ) . '*';
		} elseif ( false !== strpos( $line_string, '<cite>' ) ) {
			$replaced = str_replace( '<cite>', '*', $line_string );
			$replaced = str_replace( '</cite>', '*', $replaced );
			$this->new_lines[] = $replaced;
		} elseif( '`' === $line_string ) {
			$this->in_code_block = !$this->in_code_block;
			$this->new_lines[] = '```';
		} elseif( false !== strpos( $line_string, '<?php' ) ) {
			// Go back until we find the beginning of the block
			for ( $i = count( $this->new_lines ) - 1; $i >= 0; $i-- ) {
				if ( 0 === strpos( $this->new_lines[$i], '```' ) ) {
					$this->new_lines[$i] = '```php';
					break;
				}
			}
			$this->new_lines[] = $line_string;
		} else {
			$this->new_lines[] = $line_string;
		}
	}

	private function is_header_line( $line ) {

		// If we're not in the header section, there's no way this is a header line
		if ( !$this->in_header_section ) {
			return false;
		}

		// Possible header lines
		$header_lines = array(
			'contributors',
			'donate link',
			'tags',
			'requires at least',
			'tested up to',
			'stable tag',
			'license',
			'license uri'
		);

		// Loop through possible header lines and check if they match
		foreach ( $header_lines as $header_line ) {
			$regex = '/' . $header_line . ':.*/i';
			if ( 1 === preg_match( $regex, $line ) ) {
				return true;
			}
		}
		return false;
	}

	private function format_header_line( $line ) {
		if ( false !== stripos( $line, 'Tags:' ) ) {
			$tags = preg_split( '/,\s*/', trim( preg_replace( '/Tags:\s*/i', '', $line ) ) );
			$taglinks = array();
			foreach ( $tags as $tag ) {
				$taglinks[] = "[$tag](http://wordpress.org/extend/plugins/tags/$tag)";
			}
			return 'Tags: ' . implode( ', ', $taglinks );
		}
		return $line;
	}
}