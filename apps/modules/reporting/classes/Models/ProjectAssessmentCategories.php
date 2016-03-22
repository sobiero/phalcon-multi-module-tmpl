<?php

namespace Pims\Reporting\Models;

use Phalcon\Mvc\Model;

class ProjectAssessmentCategories extends Model
{
	protected $id;
	protected $name;
	protected $assessmentMatrixId;
	protected $parentId;
	protected $order;

    public function initialize()
    {
        $this->setSource("project_assessment_categories");
        $this->hasMany("id", "Pims\Reporting\Models\ProjectAssessmentItems", "assessmentCategoryId");
    }

    public function columnMap()
    {        
        return array(
            'id'       => 'id',
            'name' => 'name',
            'order' => 'order',
            'assessmentMatrix_id' => 'assessmentMatrixId',
            'parent_id' => 'parentId',
        );
    }

	public function getSource()
    {
        return "project_assessment_categories";
    }

    
    public function getId()
    {
        return $this->id;
    }

    public function setAssessmentMatrixId($value)
    {        
        $this->assessmentMatrixId = $value;
    }

    public function getAssessmentMatrixId()
    {
        return $this->assessmentMatrixId;
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

    public function setParentId($value)
    {        
        $this->parentId = $value;
    }

    public function getParentId()
    {
        return $this->parentId;
    }    


    /**
     * Return the related "assessment items"
     *
     * @return \ProjectAssessmentItems[]
     */
    public function getProjectAssessmentItems($parameters = null)
    {
        return $this->getRelated('Pims\Reporting\Models\ProjectAssessmentItems', $parameters);
    }
    
}
