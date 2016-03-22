<?php

namespace Pims\Reporting\Models;

use Phalcon\Mvc\Model;

class ProjectAssessmentScale extends Model
{
	protected $id;
	protected $scope;
	protected $lowerLimit;
	protected $upperLimit;

    public function initialize()
    {
        $this->setSource("project_assessment_scale");
        $this->hasMany("id", "Pims\Reporting\Models\ProjectAssessmentScores", "assessmentScaleId");
    }

    public function columnMap()
    {        
        return array(
            'id'       => 'id',
            'scope' => 'scope',
            'lower_limit' => 'lowerLimit',
            'upper_limit' => 'upperLimit'
        );
    }

	public function getSource()
    {
        return "project_assessment_scale";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getLowerLimit()
    {
        return $this->lowerLimit;
    }

    public function getUpperLimit()
    {
        return $this->upperLimit;
    }    
    
}
