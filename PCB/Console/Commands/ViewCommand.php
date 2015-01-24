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
 class ViewCommand extends Command
 {
 	private $container;
 	
 	public function __construct($name = null, $container) {
 		parent::__construct($name);
 		$this->container = $container;
 	}
 	 	
    protected function configure()
    {
        $this
            ->setName('pcb:view')
            ->setDescription('To create or delete specified pcb view.')
            ->addOption(
		        'create',
            	null,
        		InputOption::VALUE_REQUIRED,
		        'Creates the view specified.',
            	0
            )
            ->addOption(
            	'delete',
            	null,
            	InputOption::VALUE_REQUIRED,
            	'Deletes the view specified',
            	0
            )            
        	->addOption(
               'overwrite',
               null,
        		InputOption::VALUE_OPTIONAL,
               'If set, the task will overwrite a view already exist.'
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

    	// create a controller
    	if ( $create )
    	{
    		 // create a view file
    		 $templateMaker->setTemplate( 'view' );
    		 $templateMaker->create( $this->container->getParameter('pcb.root_dir'). 'Views/' .$create. '.php' );
    		     		 
    		 // show grettings
    		 $output->writeln("<fg=green>View: <fg=white>{$create} created successfully.</fg=white></fg=green>");    		 
    	}
    	
    	// delete a controller
    	if ( $delete )
    	{
			 // remove view folder
			 @unlink( $this->container->getParameter('pcb.root_dir'). 'Views/' .$delete. '.php' );
			 			 
			 // show grettings
			 $output->writeln("<fg=green>View: <fg=white>{$delete} deleted successfully.</fg=white></fg=green>");			 
    	}
    }
}