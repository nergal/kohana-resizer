<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Resizer extends Controller {

	private $empty_png = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';
	protected $cache_dir = '';

	public function before()
	{
	    $this->cache_dir = Kohana::$cache_dir.DIRECTORY_SEPARATOR.'resizer';
	    $this->cache_dir = DOCROOT.'thumbnails/';
	    if ( ! file_exists($this->cache_dir)) {
			mkdir($this->cache_dir, 0755, TRUE);
	    }
	}

	private function _get_cache_path($source_path, $type, $width, $height, $filename, $ext)
	{
		$path = array($this->cache_dir);
		$path[] = $source_path;
		$path[] = "{$type}_{$width}x{$height}";
		$path = implode(DIRECTORY_SEPARATOR, $path);

		if ( ! file_exists($path)) {
			mkdir($path, 0755, TRUE);
		}

		$file = $path.DIRECTORY_SEPARATOR.$filename.'.'.$ext;
		return $file;
	}

	public function action_index($source_path, $type, $width, $height, $filename, $ext)
	{
		$path = array(DOCROOT.'images');
		$path[] = $source_path;
		$path[] = $filename.'.'.$ext;
		$path = implode(DIRECTORY_SEPARATOR, $path);

		$config = Kohana::$config->load('resizer');

		$allow_resize = FALSE;
		foreach ($config->get('allowed_sizes') as $size) {
			if ( ! is_array($size)) {
				$size = explode('x', $size);
			}

			if (intVal($size[0]) == $width) {
				if (intVal($size[1]) == $height) {
					$allow_resize = TRUE;
					break;
				}
			}
		}

		$path = realpath($path);

		if ( ! $path) {
			$path = array(DOCROOT.'uploads');
			$path[] = $source_path;
			$path[] = $filename.'.'.$ext;
			$path = implode(DIRECTORY_SEPARATOR, $path);

			$path = realpath($path);
		}

		if ( ! $path) {
			$path = array(DOCROOT.'thumbnails');
			$path[] = $source_path;
			$path[] = $filename.'.'.$ext;
			$path = implode(DIRECTORY_SEPARATOR, $path);

			$path = realpath($path);
		}

		if ($path AND $allow_resize === TRUE) {
			$cache_file = $this->_get_cache_path($source_path, $type, $width, $height, $filename, $ext);

			if ( ! file_exists($cache_file)) {
				$image = Image::factory($path, 'gd');
				switch ($type) {
					case 'res':
						$image->resize($width, $height, Image::NONE);
						break;
					case 'cropg':
						$image->resize($width, $height, Image::INVERSE);
						break;
					case 'cropr':
						$image->resize($width, $height, Image::INVERSE);
					case 'crop':
						$image->crop($width, $height);
						break;
					default:
						throw new Exception;
				}
				$data = $image->render($ext);
				$image->save($cache_file);
			} else {
				$data = file_get_contents($cache_file);
			}
			$this->response->headers('Content-Type', 'image/'.$ext);
		} else {
			$this->response->headers('Content-Type', 'image/png');
			$data = base64_decode($this->empty_png);
		}

		$this->response->body($data);
	}

}
