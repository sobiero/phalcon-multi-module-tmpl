<?php

namespace Pims\Reporting\Models;

use Phalcon\Mvc\Model;

class ProjectAssessments extends Model
{
	private $id;
	private $description;
	private $projectId;
	private $created;
	private $createdBy;
	private $modified;
	private $modifiedBy;
	private $status;
	private $notes;

    public function initialize()
    {
        $this->setSource("project_assessments");

        $this->hasMany("id", "Pims\Reporting\Models\ProjectAssessmentScores", "projectAssessmentId");
    }

	public function getSource()
    {
        return "project_assessments";
    }

    public function columnMap()
    {        
        return array(
            'id'       => 'id',
            'description' => 'description',
            'project_id' => 'projectId',
            'created' => 'created',
            'created_by' => 'createdBy',
            'modified' => 'modified',
            'modified_by' => 'modifiedBy',
            'status' => 'status',
            'notes' => 'notes'
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDescription($value)
    {        
        $this->description = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setProjectId($value)
    {        
        $this->projectId = $value;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function setCreated($value)
    {        
        $this->created = $value;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreatedBy($value)
    {        
        $this->createdBy = $value;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setModified($value)
    {        
        $this->modified = $value;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModifiedBy($value)
    {        
        $this->modifiedBy = $value;
    }

    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    public function setStatus($value)
    {        
        $this->status = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setNotes($value)
    {        
        $this->notes = $value;
    }

    public function getNotes()
    {
        return $this->notes;
    }
    
}
