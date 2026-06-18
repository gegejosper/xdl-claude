<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippineAddressSeeder extends Seeder
{
    public function run(): void
    {
        $this->seed_regions();
        $this->seed_provinces();
        $this->seed_citymunicipalities();
        $this->seed_barangays();
    }

    private function parse_sql_inserts(string $file_path): array
    {
        $content = file_get_contents($file_path);

        preg_match_all(
            "/INSERT INTO `\w+` VALUES \((.+?)\);/",
            $content,
            $matches
        );

        return $matches[1] ?? [];
    }

    private function seed_regions(): void
    {
        DB::table('regions')->truncate();

        $rows = $this->parse_sql_inserts(database_path('sql/refRegion.sql'));

        $data = [];
        foreach ($rows as $row) {
            $values = $this->parse_values($row);
            $data[] = [
                'psgc_code' => $values[1],
                'reg_desc'  => $values[2],
                'reg_code'  => $values[3],
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('regions')->insert($chunk);
        }

        $this->command->info('Regions seeded: ' . count($data));
    }

    private function seed_provinces(): void
    {
        DB::table('provinces')->truncate();

        $rows = $this->parse_sql_inserts(database_path('sql/refProvince.sql'));

        $data = [];
        foreach ($rows as $row) {
            $values = $this->parse_values($row);
            $data[] = [
                'psgc_code' => $values[1],
                'prov_desc' => $values[2],
                'reg_code'  => $values[3],
                'prov_code' => $values[4],
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('provinces')->insert($chunk);
        }

        $this->command->info('Provinces seeded: ' . count($data));
    }

    private function seed_citymunicipalities(): void
    {
        DB::table('citymunicipalities')->truncate();

        $rows = $this->parse_sql_inserts(database_path('sql/refCitymun.sql'));

        $data = [];
        foreach ($rows as $row) {
            $values = $this->parse_values($row);
            $data[] = [
                'psgc_code'    => $values[1],
                'citymun_desc' => $values[2],
                'reg_desc'     => $values[3],
                'prov_code'    => $values[4],
                'citymun_code' => $values[5],
            ];
        }

        foreach (array_chunk($data, 200) as $chunk) {
            DB::table('citymunicipalities')->insert($chunk);
        }

        $this->command->info('City/Municipalities seeded: ' . count($data));
    }

    private function seed_barangays(): void
    {
        DB::table('barangays')->truncate();

        // ~42k rows — stream line by line, do NOT load entire file into memory
        $file = new \SplFileObject(database_path('sql/refBrgy.sql'));
        $file->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);

        $chunk = [];
        $chunk_size = 500;
        $total = 0;

        while (!$file->eof()) {
            $line = $file->fgets();

            if (!str_starts_with(trim($line), 'INSERT INTO')) {
                continue;
            }

            preg_match("/INSERT INTO `\w+` VALUES \((.+?)\);/", $line, $match);

            if (empty($match[1])) {
                continue;
            }

            $values = $this->parse_values($match[1]);

            $chunk[] = [
                'brgy_code'    => $values[1],
                'brgy_desc'    => $values[2],
                'reg_code'     => $values[3],
                'prov_code'    => $values[4],
                'citymun_code' => $values[5],
            ];

            if (count($chunk) >= $chunk_size) {
                DB::table('barangays')->insert($chunk);
                $total += count($chunk);
                $chunk = [];
            }
        }

        // flush remaining
        if (!empty($chunk)) {
            DB::table('barangays')->insert($chunk);
            $total += count($chunk);
        }

        $this->command->info('Barangays seeded: ' . $total);
    }

    /**
     * Parses a raw SQL values string like: '1', 'foo', 'bar'
     * Returns a 0-indexed array including the auto-increment id at [0].
     */
    private function parse_values(string $raw): array
    {
        // Use str_getcsv — handles quoted commas and escaped quotes
        return str_getcsv($raw, ',', "'");
    }
}