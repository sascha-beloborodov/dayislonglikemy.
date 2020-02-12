<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CityRepository
{
    use FullTextSearch;

    private $db;
    private static $table = 'cities';

    public function __construct(DB $database)
    {   
        $this->db = $database;
    }

    /**
     * Search records by name
     *
     * @param string $searchQuery
     * @return Builder
     */    
    public function findByName(string $searchQuery = null): Builder
    {
        $query = $this->db::table(self::$table);
        if ($searchQuery) {
            $query = $query->whereRaw("MATCH (name) AGAINST (? IN BOOLEAN MODE)" , $this->fullTextWildcards($searchQuery));
        }
        return $query;
    }
}
