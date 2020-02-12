<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

Class BasicImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:first';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First 15k cities';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting import ...');
        if (($handle = fopen(storage_path('app/worldcities.csv'), "r")) !== false) {
            $row = 0;
            $bulkData = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $row++;
                if ($row === 1) {
                    continue;
                }
                if ($row % 200 === 0) {
                    if (!count($bulkData)) {
                        continue;
                    }
                    $this->info('200 inserted');
                    DB::table('cities')->insert($bulkData);
                    $bulkData = [];
                }
                $bulkData[] = [
                    'name' => $data[1] ?? 'none',
                    'name_utf' => $data[0] ?? 'none',
                    'lng' => (float) str_replace(',', '.', $data[3]) ?? 0,
                    'lat' => (float) str_replace(',', '.', $data[2]) ?? 0,
                    'country_name' => $data[4] ?? null,
                    'region_name' => $data[7] ?? null,
                    'iso_2' => $data[5] ?? null,
                    'iso_3' => $data[6] ?? null,
                    'capital' => $data[8] && $data[8] === 'primary' ? true : false,
                    'population' => (int) $data[9],
                    'outer_id' => (int) $data[10],
                    'outer_source' => 'https://simplemaps.com/data/world-cities',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            fclose($handle);
        }
        if (count($bulkData)) {
            $this->info('Rest of rows inserted');
            DB::table('cities')->insert($bulkData);
        }
        $this->info('Completed!');
    }
}