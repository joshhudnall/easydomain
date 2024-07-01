<?php

namespace JoshHudnall\EasyDomain\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Str;
use JoshHudnall\EasyDomain\Enums\DomainComponent;
//use function Laravel\Prompts\multiselect;

// keep

class CreateDomain extends Command implements Isolatable
{
    protected $signature = 'domain:create';
    protected $description = 'Create a domain';

    public function handle(): void
    {
        $domain = null;
        while (!$domain) {
            $path = $this->ask('What is the path to the domain you want to create?');

            if (str_contains($path, '/') && str_contains($path, '\\')) {
                $this->error('Invalid domain path.');
                continue;
            }

            $domain = array_map(
                fn ($item) => str_replace(' ', '_', trim($item)),
                str_contains($path, '/') ? explode('/', $path) : explode('\\', $path)
            );
        }

        $path = str_replace('/', '/Domain/', implode('/', $domain));
        $fullPath = config('easydomain.domain_path') . $path;
        $namespace = str_replace('/', '\\', $path);

        $this->info('Creating new domain: ' . $path);

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0766, true);
        }

        $domainComponents = array_column(DomainComponent::cases(), 'value');

        // Can't use multiselect until Laravel 10
//        $stubs = multiselect(
//            'What stubs should be created?',
//            ['All', ...$domainComponents],
//            [],
//            count($domainComponents) + 1,
//        );
        $stubs = $this->choice(
            'Which domain components do you want to create? (Enter a comma separated list)',
            ['All', ...$domainComponents],
            null,
            null,
            true,
        );

        if (in_array('All', $stubs)) {
            $stubs = $domainComponents;
        }

        foreach ($stubs as $stub) {
            $this->createStub(DomainComponent::from($stub), $fullPath, $namespace);
        }
    }

    private function createStub(DomainComponent $domainComponent, string $path, string $namespace): void
    {
        $stubFile = resource_path('DomainStubs') . '/' . $domainComponent->value . '.stub';
        if (!file_exists($stubFile)) {
            $stubFile = __DIR__ . '/../Stubs/' . $domainComponent->value . '.stub';
        }

        if (file_exists($stubFile)) {
            $componentPath = $path . '/' . Str::plural($domainComponent->value);
            if (!file_exists($componentPath)) {
                mkdir($componentPath, 0766, true);
            }

            $fullNamespace = 'App\\Domain\\' . $namespace . '\\' . Str::plural($domainComponent->value);

            $stub = file_get_contents($stubFile);

            $domain = explode('\\', $namespace);
            $domain = end($domain);

            $stub = str_replace('{namespace}', $fullNamespace, $stub);
            $stub = str_replace('{domain}', $domain, $stub);

            $componentPath = $componentPath . '/' . $domain . $domainComponent->value . '.php';
            file_put_contents($componentPath, $stub);
        }
    }
}
