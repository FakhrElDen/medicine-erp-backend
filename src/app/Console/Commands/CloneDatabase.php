<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CloneDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables and copy database from remote server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = 'db_dump_live.sql';

        $this->line('downloading database...');
        $status = $this->export($filename);

        if ($status != 0) {
            return;
        }

        $this->line('dropping tables...');
        $status = Artisan::call('db:wipe');

        if ($status != 0) {
            return;
        }

        $this->line('importing database...');
        $this->import($filename);

        exec("rm {$filename}");
    }

    protected function export($filename)
    {
        $tmpfile = tmpfile();
        $credentials_file = $this->getCredentialsFile($tmpfile, 'mysql_remote');

        $command = 'mysqldump'
            . ' --defaults-file=' . $credentials_file
            . ' --no-tablespaces'
            . ' ' . config('database.connections.mysql_remote.database');

        $result_code = null;

        exec($command . " > {$filename}", result_code: $result_code);

        return $result_code;
    }

    protected function import($filename)
    {
        $tmpfile = tmpfile();
        $credentials_file = $this->getCredentialsFile($tmpfile, 'mysql');

        $command = 'mysql'
            . ' --defaults-file=' . $credentials_file
            . ' ' . config('database.connections.mysql.database');

        exec($command . " < {$filename}");
    }

    protected function getCredentialsFile(&$tmpfile, $connection_name)
    {
        $credentials = [
            '[client]',
            "host = '" . config("database.connections.{$connection_name}.host"). "'",
            "port = '" . config("database.connections.{$connection_name}.port"). "'",
            "user = '" . config("database.connections.{$connection_name}.username"). "'",
            "password = '" . config("database.connections.{$connection_name}.password"). "'",
        ];

        fwrite($tmpfile, implode(PHP_EOL, $credentials));

        return stream_get_meta_data($tmpfile)['uri'];
    }
}
