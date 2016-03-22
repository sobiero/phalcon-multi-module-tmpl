<?php

namespace Pims\Reporting\Controllers;

use Pims\Common\Controllers\BaseController;
use Pims\Common\Models\Projects;
use Pims\Reporting\Models\ProjectAssessments;
use Pims\Reporting\Models\ProjectAssessmentCategories;
use Pims\Reporting\Models\ProjectAssessmentItems;
use Pims\Reporting\Models\ProjectAssessmentScale;
use Pims\Reporting\Models\ProjectAssessmentScores;
use Pims\Reporting\Forms\ScoreForm;
use Pims\Reporting\Forms\ReportForm;
use Pims\Reporting\Services\ReportService;



class ReportController extends BaseController
{
	private $reportService;

    public function initialize()
    {
        $this->reportService = new ReportService();
    }

	public function indexAction()
	{
		//$this->view->disable();
		//echo ' Welcome Reporting Module Controller Home Action Index ';
		//echo '<br>', __METHOD__;
		
	}

	public function listAction($projectID)
	{
		// Add some local CSS resources
        $this->assets
            ->addCss('plugins/datatables/dataTables.bootstrap.css');

        // And some local JavaScript resources
        $this->assets
            ->addJs('plugins/datatables/jquery.dataTables.min.js')
            ->addJs('plugins/datatables/dataTables.bootstrap.min.js'); 

		$this->view->projectID = $projectID;

		$this->view->project = Projects::findFirst($projectID);
		
		$data = $this->reportService->listReports($projectID);
		
		$json = json_encode($data->toArray());

		$custom_js = array(
			'partial'=>'project_reports_javascript', 
			'params'=>array('data'=>$json));

		$this->view->form = new ReportForm();		

		$this->addJavaScript($custom_js);
	}

	public function detailAction($id)
	{		
		$report = ProjectAssessments::findFirst($id);	
		$project = Projects::findFirst($report->projectId);
		
		$results = $this->reportService->reportDetail($id);

		$this->view->detailID = $id;
		$this->view->details = $results['data'];
		$this->view->report = $report;
		$this->view->project = $project;
		$this->view->totalScores = $results['totalScores'];
	
		$this->view->form = new ScoreForm();

		$custom_js = [
			'partial'=>'report_detail_javascript', 
			'params'=>['detailID'=>$id]
		];
		

		$this->addJavaScript($custom_js);
	}


	public function scoreAction($id)
	{
		$this->view->disable();

		if ($this->request->isPost()) 
		{
            // Get the data from the from
            $scoreId = $this->request->getPost('scoreId');
            $itemId = $this->request->getPost('itemId');
            $marks    = $this->request->getPost('score');
            $comments = $this->request->getPost('comment');            
            
            $date = date('Y-m-d H:i:s');
            $userId = $this->session->get('auth')['id'];
            
            $scaleId = 0;
            
			$scale = ProjectAssessmentScale::find();

			foreach($scale as $s)
			{
				if($marks >= $s->lowerLimit && $marks <= $s->upperLimit)
					$scaleId = $s->id;				
			}

			if($scoreId != "")
			{
				$score = ProjectAssessmentScores::findFirst($scoreId);
				$score->modified = $date;
            	$score->modifiedBy = $userId;
			}
			else
			{
				$score = new ProjectAssessmentScores();
				$score->created = $date;
            	$score->createdBy = $userId;
            }

            $score->comments = $comments;
            $score->score = $marks;            
            $score->status = "Current";
            $score->projectAssessmentId = $id;
            $score->projectAssessmentItemId = $itemId;
            $score->projectAssessmentScaleId = $scaleId;


            $score->save();

            if (!$score->save())                
                print_r($score->getMessages());            
            else 
                print "score added successfully";        	
        }		
	}

	public function newAction($id)
	{		

		if ($this->request->isPost()) 
		{
            // Get the data from the from
            $desc    = $this->request->getPost('desc');
            $notes = $this->request->getPost('notes');            
            $created = date('Y-m-d H:i:s');
            $createdBy = 902;

            $project = new ProjectAssessments();
            $project->description = $desc;
            $project->notes = $notes;
            $project->created = $created;
            $project->createdBy = $createdBy;
            $project->projectId = $id;
            $project->status = "Draft";

            $project->save();

            if (!$project->save()) 
                $this->flash->error($project->getMessages());           
            else 
                $this->response->redirect("reporting/report/list/".$id);
            
        	$this->view->disable();
        }
        
	}

	public function itemAction($detailId, $itemId)
	{
		$this->view->disable();

		$obj = new \stdClass;

		$obj->itemName = "";
		$obj->itemScoreId = 0;
		$obj->itemScore = "0";
		$obj->itemComments = "";

		//retrieve project assessment item
		$item = ProjectAssessmentItems::findFirst($itemId);
		$obj->itemName = $item->name;

		//retrieve project assessment scores		
		$score = ProjectAssessmentScores::findFirst(
		    [
		        "projectAssessmentId = :projectAssessmentId: AND 
		         projectAssessmentItemId = :projectAssessmentItemId:",
		        "bind" => [
		        	"projectAssessmentId" => $detailId, 
		        	"projectAssessmentItemId" => $itemId
		        ]
		    ]			    
		);

		if($score){
			$obj->itemScoreId = $score->id;
			$obj->itemScore = $score->score;
			$obj->itemComments = $score->comments;
		}	

		$json = json_encode($obj);

		print $json;

	}

	public function submitAction($id)
	{
		$report = ProjectAssessments::findFirst($id);

        $report->status = "Final";

        $report->save();

        if (!$report->save()) 
        	$this->flash->error($project->getMessages());
        else 
           $this->response->redirect("reporting/report/detail/".$id);        

        $this->view->disable();
	}

	public function openAction($id)
	{
		$report = ProjectAssessments::findFirst($id);

        $report->status = "Draft";

        $report->save();

        if (!$report->save()) 
        	$this->flash->error($project->getMessages());
        else 
           $this->response->redirect("reporting/report/detail/".$id);        

        $this->view->disable();
	}

}
