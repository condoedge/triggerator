<?php

namespace Condoedge\Triggerator\Triggers\Usable;

use RegexIterator;
use RecursiveIteratorIterator;
use Illuminate\Database\Eloquent\Model;
use Condoedge\Triggerator\Triggers\Traits\UseSaveTrigger;
use Condoedge\Triggerator\Triggers\AbstractSetupModelTrigger;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class SaveTrigger extends AbstractSetupModelTrigger
{
    static function getName()
    {
        return __('triggerator.save-trigger-name');
    }

    static function getForm(array $params = [])
    {
        return _Rows(
            _Select('triggerator.model')->name('model', false)
                ->options(collect(static::models())->mapWithKeys(fn ($model) => [$model => $model]))
                ->default($params['model'] ?? ''),
        );
    }

    protected static function models()
    {
        $directory = app_path('Models');
        self::loadDirectoryFiles($directory);

        return collect(get_declared_classes())
            ->filter(function($class) {
                $reflectionClass = new \ReflectionClass($class);

                return !$reflectionClass->isAbstract() && $reflectionClass->isSubclassOf(Model::class) && in_array(UseSaveTrigger::class, $reflectionClass->getTraitNames());
            })
            ->mapWithKeys(fn ($class) => [$class => (new $class)->getTable()])
            ->values()
            ->toArray();
    }

    protected static function loadDirectoryFiles($directory)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        $phpFiles = new \RegexIterator($files, '/\.php$/');

        foreach ($phpFiles as $file) {
            try{
                require_once $file->getRealPath();
            } catch (\Throwable $e) {
                continue;
            }
        }
    }
}
