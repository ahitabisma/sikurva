<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Http/Services/{$name}.php");

        if (file_exists($path)) {
            $this->error("Service {$name} already exists!");
            return;
        }

        // Buat folder jika belum ada
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Services'));

        // Template service
        $stub = <<<PHP
<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class {$name}
{

    public function __construct()
    {
       
    }

    public function getAll()
    {
        return \$this->->all();
    }

    public function getById(\$id)
    {
        try {
            return \$this->->find(\$id);
        } catch (ModelNotFoundException \$e) {
            Log::error("{$name} not found: " . \$e->getMessage());
            throw new ModelNotFoundException("Data not found");
        }
    }

    public function create(array \$data)
    {
        return \$this->->create(\$data);
    }

    public function update(\$id, array \$data)
    {
        return \$this->->update(\$id, \$data);
    }

    public function delete(\$id)
    {
        return \$this->->delete(\$id);
    }
}

PHP;

        file_put_contents($path, $stub);

        $this->info("INFO  Console command [{$path}] created successfully.");
    }
}
