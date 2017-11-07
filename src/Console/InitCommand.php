<?php

namespace Laravel\Envoy\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class InitCommand extends SymfonyCommand
{
    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
		$this
			->setName('init')
			->setDescription('Create a new Envoy file in the current directory.')
			->addArgument('host', InputArgument::REQUIRED, 'The host server to initialize with.')
			->addOption('ask', 'a',null,'ask for envoy config');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {

		$config = null;
        if (file_exists(getcwd().'/Envoy.blade.php')) {
            $this->output->writeln('<error>Envoy file already exists!</error>');

            return;
        }

		if ($this->option('ask')) {
			$repo = $this->ask("Repo: ");
			$release_dir = $this->ask('Remote release directory: ');
			$app_dir = $this->ask('Remote app directory: ');
			$config = "
				<?php
				\$repo = '$repo';
				\$release_dir = '$release_dir';
				\$app_dir = '$app_dir';
				\$release = 'release_'.date('YmdHis');		
				?>
			";
		}

        file_put_contents(getcwd().'/Envoy.blade.php', "
			@servers(['web' => '".$this->input->getArgument('host')."'])
			$config	
			@task('deploy')
				cd /path/to/site
				git pull origin master
			@endtask
		");

        $this->output->writeln('<info>Envoy file created!</info>');
    }
}
