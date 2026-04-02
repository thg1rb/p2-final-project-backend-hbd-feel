<?php

namespace Database\Factories;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $studentId = fake()->unique()->numerify('##########');
        $awardId = fake()->numberBetween(1, 10);
        $timestamp = now()->timestamp;

        $mainPath = "applications/main/{$studentId}_{$awardId}_{$timestamp}.pdf";
        $this->uploadMockFile($mainPath, 'test.pdf');

        $submissionData = [
            "req_001" => [
                "file_path" => "applications/docs/{$studentId}_id_card_{$timestamp}.pdf"
            ],
            "req_002" => [
                "file_path" => "applications/docs/{$studentId}_transcript_{$timestamp}.pdf"
            ]
        ];

        $this->uploadMockFile($submissionData['req_001']['file_path'], 'id_card.pdf');

        $this->uploadMockFile($submissionData['req_002']['file_path'], 'transcript.pdf');

        return [
            'student_id' => null,
            'event_id' => 46,
            'award_id' => null,
            'grade' => fake()->randomFloat(2, 2, 4),
            'path' => $mainPath,
            'documents' => $submissionData,
            'year' => fake()->numberBetween(1, 4),
            'level' => RoleLevel::BOARD,
            'status' => fake()->randomElement(ApprovalStatus::cases())->value,
        ];
    }

    /**
     * * @param string 
     * @param string 
     */
    private function uploadMockFile(string $destination, string $sourceFileName)
    {
        $sourcePath = storage_path("app/mock/{$sourceFileName}");

        if (File::exists($sourcePath)) {
            Storage::disk('s3')->put($destination, file_get_contents($sourcePath));
        } else {
            logger()->warning("Mock file not found: {$sourcePath}");
        }
    }
}
