<?php
/**
 * Created by PhpStorm.
 * User: McMillan
 * Date: 19-Feb-16
 * Time: 9:14 AM
 */


# used if we cannot remap an item
define('CSV_NO_REMAP_INDEX',			-1);

class Csv_Class
{
	private $fp;
	private $parse_header;
	private $header;
	private $delimiter;
	private $length;
	public $lines;
	public $filename;


	public	$csv_array;
	public 	$csv_data;
	public	$csv_lines;
	public	$csv_current_line;


	public function __construct($delimeter=",")
	{
		$this->delimiter	=	$delimeter;
	}


	public function __destruct()
	{
		if ($this->fp)
		{
			fclose($this->fp);
		}
	}



	# imports the next batch of data
	public function importFromPosition($filename_full,$csv_current_line,$max_lines,$csv_lines)
	{

AddComment("CSV read header ($filename_full)");
		# sort out the header
		if (($fp 	= fopen($filename_full, "rb")) != NULL)
		{
			$this->header	=	$this->dbaseSafeArray(fgetcsv($fp, 1000,$this->delimiter));

			fclose($fp);
		}


AddComment("CSV import lines");
		# import the file into csv array from the position we want
		$csv	 = new SplFileObject($filename_full);
		$csv->setFlags(SplFileObject::READ_CSV);

		# loops through mapping the data into the assoc array
		foreach(new LimitIterator($csv, $csv_current_line + 1, $max_lines) as $row)
		{
			if	(isset($row[0]))
			{
				$this->csv_data[]	=	array_combine($this->header,$row);

#				$this->csv_current_line++;
			}
		}

AddComment("CSV get file length");


		# we can pass in the file length once we know it, to save time on consecutive reads
		if	($csv_lines)
		{
			$this->csv_lines	=	$csv_lines;
		}
		else
		{
			# this quickly gives the max line (helpful for huge files)
			$csv->seek(PHP_INT_MAX);
			$this->csv_lines	=	$csv->key() -1;
		}
AddComment("CSV finished setup");

		return true;
	}

	# check if we hit the end of the file
	public function endOfFile($current_line)
	{
		if	($current_line >= $this->csv_lines)
		{
			return	true;
		}
	}


	# gets next row from the csv array
	public function getNextRow()
	{
		$data	=	current($this->csv_data);

		return	$data;
	}




	public function import($file_name, $parse_header=true, $delimiter=",", $length=10000000)
	{
		# get this sorted first as it can mess with file pointer
		$this->filename		= 	$file_name;
		$this->lines		=	$this->getTotalLines();

/*		# new way to load it as an array, so we just index, rather than walk
		$this->csv_array		=	array_map('str_getcsv', file($file_name));
		$this->csv_lines		=	count($this->csv_array);
		$this->csv_current_line	=	1;

		# we need to grab the header
		if ($parse_header)
		{
			$this->parse_header =	$parse_header;
			# makes it safe for dbase use
			$this->header		=	$this->dbaseSafeArray($this->csv_array[0]);
			return true;
		}
*/
		if (($this->fp 	= fopen($file_name, "rb")) != NULL)
		{
			$this->parse_header = $parse_header;
			$this->delimiter 	= $delimiter;
			$this->length 		= $length;
			# we need to grab the header
			if ($this->parse_header)
			{
				$this->header	=	$this->dbaseSafeArray(fgetcsv($this->fp, 1000, $this->delimiter));
				$this->lines--;

			}

			return true;

		}
		return false;
	}





	# gets an array and makes it dbase_safe
	public function dbaseSafeArray($data)
	{
		foreach ($data as $key=> $value)
		{
			$value 		=	trim(strtolower($value));
			$value 		=	str_replace(' ','_',$value);
			$data[$key]	=	$value;
		}

		return $data;
	}

	# change it to Excel normal header
	public function excelSafeArray($data)
	{
		foreach ($data as $key=> $value)
		{
			$value 		=	trim(strtolower($value));
			$value 		=	str_replace('_',' ',$value);
			$value 		=	ucwords($value);
			$data[$key]	=	$value;
		}

		return $data;
	}



	public function getHeader()
	{
		return	$this->header;
	}

	# check if the header contains these
	public function headerContains($array)
	{
		$array	=	$this->dbaseSafeArray($array);

		if	(empty($array))
		{
			$a 	=	0;
		}

		foreach ($array as $key=> $value)
		{
			if ($this->inHeader($value) == false)
			{
				return false;
			}
		}

		return true;
	}


	# check if this column is in the header
	public function inHeader($column)
	{
		if (($key = array_search(strtolower($column), array_map('strtolower', $this->header))) !== FALSE)
		{
			return true;
		}
	}



	# gets lines in csv file
	private function getTotalLines()
	{
		$lines	=	0;
		if (($fp = fopen($this->filename,'rb')) != NULL)
		{
			while (fgets($fp) !== false) $lines++;
			fclose($fp);
		}

		return $lines;
	}









	# gets lines in csv file
	public function getRow()
	{
		if (($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE)
		{
			$data	=	array();

			foreach ($row as $column=>$value)
			{
				$data[$this->header[$column]] = $value;
			}

			return $data;
		}
	}






	# sets the current row pointer
	public function setCurrentRow($current_row)
	{
		$this->csv_current_line	=	$current_row;
	}



	# export csv file
	public function export($array,$filename="Entitled Claims File.csv",$delimiter=",")
	{
		#	function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		// open the "output" stream
		// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
		$f = fopen('php://output', 'w');
		foreach ($array as $line)
		{
			fputcsv($f, $line, $delimiter);
		}
	}





	# gets lines in csv file
	public function get($max_lines=0,$index_array=array())
	{
		$data	=	array();

		if ($max_lines > 0)
			$line_count = 0;
		else
			$line_count = -1; // so loop limit is ignored

		while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE)
		{
			# if we have to index remap the array based on order of the columns
			if	(!empty($index_array))
			{
				$new_row	=	array();

				for ($i=0;$i<count($index_array); $i++)
				{
					$new_index				=	$index_array[$i];
					$new_row[$new_index]	=	$row[$i];
				}

				$data[] =	$new_row;
			}
			else
			{
				$data[] = $row;
			}


			if ($max_lines > 0)
				$line_count++;
		}

		return $data;
	}



}












