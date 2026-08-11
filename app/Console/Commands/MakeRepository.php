<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new repository class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Http/Repositories/{$name}.php");

        if (file_exists($path)) {
            $this->error("Repository {$name} already exists!");
            return;
        }

        // Buat folder jika belum ada
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Repositories'));

        // Template repository
        $stub = <<<PHP
<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

class {$name}
{
    public function all()
    {
        // Implementasi mengambil semua data
    }

    public function find(\$id)
    {
        // Implementasi mencari data berdasarkan ID
    }

    public function create(array \$data)
    {
        // Implementasi membuat data baru
    }

    public function update(\$id, array \$data)
    {
        // Implementasi mengupdate data
    }

    public function delete(\$id)
    {
        // Implementasi menghapus data
    }
}

PHP;

        file_put_contents($path, $stub);

        $this->info("INFO  Console command [{$path}] created successfully.");
    }
}
