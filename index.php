<?php


$obj = new main();
class main {
	public $hostname = "sql1.njit.edu";
	private $username = "rrs63";
	private $password = "6hGttGyh";
	private $conn = NULL;
    private $html = '';

    public function __construct() {
    	$this->html  = '<html>';
        $this->html .= '<link rel="stylesheet" href="styles.css">';
        $this->html .= '<body>';        
    	$this->createConnection();
    	$results = $this->selectQuery('SELECT * FROM accounts where id<6');
    	$this->displayNumberOfRows($results);
    	$this->html .= "<div><u> Records in accounts table with user id < 6 :</u></div><br>";    	
    	$this->html .= '<table style="border: 1px solid black;border-collapse: collapse;">';
    	$numOfColumns = $this->displayHeader('SHOW COLUMNS FROM accounts');
    	$this->displayRecords($results,$numOfColumns);
    }

    public function __destruct() {
    	$this->html .= '</table>';	
        $this->html .= '</body></html>';        
        echo $this->html;
    }

    private function createConnection() {
    	try 
		{
		    $this->conn = new PDO("mysql:host=$this->hostname;dbname=rrs63",
		    $this->username, $this->password);
		    $this->html .= "<b>Connected successfully</b><br><br>";
		}
		catch(PDOException $e)
		{
			//echo "Connection failed: " . $e->getMessage()."<br><br>";
			http_error("500 Internal Server Error\n\n"."There was a SQL error:\n\n" . $e->getMessage())."<br><br>";
		}
    }


     private function selectQuery($query) {
    	$statement = $this->conn->prepare($query);
		$statement->execute();

		$results = $statement->fetchAll();
		$statement->closeCursor();
		return $results;
    } 

    private function displayNumberOfRows($results) {    	
		$this->html .= "<i>Total number of records in output : </i><b>".count($results)."</b><br><br>";
    }
 
 	private function displayHeader($query) { 		
 		$columnNames = $this->conn->prepare($query); 
    	$columnNames->execute();
    	$results = $columnNames->fetchAll();    
    	$this->html .= '<tr style="border: 1px solid black;border-collapse: collapse;">';	
    	for($x=0;$x<count($results);$x++) {
    		$this->html .='<th style="border: 1px solid black;border-collapse: collapse;">'.$results[$x][Field].'</th>';   	
    	}   
    	$this->html .= '</tr>';
    	return count($results);
 	}

    private function displayRecords($results,$numOfColumns) {     	
        
        if(count($results) > 0)
		{
			for($x = 0; $x < count($results); $x++) {
				$rowLength = count($results[$x]);	
				$this->html .= '<tr style="border: 1px solid black;border-collapse: collapse;">';
				for($y = 0; $y < $numOfColumns; $y++) {
					$this->html .='<td style="border: 1px solid black;border-collapse: collapse;">'.$results[$x][$y].'</td>';
				}    
				$this->html .= '</tr>';  		   
			}
			
		}
		else {
			$this->html = "<b>0 Results</b>";
		}	
    }


    private function http_error($message) {
		header("Content-type: text/plain");
		die($message);
	}


}

?>