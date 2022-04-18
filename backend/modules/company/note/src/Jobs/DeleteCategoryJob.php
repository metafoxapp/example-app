<?php

namespace Company\Note\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Company\Note\Models\Note;
use Company\Note\Models\Category;
use Company\Note\Repositories\CategoryRepositoryInterface;
use Company\Note\Repositories\Eloquent\CategoryRepository;

/**
 * Class DeleteCategoryJob.
 */
class DeleteCategoryJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Category $category;

    protected int $newCategoryId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Category $category, int $newCategoryId)
    {
        $this->category = $category;
        $this->newCategoryId = $newCategoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $categoryRepository = resolve(CategoryRepositoryInterface::class);
        $categoryRepository->deleteOrMoveToNewCategory($this->category, $this->newCategoryId);
    }
}
