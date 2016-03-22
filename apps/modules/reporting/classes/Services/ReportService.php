<?php
namespace Pims\Reporting\Services;

use Pims\Reporting\Models\ProjectAssessments;
use Pims\Reporting\Models\ProjectAssessmentCategories;
use Pims\Reporting\Models\ProjectAssessmentItems;
use Pims\Reporting\Models\ProjectAssessmentScale;
use Pims\Reporting\Models\ProjectAssessmentScores;


class ReportService extends BaseService
{
	public function listReports($projectID)
	{		
		// Perform the query
		$data = ProjectAssessments::find(
			[
		    	"projectId = :projectID:",
		   		"bind" => [
		   			"projectID" => $projectID
		   		]		    
			]
		);

		return $data;
	}

	public function reportDetail($id)
	{
		$totalScores = 0;

		$data = [];
		
		// Perform the query
		$categories = ProjectAssessmentCategories::find(
		    [
		        "assessmentMatrixId = :assessmentMatrixID:",
		        "bind" => [
		        	"assessmentMatrixID" => 3
		        ]
		    ]
		);
		
		foreach($categories as $category)
		{
			$total = new \stdClass;

			$total->name = "Total Scoring";
			$total->score = 0; 

			
			$row = new \stdClass;
			$row->category = $category;


			$rowItems = array();

			$items = $category->getProjectAssessmentItems();

			foreach($items as $item)
			{
				$itemRow = new \stdClass;
				$itemRow->item = $item;
				$itemRow->score = '';
				$itemRow->scoreID = '';
				$itemRow->comments = '';
				$itemRow->scoreStatus = '';
				
				// get item score				
				$score = ProjectAssessmentScores::findFirst(
				    [
				        "projectAssessmentId = :projectAssessmentId: AND 
				         projectAssessmentItemId = :projectAssessmentItemId:",
				        "bind" => [
				        	"projectAssessmentId" => $id, 
				        	"projectAssessmentItemId" => $item->id
				        ]
				    ]			    
				);

				if($score)
				{
					$total->score += $score->score;

					$itemRow->scoreID = $score->id;
					$itemRow->score = $score->score;
					$itemRow->comments = $score->comments;

					$status = ProjectAssessmentScale::findFirst($score->projectAssessmentScaleId);

					$itemRow->scoreStatus = $status->scope;
				}				

				array_push($rowItems, $itemRow);
			}
			
			$row->items = $rowItems;
			
			$row->total = $total;

			$totalScores += $total->score;

			array_push($data, $row);
		}

		return [
			'data'=>$data,
			'totalScores'=>$totalScores
		];
	}


}