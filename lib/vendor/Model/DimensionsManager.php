<?php


namespace Model;


use Entity\DimensionsEntity;
use RCFramework\Manager;

class DimensionsManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllDimensions()
    {
        $answerDimensionsData = $this->db->prepare('SELECT dimensions_id, dimensions_dimensions FROM rc_photographe_dimensions');
        $answerDimensionsData->execute();

        $dimensionsFeatures = [];

        $dbDimensions = $answerDimensionsData->fetchAll();
        foreach ($dbDimensions as $dimensions)
        {
            $dimensionsFeatures[] = new DimensionsEntity($dimensions);
        }

        return $dimensionsFeatures
    }

    public function saveDimensions(DimensionsEntity $newDimensionsEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_dimensions(dimensions_dimensions) VALUES (:dimensions_dimensions)');
        $req->execute(array(
            'dimensions_dimensions' => $newDimensionsEntity->dimensions()
        ));
    }

    public function deleteDimensions($dimensionsId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_dimensions WHERE dimensions_id=:dimensionsId');
        $req->bindValue('dimensionsId',$dimensionsId, \PDO::PARAM_INT);
        $req->execute();
    }
}