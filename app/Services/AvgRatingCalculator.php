<?php

namespace App\Services;

use stdClass;

class AvgRatingCalculator
{

    public function calculate($ratings) 
    {
        // Formula for calculating avg rating is: AR = 1 * n of 1-star ratings + 2 * n of 2sr + 3 * n of 3sr + 4 * n of 4sr + 5 * n of 5sr / total number of ratings
        // AR - Average Rating, n of sr = number of given star rating (n of 2sr = number of 2-star ratings)
        $result = new stdClass();

        // This will take the first element which is the object with results and convert it into an array
        $numberOfRatings = json_decode(json_encode($ratings), true);

        $totalRatings = array_sum($numberOfRatings);
        $result->total = $totalRatings;

        // If the number of ratings is 0, so nobody rated the article yet, we just need to return 0
        if ($totalRatings === 0) {

            $result->avg = 0;

        } else {

            $avgRating = (
                (1 * $numberOfRatings['star_1_ratings']) + 
                (2 * $numberOfRatings['star_2_ratings']) + 
                (3 * $numberOfRatings['star_3_ratings']) + 
                (4 * $numberOfRatings['star_4_ratings']) +
                (5 * $numberOfRatings['star_5_ratings'])
             ) 
             / (
                 $numberOfRatings['star_1_ratings'] + 
                 $numberOfRatings['star_2_ratings'] + 
                 $numberOfRatings['star_3_ratings'] + 
                 $numberOfRatings['star_4_ratings'] + 
                 $numberOfRatings['star_5_ratings']
             );

            $result->avg = number_format($avgRating, 2);
        }

        return $result;
    }

}
