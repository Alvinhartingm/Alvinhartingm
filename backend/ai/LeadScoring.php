<?php
namespace App\AI;

use Phpml\Classification\LogisticRegression;

class LeadScoring
{
    public function score(array $lead): float
    {
        $samples = [[0,0],[0,1],[1,0],[1,1]]; // demo data
        $labels = [0,0,0,1];
        $classifier = new LogisticRegression();
        $classifier->train($samples, $labels);
        return $classifier->predict([$lead['has_budget'], $lead['contacted']]);
    }
}
