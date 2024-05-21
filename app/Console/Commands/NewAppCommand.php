<?php

namespace App\Console\Commands;

use App\Helpers\Contents;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class NewAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new application';

    protected string $sandbox_path;
    protected string $app_name;
    protected string $app_path;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->app_name = $this->argument('name');
        $this->sandbox_path = base_path('sandbox');
        $this->app_path = base_path('sandbox/' . $this->app_name);

        File::deleteDirectory($this->sandbox_path);
        File::ensureDirectoryExists($this->sandbox_path);


        $this->command(
            command: "composer create-project laravel/laravel " . $this->app_name,
            path: $this->sandbox_path,
        );

        // App key. There's some trouble wihh artisan key:generate, figure out later.
        $new_app_key = 'base64:nhCyfKGNlwpKmkcdHp1sx8RE8tnK74Eb75CL8gjaOcA=';
        $this->commandInAppPath(<<<EOT
        sed -i "s/^APP_KEY=.*/APP_KEY=$new_app_key/" .env
        EOT);

        $this->commandInAppPath('composer require filament/filament:"^3.0-stable" -W');
        $this->commandInAppPath('php artisan filament:install --panels --no-interaction');


        $this->installAutoLogin();
        $this->changeDefaultUserCredentials();
        $this->addRootRedirectToAdminPanel();
        $this->commandInAppPath('php artisan migrate:fresh --seed');
    }

    protected function commandInAppPath(string $command): void
    {
        $this->command(command: $command, path: $this->app_path);
    }

    protected function command($command, $path = null): void
    {
        $pendingProcess = Process::newPendingProcess();

        if ($path) {
            $pendingProcess->path($path);
        }

        $pendingProcess->run(
            command: $command,
            output: function (string $type, string $output) {
                if ($type === 'stderr') {
                    $this->error($output);
                }

                $this->comment($output);
            }
        )->throw();
    }

    private function installAutoLogin(): void
    {
        $destinationFolder = $this->app_path . '/app/Filament/Pages/Auth';

        File::ensureDirectoryExists($destinationFolder);

        Contents::replaceFile('Filament/Login.php', $destinationFolder . '/Login.php');

        Contents::replaceInFile(
            '->login()',
            '->login(\App\Filament\Pages\Auth\Login::class)',
            $this->app_path . '/app/Providers/Filament/AdminPanelProvider.php'
        );
    }

    private function addRootRedirectToAdminPanel()
    {
        $contents = File::get("{$this->app_path}/routes/web.php");

        $newContent = Str::replace(
            search: <<<EOT
            Route::get('/', function () {
                return view('welcome');
            });
            EOT,
            replace: "Route::get('/', fn() => redirect()->route('filament.admin.pages.dashboard'));",
            subject: $contents
        );

        File::put("{$this->app_path}/routes/web.php", $newContent);
    }

    private function changeDefaultUserCredentials(): void
    {
        $contents = File::get("{$this->app_path}/database/seeders/DatabaseSeeder.php");

        $newContent = Str::replace(
            search: 'test@example.com',
            replace: 'test@filamentphp.com',
            subject: $contents
        );

        File::put("{$this->app_path}/database/seeders/DatabaseSeeder.php", $newContent);
    }
}
