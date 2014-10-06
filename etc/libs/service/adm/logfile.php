<?php

class logfile{
	
	private $files;	
	
	private $handle;
	
	private $filename;
		
	function createLog($userLogin, $aksi, $message){
		$this->filename = "log/".date("m_d_Y").".txt";
		$ContentToAdd = $userLogin.";".$aksi.";".date("d/m/Y H:i:s").";".$message.";\n";
		//echo "AAAAAAAAAAAAAAAAAAA    ".$ContentToAdd;
		
		if($this->IsFileExists($this->filename)){
			$this->OpenAndModifyFile();
		} else {
			$this->CreateFile();
		}
		
		$this->AddContent($ContentToAdd);
		$this->closeFile();		
	}
	
	private function IsFileExists($filename){
		if(file_exists($filename)) return true;
		return false;
	}
	
	private function AddContent($content){
		fwrite($this->handle, $content);
	}
	
	private function CreateFile(){
		$this->handle = fopen($this->filename,"w");				
	}

	private function OpenAndModifyFile(){
		$this->handle = fopen($this->filename,"a+");		
	}
	
	private function closeFile(){
		fclose($this->handle);
	}
					
}
