
			@servers(['web' => 'thebrace.net'])
			
				<?php
				$repo = 'testing';
				$release_dir = 'testing';
				$app_dir = 'testing';
				$release = 'release_'.date('YmdHis');		
				?>
				
			@task('deploy')
				cd /path/to/site
				git pull origin master
			@endtask
		