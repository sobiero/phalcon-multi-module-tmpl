<?php

namespace Pims\Reporting\Models;

use Phalcon\Mvc\Model;

class ProjectAssessmentItems extends Model
{
	private $id;
	private $name;	
	private $order;
    private $assessmentCategoryId;

    public function initialize()
    {
        $this->setSource("project_assessment_items");

        $this->hasMany("id", "Pims\Reporting\Models\ProjectAssessmentScores", "assessmentItemId");

        $this->belongsTo(
            "assessmentCategoryId", 
            "Pims\Reporting\Models\ProjectAssessmentCategories", 
            "id", 
            array('alias' => 'items')
        );
    }

    public function columnMap()
    {        
        return array(
            'id'       => 'id',
            'name' => 'name',
            'order' => 'order',
            'assessmentCategory_id' => 'assessmentCategoryId'
        );
    }

	public function getSource()
    {
        return "project_assessment_items";
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setName($value)
    {        
        $this->name = $value;
    }    
    
    public function getName()
    {
        return $this->name;
    }

    public function setOrder($value)
    {        
        $this->order = $value;
    }

    public function getOrder()
    {
        return $this->order;
    } 

    public function setAssessmentCategoryId($value)
    {        
        $this->assessmentCategoryId = $value;
    }

    public function getAssessmentCategoryId()
    {
        return $this->assessmentCategoryId;
    }
     
    
}