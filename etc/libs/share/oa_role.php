<?php 
class oa_role 
{
	public function getORGRole($katagori, $eselon, $userLevel, $userORG)
	{
		if ($katagori == 'A') { $org = 'MN'; }
		else 
		{
			if ($eselon == 'NE') {  $org = 'NE'; }
			else
			{
				if ($userLevel == '1') { $org = 'MN'; }
				else if ($userLevel == '2') 
				{
				    if (substr($userOrg,0,2)=='SK') { $org = substr($userOrg,0,2); }
					else { $org = substr($userOrg,0,3); }
				}
				else if ($userLevel == '3') 
				{
				    if (substr($userOrg,0,2)=='SK') { $org = substr($userOrg,0,3); }
					else { $org = substr($userOrg,0,4); }
				}
				else if ($userLevel == '4') 
				{
					if (substr($userOrg,0,2)=='SK') { $org = substr($userOrg,0,4); }
					else { $org = substr($userOrg,0,5);  }
				}
				else if ($userLevel == '5') { $org = substr($userOrg,0,6); }	
			}
		}
		return $org;
	}
}
?>