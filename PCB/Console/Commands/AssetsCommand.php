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
 * @package    AssetsCommand
 * @subpackage AssetsCommand
 **/ 
 class AssetsCommand extends Command
 {
  	private $container;
 	
 	public function __construct($name = null, $container) {
 		parent::__construct($name);
 		$this->container = $container;
 	}
 	 	
    protected function configure()
    {
        $this
            ->setName('pcb:assets')
            ->setDescription('To create or delete assets classes.')
            ->addOption(
		        'create',
            	null,
        		InputOption::VALUE_REQUIRED,
		        'Creates assets class specified.',
            	0
            )
            ->addOption(
            	'delete',
            	null,
            	InputOption::VALUE_REQUIRED,
            	'Deletes assets class specified.',
            	0
            )            
        	->addOption(
               'overwrite',
               null,
        		InputOption::VALUE_OPTIONAL,
               'If set, the task will overwrite a asset class if already exist.'
            );
    }

    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	// get template maker
    	$templateMaker = new TemplateMaker();
    	
    	// get controller name
    	$overwrite 	= $input->getOption('overwrite');
    	$create     = ucfirst($input->getOption('create'));
    	$delete     = ucfirst($input->getOption('delete'));

    	// create 
    	if ( $create )
    	{
    		 // set template
    		 $templateMaker->setTemplate( 'asset' );
    		 $templateMaker->set('asset', $create);
    		 $templateMaker->create( $this->container->getParameter('pcb.root_dir'). 'Libraries/Assets/' .$create. '.php', $overwrite ? true : false );
    		     		 
    		 // show grettings
    		 $output->writeln("<fg=green>Assets: <fg=white>{$create} created successfully.</fg=white></fg=green>");    		 
    	}
    	
    	// delete 
    	if ( $delete )
    	{
			 // remove controller
			 @unlink( $this->container->getParameter('pcb.root_dir'). 'Libraries/Assets/' .$delete. '.php' );
			 			 
			 // show grettings
			 $output->writeln("<fg=green>Assets: <fg=white>{$delete} deleted successfully.</fg=white></fg=green>");			 
    	}
    }
}