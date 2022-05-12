<?php

namespace Company\Note\Providers;

use MetaFox\Platform\Support\EloquentModelObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Company\Note\Models\Note;
use Company\Note\Models\NoteText;
use Company\Note\Models\Category;
use Company\Note\Observers\NoteObserver;
use Company\Note\Observers\CategoryObserver;
use Company\Note\Repositories\NoteRepositoryInterface;
use Company\Note\Repositories\CategoryRepositoryInterface;
use Company\Note\Repositories\Eloquent\NoteRepository;
use Company\Note\Repositories\Eloquent\CategoryRepository;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected string $moduleName = 'Note';

    /**
     * @var string
     */
    protected string $moduleNameLower = 'note';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Register relation
         */
        Relation::morphMap([
            Note::ENTITY_TYPE => Note::class,
        ]);

        Note::observe([EloquentModelObserver::class, NoteObserver::class]);
        NoteText::observe([EloquentModelObserver::class]);
        Category::observe([CategoryObserver::class]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        // Boot facades.
    }
}
