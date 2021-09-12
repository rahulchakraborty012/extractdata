<?php
// Class to extract the data from the url
class Extract{

	public $url='';
	public function __construct($url){
		$this->url=$url;
	}

	// Method to extract Data
	public function extracData(){
		$pageContent = file_get_contents($this->url);
		$output = array();
		libxml_use_internal_errors(true);
		$dom = new DomDocument();
		try{
			$dom->loadHTML($pageContent);	
			libxml_clear_errors();		
			foreach ($dom->getElementsByTagName('a') as $item) {
			   $output[] = array (
			      'str' => $dom->saveHTML($item),
			      'href' => $item->getAttribute('href'),
			      'anchorText' => $item->nodeValue
			   );
			}
		} 
		catch(Exception $ex){
			echo "Error in extraction-".$ex->getMessage();
			die;
		}	
		return $output;	
	}

	// Method to Display Table
	public function dispalyTable($data){

		$html = '<table border="1">'
					.'<tr>'
						.'<th>Article Title</th>'
						.'<th>Article Url</th>'
					.'</tr>';
				
		if(!empty($data)){
			foreach($data as $d){
				if($d['anchorText']!='')
					$html.='<tr><td>'.$d['anchorText'].'</td><td>'.$d['href'].'</td></tr>';
			}
		}	
		else{
			$html.='<tr><td colspan="2">No data found.</td></tr>';
		}		
		$html .= '</table>';	
		return $html;
	}
}

//Calling the Class method
$extractObject = new Extract('https://www.geeksforgeeks.org');

//Extracting the data from the url
$resultObject = $extractObject->extracData();

// Printing the result in the tabular format
$resultTable = $extractObject->dispalyTable($resultObject);

echo $resultTable;
die;