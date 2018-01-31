@servers(['localhost' => '127.0.0.1'])

@task('deploy', ['on' => ['localhost']])
    echo RUN DEPLOY
@endtask
