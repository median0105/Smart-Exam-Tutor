<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Tryout;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Smart Exam Tutor',
            'email' => 'admin@smart-exam-tutor.test',
            'role' => User::ROLE_ADMIN,
            'student_code' => null,
        ]);

        $student = User::factory()->create([
            'name' => 'Nadia Siswa',
            'email' => 'siswa@smart-exam-tutor.test',
            'role' => User::ROLE_STUDENT,
            'student_code' => 'STD-2026-001',
            'learning_level' => 'Menengah',
        ]);

        $student->profile()->create([
            'school' => 'SMA Cerdas Nusantara',
            'grade_level' => 'Kelas 11',
            'target_exam' => 'UTBK',
            'average_score' => 72.5,
            'total_attempts' => 2,
        ]);

        $math = Subject::create([
            'name' => 'Matematika',
            'slug' => 'matematika',
            'code' => 'MTK',
            'description' => 'Persiapan try out matematika adaptif.',
            'color' => 'from-blue-500 to-violet-500',
            'is_active' => true,
        ]);

        $linear = Topic::create([
            'subject_id' => $math->id,
            'name' => 'Persamaan Linear',
            'slug' => 'persamaan-linear',
            'description' => 'Konsep, bentuk, dan strategi menyelesaikan persamaan linear.',
            'target_mastery_percentage' => 80,
        ]);

        $probability = Topic::create([
            'subject_id' => $math->id,
            'name' => 'Peluang',
            'slug' => 'peluang',
            'description' => 'Konsep dasar peluang dan penerapannya.',
            'target_mastery_percentage' => 78,
        ]);

        LearningMaterial::create([
            'subject_id' => $math->id,
            'topic_id' => $linear->id,
            'title' => 'Ringkasan Persamaan Linear',
            'slug' => 'ringkasan-persamaan-linear',
            'summary' => 'Materi ringkas untuk memahami bentuk umum dan teknik eliminasi.',
            'content' => 'Materi ini membahas konsep bentuk umum persamaan linear, langkah eliminasi, dan contoh soal bertahap.',
            'tags' => ['linear', 'aljabar', 'persamaan'],
            'difficulty' => 'easy',
            'estimated_minutes' => 20,
            'is_published' => true,
        ]);

        LearningMaterial::create([
            'subject_id' => $math->id,
            'topic_id' => $probability->id,
            'title' => 'Peluang Dasar dan Latihan Kontekstual',
            'slug' => 'peluang-dasar-dan-latihan-kontekstual',
            'summary' => 'Pemahaman ruang sampel, kejadian, dan peluang sederhana.',
            'content' => 'Materi ini membahas ruang sampel, aturan penjumlahan, dan peluang kejadian sederhana melalui latihan kontekstual.',
            'tags' => ['peluang', 'statistika'],
            'difficulty' => 'medium',
            'estimated_minutes' => 25,
            'is_published' => true,
        ]);

        $questionOne = Question::create([
            'subject_id' => $math->id,
            'topic_id' => $linear->id,
            'body' => 'Jika 2x + 6 = 18, nilai x adalah ...',
            'explanation' => 'Kurangi kedua ruas dengan 6, lalu bagi 2 sehingga x = 6.',
            'difficulty' => 'easy',
            'points' => 5,
            'tags' => ['linear'],
            'is_active' => true,
        ]);

        $questionOne->options()->createMany([
            ['option_label' => 'A', 'content' => '4', 'is_correct' => false, 'feedback' => 'Periksa kembali saat membagi.'],
            ['option_label' => 'B', 'content' => '5', 'is_correct' => false, 'feedback' => 'Masih kurang satu langkah.'],
            ['option_label' => 'C', 'content' => '6', 'is_correct' => true, 'feedback' => 'Tepat.'],
            ['option_label' => 'D', 'content' => '7', 'is_correct' => false, 'feedback' => 'Coba substitusi kembali.'],
        ]);

        $questionTwo = Question::create([
            'subject_id' => $math->id,
            'topic_id' => $probability->id,
            'body' => 'Sebuah koin dilempar sekali. Peluang muncul angka adalah ...',
            'explanation' => 'Ruang sampel terdiri dari dua kemungkinan setara, sehingga peluang angka adalah 1/2.',
            'difficulty' => 'easy',
            'points' => 5,
            'tags' => ['peluang'],
            'is_active' => true,
        ]);

        $questionTwo->options()->createMany([
            ['option_label' => 'A', 'content' => '1/4', 'is_correct' => false, 'feedback' => 'Terlalu kecil untuk satu lemparan.'],
            ['option_label' => 'B', 'content' => '1/3', 'is_correct' => false, 'feedback' => 'Ruang sampelnya hanya dua.'],
            ['option_label' => 'C', 'content' => '1/2', 'is_correct' => true, 'feedback' => 'Tepat.'],
            ['option_label' => 'D', 'content' => '1', 'is_correct' => false, 'feedback' => 'Tidak selalu muncul angka.'],
        ]);

        $tryout = Tryout::create([
            'subject_id' => $math->id,
            'title' => 'Try Out Diagnostik Matematika',
            'slug' => Str::slug('Try Out Diagnostik Matematika'),
            'description' => 'Try out awal untuk membaca level kemampuan dan kelemahan topik siswa.',
            'duration_minutes' => 30,
            'question_count' => 2,
            'difficulty_mix' => 'foundation',
            'is_published' => true,
        ]);

        $tryout->questions()->attach([
            $questionOne->id => ['position' => 1],
            $questionTwo->id => ['position' => 2],
        ]);
    }
}
