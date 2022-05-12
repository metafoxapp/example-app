<?php

namespace Company\Note\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Company\Note\Models\Category;

/**
 * Class CategoryFactory.
 * @method Category create($attributes = [], ?Model $parent = null)
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => $this->faker->name,
            'is_active' => true,
            'ordering'  => 0,
            'parent_id' => 0,
        ];
    }
}
