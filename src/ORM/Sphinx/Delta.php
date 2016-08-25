<?php
namespace Motorway\SearchEngine\ORM\Sphinx;

use \Motorway\SearchEngine\Config\ConfigInterface;

class Delta extends Simple 
{
	protected $writer;

	public function __construct(ConfigInterface $config)
	{
		parent::__construct($config);

		$this->writer = $this->makeWriter($config);
	}

	protected function makeWriter(ConfigInterface $config)
	{
		$source = $config->get('source');
		if (empty($source)) {
			throw new \LogicException("source is empty", 1);
		}
	}
}