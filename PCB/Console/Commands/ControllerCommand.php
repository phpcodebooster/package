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
 class ControllerCommand extends Command
 {
 	private $container;
 	
 	public function __construct($name = null, $container) {
 		parent::__construct($name);
 		$this->container = $container;
 	}
 	
    protected function configure()
    {
        $this
            ->setName('pcb:controller')
            ->setDescription('To create or delete pcb controller class command line.')
            ->addOption(
		        'create',
            	null,
        		InputOption::VALUE_REQUIRED,
		        'Creates the controller and related default view.',
            	0
            )
            ->addOption(
            	'delete',
            	null,
            	InputOption::VALUE_REQUIRED,
            	'Deletes the controller and related views.',
            	0
            )            
        	->addOption(
               'overwrite',
               null,
        		InputOption::VALUE_OPTIONAL,
               'If set, the task will overwrite a controller already exist.'
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
    		 // set template
    		 $controller_loc = $this->container->getParameter('pcb.root_dir'). 'Controllers/' .$create. 'Controller.php';
    		 $templateMaker->setTemplate( 'controller' );
    		 $templateMaker->set('namespace', $create);
    		 $templateMaker->set('controller', $create. 'Controller');
    		 $templateMaker->create( $controller_loc, $overwrite ? true : false );
    		 
    		 // create a view file
    		 $templateMaker->setTemplate( 'view' );
    		 $templateMaker->create( $this->container->getParameter('pcb.root_dir'). 'Views/' .$create. '/index.php' );
    		     		 
    		 // show grettings
    		 $output->writeln("<fg=green>Controller/View: <fg=white>{$create} created successfully.</fg=white></fg=green>");    		 
    	}
    	
    	// delete a controller
    	if ( $delete )
    	{
			 // remove controller
			 @unlink( $this->container->getParameter('pcb.root_dir'). 'Controllers/' .$delete. 'Controller.php' );
			 
    		 // loop though all view files and delete them
    		 foreach( glob( $this->container->getParameter('pcb.root_dir'). 'Views/' .$delete . '/*.*') as $file ) {
			      @unlink($file); 
			 }
			 
			 // remove view folder
			 @rmdir( $this->container->getParameter('pcb.root_dir'). 'Views/' .$delete. '/' );
			 			 
			 // show grettings
			 $output->writeln("<fg=green>Controller/View: <fg=white>{$delete} deleted successfully.</fg=white></fg=green>");			 
    	}
    }
}