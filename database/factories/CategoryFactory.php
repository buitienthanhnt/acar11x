<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Types\CategoryInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 * https://laravel.com/docs/11.x/eloquent-factories#factory-states
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        return [
            CategoryInterface::NAME => $name,
            CategoryInterface::ACTIVE => true,
            CategoryInterface::ALIAS => Str::snake($name, '-'),
            CategoryInterface::PARENT => 0,
        ];
    }

    /**
     * Configure the model factory.
     * Đăng ký sự kiện gọi cho factory: sau khi được tạo
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Category $category) {
            // ...
        })->afterCreating(function (Category $category) {
            // ...
        });
    }

    /**
     * khai báo hàm có thể gán thuộc tính nhất định cho factory
     * use: $pages = Page::factory()->count(5)->suspended()->make();
     * theo đó sau khi gọi hàm này thì nó sẽ gán thuộc tính trả về cho factory
     */
    public function suspended(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => true,
            ];
        });
    }
}
