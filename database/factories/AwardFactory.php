<?php

namespace Database\Factories;

use App\Enums\CampusType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $requirements = [
            [
                "id" => "req_001",
                "name" => "สำเนาบัตรประชาชน",
                "required" => true
            ],
            [
                "id" => "req_002",
                "name" => "Transcript",
                "required" => true
            ],
        ];

        $fileName = "awards/forms/form_" . fake()->uuid() . ".pdf";
        $this->uploadMockFile($fileName);

        return [
            'name' => fake()->words(3, true),
            'form_path' => $fileName,
            'requirements' => $requirements,
            'campus' => fake()->randomElement(CampusType::cases())->value,
        ];
    }

    private function uploadMockFile(string $destination)
    {
        $sourcePath = storage_path('app/mock/test.pdf');

        if (File::exists($sourcePath)) {
            Storage::disk('s3')->put($destination, file_get_contents($sourcePath));
        }
    }
}
