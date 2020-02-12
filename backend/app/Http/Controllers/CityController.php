<?php

namespace App\Http\Controllers;

use App\Repositories\CityRepository;
use App\Services\DayLongService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CityController extends Controller
{
    private $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCities(Request $req, DayLongService $dService)
    {
        try {
            $date = $req->get('date', date('Y-m-d H:i:s'));
            $cities = $this->repository->findByName($req->get('q'))->paginate($req->get('perPage', 10));
            $cities->setCollection(
                new Collection( $dService->addSunriseSunsetToList( $cities->items(), new \DateTime($date) ) )
            );
            return response()->json([
                'cities' => $cities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
