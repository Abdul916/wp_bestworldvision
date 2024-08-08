<?php
class Common_Admin_Functions {
	
	//============================================================
	//functions for use in both admin files, can be included where needed
	//--------------------------
	
	//========================================
	//simple function to create json from string of comma list 
	//used in partials/review_list and partials/templates_posts, and admin_hooks
	public function wprev_commastrtojson($str,$dashes=false,$isnumber=true){
			if($str!=""){
			$str = preg_replace('/\s/', '', $str);
			$strarray = explode(',',$str);
			$strarray = array_filter($strarray);
			foreach ($strarray as $each_number) {
				if($isnumber==true){
					if($dashes==false){
						$strarraynew[] = (int) $each_number;
					} else {
						$strarraynew[] = "-".(int) $each_number."-";
					}
				} else {
					if($dashes==false){
						$strarraynew[] = $each_number;
					} else {
						$strarraynew[] = "-".$each_number."-";
					}
				}
			}
			$strarrayjson = json_encode($strarraynew);
			} else {
				$strarrayjson = '[]';
			}
			return $strarrayjson;
	}
	
	//function for returning whether or not a current logged in user can see the page. Set in Tools.
	//input is the page. output is true/false
	public function wprev_canuserseepage($pageurl=''){
		$results['cap']='';
		$results['canview']=false;
		//get current saved values from Tools page
		 if(get_option('wprev_rolepages')){
			$savedrolesjson = get_option('wprev_rolepages');
			$savedrolesarray = json_decode($savedrolesjson,true);
		 } else {
			$savedrolesarray = Array(); 
		 }
		 //echo "<br><br>";
		 //print_r($savedrolesarray);
		 //echo "<br><br>";
		 //ex: Array ( [getrevs] => Array ( [0] => author ) [reviewfunnel] => Array ( [0] => contributor [1] => subscriber ) [reviews] => Array ( [0] => customer ) [templates_posts] => Array ( [0] => editor [1] => author ) [forms] => Array ( [0] => contributor ) [notifications] => Array ( [0] => contributor ) )
		 
		 //check to see if $pageurl is in the $savedrolesarray
		 if(isset($savedrolesarray[$pageurl]) && is_array($savedrolesarray[$pageurl])){
			 //echo "here";
			 //if true then get the role of the current user.
			 $userroles = $this->wprev_get_user_roles();	//array of user roles.
			// echo "<br><br>";
			//print_r($userroles);
			//echo "<br><br>";
			//print_r($savedrolesarray[$pageurl]);
			
			foreach ($savedrolesarray[$pageurl] as $value) {
			  //loop each userroles and see if a match.
			  foreach ($userroles as $userrole) {
				  if($userrole == $value){
					$results['canview']=true;
				    $capabilityarray = get_role($userrole)->capabilities;
				    $firstKey = array_key_first($capabilityarray);
					$results['cap'] = $firstKey;
				  }
				  if($userrole == 'subscriber'){
					  //not opening up to subscribers
					  $results['canview']=false;
				  }
			  }
			}

		 }
		 
		 return $results;
	}
	
		//used for returning which roles the current user has
	public function wprev_get_user_roles() {
		 $user = wp_get_current_user(); // getting & setting the current user 
		 //print_r($user);
		 $roles = ( array ) $user->roles; // obtaining the role 
		 return $roles;
	}
	
	
	

}
	//========================================
	
	?>