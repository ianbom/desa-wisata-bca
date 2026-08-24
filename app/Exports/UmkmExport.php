<?php

namespace App\Exports;

use App\Models\UmkmSurveyQuestion;
use App\Models\VillageUmkm;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UmkmExport
{
    /** @return array{path: string, filename: string} */
    public function export(): array
    {
        $questions = UmkmSurveyQuestion::query()
            ->where('is_active', true)
            ->orderBy('criteria_code')
            ->orderBy('sort_order')
            ->orderBy('question_number')
            ->orderBy('id')
            ->get(['id', 'question_text']);
        $umkms = VillageUmkm::query()
            ->with([
                'village:id,name,province,city,district,subdistrict',
                'dataCollector:id,name',
                'annualWorkerStats:id,umkm_id,year,dimension,category_value,total_people',
                'surveyAnswers:id,umkm_id,umkm_assessment_question_id,score',
            ])
            ->orderBy('name')
            ->get();

        $headers = [
            'No', 'Nama UMKM', 'Nama Desa', 'Lokasi Desa', 'Nama Pelaku UMKM',
            'Nama Lengkap Badan Usaha', 'Tahun Berdiri', 'Website Perusahaan', 'Alamat Produksi',
            'Kategori Produk', 'Brand', 'Omzet Tahunan', 'Kapasitas Produksi Bulanan',
            'Kendala Saat Ini', 'Sertifikasi', 'Legalitas/Sertifikasi', 'Peserta UMKM',
            'Peserta Kapasitas Produksi', 'Kapasitas Produksi Tahunan', 'Kelayakan Lokasi Pabrik',
            'Instagram', 'Facebook', 'Twitter', 'Website Marketing', 'Profil Ecommerce',
            'Catatan Marketing', 'Catatan Sustainability', 'Bank', 'Nomor Rekening', 'Memiliki QRIS',
            'Provider QRIS', 'Memiliki EDC', 'Provider EDC', 'Memiliki Kartu Kredit',
            'Catatan Banking', 'Pernah Ekspor', 'Negara Tujuan Ekspor', 'Pengumpul Data',
            'Statistik Tenaga Kerja', 'Total Skor',
            ...$questions->pluck('question_text')->all(),
        ];

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data UMKM');
        $sheet->fromArray([$headers], null, 'A1');

        foreach ($umkms as $index => $umkm) {
            $scores = $umkm->surveyAnswers->keyBy('umkm_assessment_question_id');
            $workerStats = $umkm->annualWorkerStats
                ->sortByDesc('year')
                ->map(fn ($stat): string => collect([$stat->year, $stat->dimension, $stat->category_value, $stat->total_people.' orang'])->filter()->implode(' - '))
                ->implode("\n");
            $row = [
                $index + 1,
                $umkm->name,
                $umkm->village?->name,
                collect([$umkm->village?->subdistrict, $umkm->village?->district, $umkm->village?->city, $umkm->village?->province])->filter()->implode(', '),
                $umkm->business_owner_name, $umkm->legal_business_name,
                $umkm->established_year, $umkm->company_website_url, $umkm->production_address,
                $umkm->product_category, $umkm->brand_name, $umkm->annual_revenue,
                $umkm->monthly_production_capacity, $umkm->current_obstacles, $umkm->certifications,
                $umkm->has_business_legality_and_certification, $umkm->is_umkm_participant,
                $umkm->is_production_capacity_participant, $umkm->annual_production_capacity,
                $umkm->factory_location_feasibility, $umkm->instagram_url, $umkm->facebook_url,
                $umkm->twitter_url, $umkm->marketing_website_url, $umkm->ecommerce_profile_url,
                $umkm->marketing_notes, $umkm->sustainability_notes, $umkm->bank_name,
                $umkm->bank_account_number, $this->booleanLabel($umkm->has_qris), $umkm->qris_provider,
                $this->booleanLabel($umkm->has_edc), $umkm->edc_provider,
                $this->booleanLabel($umkm->has_credit_card), $umkm->banking_notes,
                $this->booleanLabel($umkm->has_exported), $umkm->export_destination_countries,
                $umkm->dataCollector?->name ?? $umkm->collector_name,
                $workerStats,
                (float) $umkm->surveyAnswers->sum('score'),
                ...$questions->map(fn ($question): ?float => ($answer = $scores->get($question->id)) ? (float) $answer->score : null)->all(),
            ];
            $sheet->fromArray([$row], null, 'A'.($index + 2));
        }

        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));
        $lastRow = max($sheet->getHighestRow(), 1);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter("A1:{$lastColumn}{$lastRow}");
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0066AE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach (range(1, count($headers)) as $column) {
            $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
        }

        $directory = storage_path('app/exports');
        File::ensureDirectoryExists($directory);
        $filename = 'export-data-umkm-'.now()->format('Ymd-His').'.xlsx';
        $path = $directory.DIRECTORY_SEPARATOR.$filename;
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        return compact('path', 'filename');
    }

    private function booleanLabel(mixed $value): string
    {
        return is_null($value) ? '-' : ((bool) $value ? 'Ya' : 'Tidak');
    }
}
