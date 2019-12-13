<?php
namespace app\api\controller;
use Phpml\Classification\KNearestNeighbors;


class Ai{

    public function index(){
        $samples = [[1, 3], [1, 4], [2, 4], [3, 1], [4, 1], [4, 2]];
        $labels = ['a', 'a', 'a', 'b', 'b', 'b'];

        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        echo $classifier->predict([3, 2]);
    }
}