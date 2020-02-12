<?php

namespace App\Services;

class DayLongService
{
    /**
     * Adding sunrise and sunset to list
     *
     * @param array $cities
     * @param \DateTime $date
     * @return array
     */
    public function addSunriseSunsetToList(array $cities, \DateTime $date): array
    {
        foreach ($cities as $idx => $city) {
            $tz = $this->getNearestTimezone($city->lat, $city->lng, $city->iso_2);
            if (!$tz) {
                unset($cities[$idx]);
                continue;
            }
            $cityDate = clone $date;
            $cityDate->setTimezone(new \DateTimeZone($tz));
            $city->sunrise = date_sunrise($cityDate->getTimestamp(), SUNFUNCS_RET_STRING, $city->lat, $city->lng, 90, $cityDate->getOffset() / 3600);
            $city->sunset = date_sunset($cityDate->getTimestamp(), SUNFUNCS_RET_STRING, $city->lat, $city->lng, 90, $cityDate->getOffset() / 3600);
        }
        return $cities;
    }

    /**
     * Determine
     *
     * @param float $curLat
     * @param float $curLong
     * @param string $countryCode
     * @return string|null
     */
    private function getNearestTimezone($curLat, $curLong, $countryCode = '')
    {
        $timezoneIds = ($countryCode) ? \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $countryCode)
                                        : \DateTimeZone::listIdentifiers();
    
        if ($timezoneIds && is_array($timezoneIds) && isset($timezoneIds[0])) {
            $timeZone = '';
            $tzDistance = 0;
            if (count($timezoneIds) == 1) {
                return $timezoneIds[0];
            }
            foreach ($timezoneIds as $timezoneId) {
                $timezone = new \DateTimeZone($timezoneId);
                $location = $timezone->getLocation();
                $tzLat = $location['latitude'];
                $tzLong = $location['longitude'];
                $theta = $curLong - $tzLong;
                $distance = (sin(deg2rad($curLat)) * sin(deg2rad($tzLat))) 
                    + (cos(deg2rad($curLat)) * cos(deg2rad($tzLat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));
                if (!$timeZone || $tzDistance > $distance) {
                    $timeZone = $timezoneId;
                    $tzDistance = $distance;
                } 
            }
            return $timeZone;
        }
        return null;
    }
}
