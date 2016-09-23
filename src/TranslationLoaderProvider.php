<?php

namespace ClaudioVenturini\Silex\TranslationLoader;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Silex\Application;

class TranslationLoaderProvider implements ServiceProviderInterface {
	
	/**
	 * Resource format (e.g. 'yaml', or 'xliff')
	 *
	 * @var string
	 */
	private $format;
	
	/**
	 * Path to the folder containing translations files
	 *
	 * @var string
	 */
	private $translationsPath;
	
	/**
	 * Array of available locales
	 *
	 * @var string[]
	 */
	private $locales;
	
	/**
	 * Callable to compose the file name of a resource
	 *
	 * @var callable ($domain, $locale, $format)
	 */
	private $fileNameComposerCallable;
	
	public function __construct($format, $translationsPath, array $locales, callable $fileNameComposerCallable = null){
		$this->format = $format;
		$this->translationsPath = $translationsPath;
		$this->locales = $locales;
		$this->fileNameComposerCallable = $fileNameComposerCallable;
	}
	
	public function register(Container $container) {
		$container['translation.loader'] = (function (Application $app) {
			return new TranslationLoader($app['translator'], $this->format, $this->translationsPath, $this->locales);
		});
	}
	
}
