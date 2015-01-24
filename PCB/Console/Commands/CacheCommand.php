<?php

/**
 * ---------------------------------
 * PHP CODE BOOSTER
 * ---------------------------------
 *
 * @Author: Sandip Patel
 * @package PHPCodebooster
 * @version 5.0
 * @copyright (c) 2014, Sandip Patel
 **/ 
 namespace PCB\Console\Commands;
 
 use PCB\Console\TemplateMaker;
 use Symfony\Component\Console\Command\Command;
 use Symfony\Component\Console\Input\InputArgument;
 use Symfony\Component\Console\Input\InputInterface;
 use Symfony\Component\Console\Input\InputOption;
 use Symfony\Component\Console\Output\OutputInterface;
 
 /**
 * PCB Console Application COntroller
 * 
 * @package    ControllerCommand
 * @subpackage ControllerCommand
 **/ 
 class CacheCommand extends Command
 {
 	private $container;
 	
 	public function __construct($name = null, $container) {
 		parent::__construct($name);
 		$this->container = $container;
 	}
 	 	
    protected function configure()
    {
        $this
            ->setName('pcb:cache')
            ->setDescription('To clear all framework caches.')
            ->addOption(
		        'clear',
            	null,
        		InputOption::VALUE_OPTIONAL,
		        'clear all the framework cache files.',
            	0,
            	true
            );
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$delete = (bool)$input->getOption('clear');
    	if ( $delete )
    	{    	
    		 // remove cache folder contents
    		 foreach( glob( $this->container->getParameter('pcb.cache_dir'). '/*.*') as $file ) {
			      @unlink($file); 
			 }
			 			 
			 // show grettings
			 $output->writeln("<fg=green>Message: <fg=white>framework cache has been cleared.</fg=white></fg=green>");			 
    	}
    }
}