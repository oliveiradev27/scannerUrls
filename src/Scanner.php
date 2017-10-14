<?php
namespace Oreilly\ModernPHP\Url;
 
class Scanner
{
	/** 
     * @var array Um array de URLs
	*/
	protected $urls;
	
	/**
     * @var \GuzzleHttp\Client
	*/
	protected $httpClient;
	
	/**
	 * @param array $urls Um array de URLs para o scan
	*/
	function __construct(array $urls)
	{
		$this->urls = $urls;
		$this->httpClient = new \GuzzleHttp\Client();
	}

	/**
	 * Obtém URLs inválidos
	 * @return array
	*/
	public function getInvalidUrls()
	{
		$invalidUrls = [];
		foreach ($this->urls as $url) {
			try {
				$statusCode = $this->getStatusCodeForUrl($url);
			} catch (\Exception $e) {
				$statusCode = 500;
			}

			if ($statusCode >= 400) {
				array_push($invalidUrls, [
					'url' 	 => $url,
					'status' => $statusCode
				]);
			}
		}
		return $invalidUrls;
	}

	/**
	 * Obtém o código de status HTTP para o URL
	 * @param string $url O URL remoto
	 * @return int O código de status HTTP
	*/
	protected function getStatusCodeForUrl($url)
	{
		$httpResponse = $this->httpClient->options($url);
		return $httpResponse->getStatusCode();
	}
}