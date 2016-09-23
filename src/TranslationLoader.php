<?php

namespace ClaudioVenturini\Silex;

use Symfony\Component\Translation\Translator;

class TranslationLoader {
	
	/**
	 * @var Translator
	 */
	private $translator;
	
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
	
	public function __construct(Translator $translator, $format, $translationsPath, array $locales, callable $fileNameComposerCallable = null){
		$this->translator = $translator;
		$this->format = $format;
		$this->translationsPath = $translationsPath;
		$this->locales = $locales;
		
		if ($fileNameComposerCallable === null){
			$fileNameComposerCallable = function($domain, $locale, $format){
				switch ($format){
					case 'xliff':
						$format = 'xlf';
						break;
					case 'yaml':
						$format = 'yml';
						break;
				}
				return strtolower($domain.'.'.$locale.'.'.$format);
			};
		}
		
		$this->fileNameComposerCallable = $fileNameComposerCallable;
	}
	
	public function loadDomain($domain, array $locales = null){
		if ($locales === null){
			$locales = $this->locales;
		}
		
		foreach ($locales as $locale){
			$fileNameComposer = $this->fileNameComposerCallable;
			
			$fileName = $fileNameComposer($domain, $locale, $this->format);
			$path = $this->translationsPath.'/'.$fileName;
			
			if (file_exists($path)){
				$this->translator->addResource($this->format, $this->translationsPath.'/'.$fileName, $locale, $domain);
			}
		}
	}
	
}
