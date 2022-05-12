<?php

namespace Company\Note\Database\Factories;

use MetaFox\Platform\Contracts\User;
use MetaFox\Platform\Support\Factory\HasSetState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Company\Note\Models\Note;

/**
 * Class NoteFactory.
 * @method Note create($attributes = [], ?Model $parent = null)
 * @method NoteFactory setCustomPrivacy(array $list = [])
 */
class NoteFactory extends Factory
{
    use HasSetState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'            => $this->faker->sentence,
            'privacy'          => random_privacy(),
            'module_id'        => 'note',
            'user_id'          => 1,
            'user_type'        => 'user',
            'owner_id'         => 1,
            'owner_type'       => 'user',
            'server_id'        => 0,
            'image_path'       => faker_image_path('note'),
            'text'             => $this->faker->text,
            'text_parsed'      => $this->faker->text,
            'categories'       => [1, 2],
            'is_approved'      => 1,
            'is_draft'         => 0,
            'total_like'       => 0,
            'total_share'      => 0,
            'total_comment'    => 0,
            'total_attachment' => 0,
            'total_view'       => 0,
        ];
    }
}
