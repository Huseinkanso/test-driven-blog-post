<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title=fake()->sentence();
        return [
            'title'=>$title,
            // 'slug'=>Str::slug($title),
            'body'=>fake()->text(),
            'image'=>UploadedFile::fake()->image('photo1.jpg'),
            'user_id'=>1,
        ];
    }
}
