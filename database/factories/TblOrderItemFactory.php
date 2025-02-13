<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\TblOrder;
use App\Models\TblOrderItem;

class TblOrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TblOrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => TblOrder::factory(),
            'product_id' => $this->faker->numberBetween(-100000, 100000),
            'quantity' => $this->faker->numberBetween(-100000, 100000),
            'price' => $this->faker->numberBetween(-100000, 100000),
            'total_price' => $this->faker->numberBetween(-100000, 100000),
        ];
    }
}
