<?php

use App\Exports\UmkmExport;
use App\Models\SurveyTemplate;
use App\Models\TourismVillage;
use App\Models\UmkmSurveyAnswer;
use App\Models\UmkmSurveyQuestion;
use App\Models\User;
use App\Models\VillageUmkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;

uses(RefreshDatabase::class);

test('umkm export includes every umkm, worker statistics, total score, and question scores', function () {
    $user = User::factory()->create();
    $template = SurveyTemplate::factory()->create(['created_by' => $user->id, 'type' => 'umkm']);
    $question = UmkmSurveyQuestion::query()->create([
        'survey_template_id' => $template->id,
        'criteria_code' => 'C1',
        'criteria_name' => 'Kriteria Export',
        'criteria_weight_percent' => 100,
        'question_text' => 'Pertanyaan Export UMKM',
        'question_number' => 1,
        'question_weight_percent' => 100,
        'max_score' => 5,
        'sort_order' => 1,
        'is_active' => true,
    ]);
    $village = TourismVillage::factory()->create(['created_by' => $user->id]);
    $umkm = VillageUmkm::query()->create(['village_id' => $village->id, 'created_by' => $user->id, 'name' => 'UMKM Export']);
    UmkmSurveyAnswer::query()->create(['umkm_id' => $umkm->id, 'umkm_assessment_question_id' => $question->id, 'answered_by' => $user->id, 'score' => 4]);
    $umkm->annualWorkerStats()->create(['entity_type' => 'umkm', 'entity_key' => 'umkm-'.$umkm->id, 'year' => 2025, 'dimension' => 'Jenis Kelamin', 'category_value' => 'Laki-laki', 'total_people' => 7, 'created_by' => $user->id]);

    $file = app(UmkmExport::class)->export();
    $rows = IOFactory::load($file['path'])->getActiveSheet()->toArray();

    $workerColumn = array_search('Statistik Tenaga Kerja', $rows[0], true);
    $totalColumn = array_search('Total Skor', $rows[0], true);
    $questionColumn = array_search('Pertanyaan Export UMKM', $rows[0], true);

    expect($rows[0])->toContain('Statistik Tenaga Kerja', 'Total Skor', 'Pertanyaan Export UMKM')
        ->and($rows[0][1])->toBe('Nama UMKM')
        ->and($rows[0][2])->toBe('Nama Desa')
        ->and($rows[1])->toContain('UMKM Export')
        ->and($rows[1][$workerColumn])->toContain('7 orang')
        ->and($rows[1][$totalColumn])->toEqual(4)
        ->and($rows[1][$questionColumn])->toEqual(4);

    @unlink($file['path']);
});

test('viewer can download all umkm export', function () {
    $viewer = User::factory()->create(['role' => 'viewer']);

    $this->actingAs($viewer)
        ->get(route('umkm.export'))
        ->assertOk()
        ->assertDownload();
});
