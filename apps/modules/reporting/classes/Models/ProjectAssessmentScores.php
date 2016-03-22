<?php

namespace Pims\Reporting\Models;

use Phalcon\Mvc\Model;

class ProjectAssessmentScores extends Model
{
	protected $id;
    protected $projectAssessmentId;
	protected $projectAssessmentItemId;
    protected $projectAssessmentScaleId;
    protected $score;
	protected $comments;
    protected $created;
    protected $createdBy;
    protected $modified;
    protected $modifiedBy;
    protected $status;

    public function initialize()
    {
        $this->setSource("project_assessment_scores");
        $this->belongsTo("projectAssessmentId", "Pims\Reporting\Models\ProjectAssessments", "id");
        $this->belongsTo("projectAssessmentItemId", "Pims\Reporting\Models\ProjectAssessmentItems", "id");
        $this->belongsTo("projectAssessmentScaleId", "Pims\Reporting\Models\ProjectAssessmentScale", "id");
    }

    public function columnMap()
    {        
        return array(
            'id'       => 'id',
            'projectAssessment_id' => 'projectAssessmentId',
            'projectAssessmentItem_id' => 'projectAssessmentItemId',
            'projectAssessmentScale_id' => 'projectAssessmentScaleId',
            'created' => 'created',
            'created_by' => 'createdBy',
            'modified' => 'modified',
            'modified_by' => 'modifiedBy',
            'status' => 'status',
            'score' => 'score',
            'comments' => 'comments'
        );
    }

	public function getSource()
    {
        return "project_assessment_scores";
    }

    public function getId()
    {
        return $this->id;
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

    public function setScore($value)
    {        
        $this->score = $value;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setComments($value)
    {        
        $this->comments = $value;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setProjectAssessmentId($value)
    {        
        $this->projectAssessmentId = $value;
    }

    public function getProjectAssessmentId()
    {
        return $this->projectAssessmentId;
    }

    public function setProjectAssessmentItemId($value)
    {        
        $this->projectAssessmentItemId = $value;
    }

    public function getProjectAssessmentItemId()
    {
        return $this->projectAssessmentItemId;
    }

    public function setProjectAssessmentScaleId($value)
    {        
        $this->projectAssessmentScaleId = $value;
    }

    public function getProjectAssessmentScaleId()
    {
        return $this->projectAssessmentScaleId;
    }
    
    
}