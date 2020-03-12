<?php
include '../includes/autoload.php';

    if(!isset($_SESSION['authenticated']) || $_SESSION['role'] != "VP")
       {
            header('Location: ../index.php');
            exit;
       }
       if($_SESSION['freeze'] == "TRUE")
       {
          // echo "Account Frozen";
           header('Location: accountEdit.php');
       }
   
	$id = $_SESSION['userId'];
	
	$table1 = "user_info";
	$table2 = "users";
	$table3 = "distributors";
        
        
	$upload_msg = "Message: <br />";
	function isUniqueEmail($link, $table1, $email) {
		$query = "SELECT * FROM $table1 WHERE email='$email'";
		$result = mysqli_query($link, $query)or die("MySQL ERROR on query c: ".mysqli_error($link));
		if(mysqli_num_rows($result) >= 1) {
			echo "I'm sorry, that email address is already being used, please use another one.";
			return false;
		} else {
			return true;
		}
	}
	
	// check if form has been submitted
	if(isset($_POST['submit'])){
	
	$rpPhoto = $_FILES['uploaded_file']['tmp_name'];
	$imageDirPath = $_SERVER['DOCUMENT_ROOT'].'/images/sc/';
	$vpPicPath = "";
	
	//grab all form fileds
	$bob = 24503;
	$vpid = $id;
	$scid = mysqli_real_escape_string($link, $_POST['scid']);
	$company = mysqli_real_escape_string($link, $_POST['cname']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$mname = mysqli_real_escape_string($link, $_POST['mname']);
	$lname = mysqli_real_escape_string($link, $_POST['lname']);
	$title = mysqli_real_escape_string($link, $_POST['title']);
	$gender = mysqli_real_escape_string($link, $_POST['gender']);
	$ssn = mysqli_real_escape_string($link, $_POST['ssn1']);
	$address1 = mysqli_real_escape_string($link, $_POST['address1']);
	$address2 = mysqli_real_escape_string($link, $_POST['address2']);
	$city = mysqli_real_escape_string($link, $_POST['city']);
	$state = mysqli_real_escape_string($link, $_POST['state']);
	$zip = mysqli_real_escape_string($link, $_POST['zip']);
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$phone = mysqli_real_escape_string($link, $_POST['phone']);
	//$hPhone2 = mysqli_real_escape_string($link, $_POST['hphone2']);
	//$wPhone3 = mysqli_real_escape_string($link, $_POST['hphone3']);
	$mPhone = mysqli_real_escape_string($link, $_POST['mphone']);
	$ext = mysqli_real_escape_string($link, $_POST['ext']);
	$fbPage = mysqli_real_escape_string($link, $_POST['fb']);
	$twitter = mysqli_real_escape_string($link, $_POST['twitter']);
	$linkedin = mysqli_real_escape_string($link, $_POST['lindkedin']);
	$loginPass = mysqli_real_escape_string($link, $_POST['password']);
	$salesMan = $_POST['sales'];
	$ftin = mysqli_real_escape_string($link, $_POST['ftin1']);
	$stin = mysqli_real_escape_string($link, $_POST['stin1']);
	$nonp = mysqli_real_escape_string($link, $_POST['nonp1']);
	//$cellPhone = $_POST['cellphone'];
	//$workPhone = $_POST['workphone'];
	$extPhone = mysqli_real_escape_string($link, $_POST['ext']);
	$paypal = mysqli_real_escape_string($link, $_POST['paypalemail']);
	$landingPage = "setupEditWebsite/accountEdit.php";
	$who = "RP";
	$percent = 6;
	$salt = time(); 			// create salt using the current timestamp 
	$loginPass = sha1($loginPass.$salt); 	// encrypt the password and salt with SHA1
	//$distPic = $_FILES['uploaded_file']['tmp_name'];
	$imageDirPath = $_SERVER['DOCUMENT_ROOT'].'/images/representatives/';
	$imagePath = "";
	
	
	  function process_image($name, $id, $tmpPic, $baseDirPath){

		$cleanedPic = checkName($_FILES["$name"]["name"]);	
		if(!is_image($tmpPic)) {
    			// is not an image
    			$upload_msg .= $cleanedPic . " is not an image file. <br />";
    		} else {
    			if($_FILES['$name']['error'] > 0) {
				$upload_msg .= $_FILES['$name']['error'] . "<br />";
			} else {
				
				if (file_exists($baseDirPath.$id."/".$cleanedPic)){
					$imagePath = "images/representatives/".$id."/".$cleanedPic;
				} else {
					$picDirectory = $baseDirPath;
					
					
					if (!is_dir($picDirectory.$id)){
						mkdir($picDirectory.$id);
						
					}
					$picDirectory = $picDirectory.$id."/";
					move_uploaded_file($tmpPic, $picDirectory . $cleanedPic);
					$upload_msg .= "$cleanedPic uploaded.<br />";
					$imagePath = "images/representatives/".$id."/".$cleanedPic;
					
					
				}// end third inner else
				return $imagePath;
			} // end first inner else
		      } // end else
	     }// end process_image
	    
		//if good email insert data
	    if(isUniqueEmail($link, $table1, $email)) {
		$query1 = "INSERT INTO $table2 (username, password, Security, landingPage, salt, created, lastLogin, role)";
		$query1 .= "VALUES('$email','$loginPass','1','$landingPage','$salt', now(), now(), '$who')";
		$query2 = "INSERT INTO $table1 (companyName, FName, MName, LName, ssn, address1, address2, city, state, zip, email, homePhone, fbPage, twitter, linkedin, salesPerson, cellPhone, workPhone, userPaypal,role,title,gender, userBaseCommPct, fedtin, statetin, threec)";
		$query2 .= " VALUES('$company','$fname','$mname','$lname','$ssn','$address1','$address2','$city','$state','$zip','$email','$hPhone1','$fbPage','$twitter','$linkedin', '$scid','$phone', '$phone', '$paypal','$who', '$title', '$gender', '$percent', '$ftin', '$stin', '$nonp')";
        $query3 = "INSERT INTO $table3 (companyName, FName, MName, LName, ssn, address1, address2, city, state, zip, email, homePhone, fbPage, twitter, linkedin, vpID, workPhone, workPhoneExt,  distPicPath,setupID, role,paypal)";
		$query3 .= " VALUES('$company','$fname','$mname','$lname','$ssn','$address1','$address2','$city','$state','$zip','$email','$hPhone1','$fbPage','$twitter','$linkedin', '$id', '$phone', '$ext', '$imagePath','$scid', '$who','$paypal')";


		mysqli_query($link, "start transaction;");
		// insert data into users table
		$res1 = mysqli_query($link, $query1)or die ("couldn't execute query 1.".mysql_error());
		// insert data into distributors table
		$res2 = mysqli_query($link, $query2)or die ("couldn't execute query 2.".mysql_error());
		
		$res3 = mysqli_query($link, $query3)or die ("couldn't execute query 3.".mysql_error());
		
		if($res1 && $res2 && $res3){
			mysqli_query($link, "commit;");
			$query9 = "SELECT * FROM user_info WHERE email='$email'";
			$res4 = mysqli_query($link, $query9)or die ("couldn't execute query 9.".mysql_error());
			$row = mysqli_fetch_assoc($res4);
			$newUserID = $row['userInfoID'];
			
			$queryx = "UPDATE distributors SET loginid = '$newUserID ', distPicPath='$imagePath' WHERE email = '$email'";
			mysqli_query($link, $queryx)or die ("couldn't execute query x.".mysql_error());
			
			if($rpPhoto != ''){
		    $personalPicPath = process_image('uploaded_file',$newUserID, $rpPhoto, $imageDirPath);
		    if($personalPicPath !=''){
			$query10 = "UPDATE $table1 SET picPath = '$personalPicPath' WHERE userInfoID = '$newUserID'";
			mysqli_query($link, $query10);
						}
		    }
			echo "Your account has been successfuly created.\n\n";
			//newDistributorEmail($email,$FName,$LName,$cellPhone);
			 header( 'Location: accounts.php' );

		} 
	        }
		else
		{   
		    /*
                    mysqli_query($link, "rollback;");
		    echo "I'm sorry, there was a problem creating your account.";
		    */  
		        $query15 = "SELECT * FROM user_info WHERE email='$email'";
			$res15 = mysqli_query($link, $query15)or die ("couldn't execute query 15.".mysql_error());
			$rowC = mysqli_fetch_assoc($res15);
			$newUserID = $rowC['userInfoID'];
			$company2 = $rowC['companyName'];
			$fname2 = $rowC['FName'];
			$lname2 = $rowC['LName'];
			$ssn2 = $rowC['ssn'];
			$ad1 = $rowC['address1'];
			$ad2 = $rowC['address2'];
			$city2 = $rowC['city'];
			$state2 = $rowC['state'];
			$zip2 = $rowC['zip'];
			$fbPage = $rowC['fbPage'];
			$twitter2 = $rowC['twitter'];
			$linkedin2 = $rowC['linkedin'];
			$phone2 = $rowC['workPhone'];
			$ext2 = $rowC['workPhoneExt'];
			$imagePath2 = $rowC['picPath'];
			$paypal2 = $rowC['userPaypal'];
			$title2 = $rowC['title'];
			$gender2 = $rowC['gender'];
	
			
			$query16 = "INSERT INTO distributors (companyName, FName, LName, ssn, address1, address2, city, state, zip, email, fbPage, twitter,
			 linkedin, vpID, workPhone, workPhoneExt,  distPicPath, setupID,loginid, role, paypal, title, gender)";
			 
		        $query16 .= " VALUES('$company2','$fname2','$lname2','$ssn2','$ad1','$ad2','$city2','$state2','$zip2',
		        '$email','$fbPage','$twitter2','$linkedin2', '$id', '$phone2', '$ext2', '$imagePath2', '$scid', '$newUserID', '$who', '$paypal2', 'title2', '$gender2')";
		        $res16 = mysqli_query($link, $query16)or die ("couldn't execute query 16.".mysql_error());
		        
		        echo "Your account has been successfuly created.\n\n";
			//newDistributorEmail($email,$FName,$LName,$cellPhone);
			 header( 'Location: accounts.php' );
	        }
	

	}// end if
	
   $userID = $_SESSION['userId'];
   $query = "SELECT * FROM user_info WHERE userInfoID='$id' and role='VP'";
   $result = mysqli_query($link, $query)or die ("couldn't execute query.".mysql_error());
   $row = mysqli_fetch_assoc($result);
   $pic = $row['picPath'];
   $bob = 24503;
?>
<!DOCTYPE html>
<head>
	<title>FundraisingATM | Add Fundraiser Account</title>
    <link rel="shortcut icon" href="../images/favicon.ico">
 	<link rel="stylesheet" type="text/css" href="../css/old/addnew_form_styles.css" />
  	<link rel="stylesheet" type="text/css" href="../css/simpletabs_styles.css" />
  	<script type="text/javascript" src="../js/simpletabs_1.3.js"></script>

      <style>

form input[type=submit], form input[type=reset], form input#redbutton{
	border:thin solid #777;	
	-moz-border-radius: 7px 7px 7px 7px;
	-webkit-border-radius: 7px 7px 7px 7px;
	border-radius: 7px 7px 7px 7px;
	-moz-box-shadow: 3px 3px 3px #777;
	-webkit-box-shadow: 3px 3px 3px #777;
	box-shadow: 3px 3px 3px #777;
	background-color: #CC0000;
	color: white;
	font-size: .75em;
	cursor: pointer;
}
#redbutton a{
	border:thin solid #777;	
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
	border-radius: 8px;
	-moz-box-shadow: 3px 3px 3px #777;
	-webkit-box-shadow: 3px 3px 3px #777;
	box-shadow: 3px 3px 3px #777;
	height: 1.5em;
	background-color: #CC0000;
	color: white;
	font-size: .75em;
	text-decoration: none;
	padding: .2em;
}
.redbutton:hover{color: #cc0000; background: #fff; }

ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #FFF;
    background-color: #ccc;
}

/* Float the list items side by side */
ul.tab li {
    float: left;
    border: 3px solid #ccc;
    }

/* Style the links inside the list items */
ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
ul.tab li a:hover {
    background-color: white;
}

/* Create an active/current tablink class */
ul.tab li a:focus, .active {
    color: white;
    background-color: #cc0000;
}


/* Style the tab content */
.tabcontent {
    display: none;
    width: 100%;
    padding: 2px 12px;
    border: 1px solid #ccc;
    border-top: none;
    box-shadow: 0px 0px 15px #888888;
}
.tabcontent{
padding-right:1em;
padding-left:1em;
}
#newLeader{
    margin-top:2em;
}
.form-control{
    margin-bottom:1rem;
}
label{
    margin-top:1rem;
}
.interim-header{
    margin: -2rem 0 -2rem 0;
}
.files {
  border-radius: 25px;
}

</style>
</head>

<body>
<div id="container">
    	<?php include 'header.inc.php' ; ?>
      	<?php include 'sidenav.php' ; ?>
      	
		<div id="content" style="margin-left:35px">
        	<h1 align="center"><b>Add New Fundraiser Account</b></h1> 
			<div class="table">
				<form class="graybackground" action="addFundAccount.php" method="POST" enctype="multipart/form-data" id="myForm" name="myForm" onsubmit="return checkForm(this);">
				<h2><b>--Option 1: Add One Account--</b></h2>
                <div>
					<span id="hd_vp2" style="font-size: 1.1em">Vice President:</span>
                    
                    <span id="hd_sc2" style="font-size: 1.1em">Sales Coordinator:</span>
                    <span></span>
					<span id="hd_rp2" style="font-size: 1.1em">Representative</span>
                </div> <!-- end row -->

				<div class="tablerow" >
					<select name="vpid" id="vpid" class="role2">
						<option>Select VP Account</option>
						<?php
						$query = "Select * FROM distributors  WHERE setupID='$id' and role='VP'";
											$result = mysqli_query($link, $query)or die("MySQL ERROR om query 2: ".mysqli_error($link));


											while($row = mysqli_fetch_assoc($result))
											{
						echo '<option value="'.$row['loginid'].'">'.$row[FName].' '.$row[LName].' '.$row[loginid].'</option>';
						}
						?>
					</select>

                    <select name="vpid" id="vpid" class="role2">
							<option>Select SC Account</option>
						</select>
						
						<select name="vpid" id="vpid" class="role2">
							<option>Select RP Account</option>
						</select>


				</div> <!-- end row -->
				<br>
				<ul class="tab" style="box-shadow: 0px 0px 15px #888888;">
                        <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Single')" id="defaultOpen" style="font-weight:bold">Information</a></li>
						<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Four')" style="font-weight:bold">Banner</a></li>
						<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Triple')" style="font-weight:bold">Social Media</a></li>
				</ul>

				<div id="Single" class="tabcontent">
					<!-- <form class="" action="addFundMember.php" method="Post" id="myForm" name="myForm" onsubmit="return checkForm(this);" enctype="multipart/form-data"> -->
  					<div class="table" style="width:100%; height: 370px;">
						<div class="simpleTabs" style="margin-left:30px;">
							<!--<ul class="simpleTabsNavigation">
								<li><a href="#">Information</a></li>
								<li><a href="#">Account Login</a></li>
								<li><a href="#">Social Media</a></li>
								<li><a href="#">Profile Photo</a></li>
							</ul> -->

		  					<div>
												<h1 style="color: #cc0000"><b>Specify Account Type</b></h1>

												<select id="vpid" name="category">
													<option selected>Select Organization</option>
													<option value="All Categories">All Categories</option>
													<option value="Educational Organizations">Educational Organizations</option>
													<option value="Faith Groups">Faith Groups</option>
													<option value="Community Organizations">Community Organizations</option>
													<option value="Youth & Sports Organizations">Youth & Sports Organizations</option>
													<option value="Local & National Charities">Local & National Charities</option>
													<option value="National Organizations">National Organizations</option>
													<option value="Individual Charity">Individual Charity</option>
													<option value="Other">Other</option>
												</select>
												<select id="vpid" name="type">
													<option selected>Select Category</option>
													<option value="All Educational Types">All Educational Types</option>
													<option value="4yr College">4yr College</option>
													<option value="2yr College">2yr College</option>
													<option value="High School">High School</option>
													<option value="Middle School">Middle School</option>
													<option value="Elementary School">Elementary School</option>
													<option value="Home School">Home School</option>
													<option value="Preschool School">Preschool School</option>
													<option value="Other">Other</option>
												</select>
												<select id="vpid" name="subtype">
													<option selected>Select Sub-Category</option>
													<option value="All Sub-Types">All Sub-Types</option>
													<option value="Public" >Public</option>
													<option value="Private">Private</option>
													<option value="Christian">Christian</option>
													<option value="Charter">Charter</option>
												</select>
												<br>
												<h2 style="color: #cc0000"> <b>[Category] Contact Information</b></h2>
											
								<table>
									<tr>
										<td id="td_1">
											<div class="tablerow"> <!-- title -->
												<span id="hd_address1">[Category] Name</span>
											</div> <!-- end row -->
											<div class="tablerow"> <!-- input -->
												<input id="address1" type="text" name="">
											</div> <!-- end row -->
                                            <div class="tablerow"> <!-- title -->
												<span id="hd_address1">Address 1</span>
											</div> <!-- end tablerow -->
											<div class="tablerow"> <!-- input -->
												<input id="address1" type="text" name="address1">
											</div> <!-- end row -->
											<div class="tablerow"> <!-- title -->
												<span id="hd_address2">Address 2</span>
											</div> <!-- end row -->
											<div class="tablerow"> <!-- input -->
												<input id="address2" type="text" name="address2">
											</div> <!-- end row -->

											<div class="tablerow"> <!-- titles -->
												<span id="hd_city">City</span>
												<span></span>
												<span id="hd_state">State</span>
												<span></span>
												<span id="hd_zip">Zip</span>
											</div>
											<div class="tablerow"> <!-- inputs -->
												<input id="city" type="text" name="city">
												<select id="state" name="state" style="border-radius:15px; outline:none;">
													<option value="" selected="selected">--</option>
													<option value="AL">AL</option>
													<option value="AK">AK</option>
													<option value="AZ">AZ</option>
													<option value="AR">AR</option>
													<option value="CA">CA</option>
													<option value="CO">CO</option>
													<option value="CT">CT</option>
													<option value="DE">DE</option>
													<option value="DC">DC</option>
													<option value="FL">FL</option>
													<option value="GA">GA</option>
													<option value="HI">HI</option>
													<option value="ID">ID</option>
													<option value="IL">IL</option>
													<option value="IN">IN</option>
													<option value="IA">IA</option>
													<option value="KS">KS</option>
													<option value="KY">KY</option>
													<option value="LA">LA</option>
													<option value="ME">ME</option>
													<option value="MD">MD</option>
													<option value="MA">MA</option>
													<option value="MI">MI</option>
													<option value="MN">MN</option>
													<option value="MS">MS</option>
													<option value="MO">MO</option>
													<option value="MT">MT</option>
													<option value="NE">NE</option>
													<option value="NV">NV</option>
													<option value="NH">NH</option>
													<option value="NJ">NJ</option>
													<option value="NM">NM</option>
													<option value="NY">NY</option>
													<option value="NC">NC</option>
													<option value="ND">ND</option>
													<option value="OH">OH</option>
													<option value="OK">OK</option>
													<option value="OR">OR</option>
													<option value="PA">PA</option>
													<option value="RI">RI</option>
													<option value="SC">SC</option>
													<option value="SD">SD</option>
													<option value="TN">TN</option>
													<option value="TX">TX</option>
													<option value="UT">UT</option>
													<option value="VT">VT</option>
													<option value="VA">VA</option>
													<option value="WA">WA</option>
													<option value="WV">WV</option>
													<option value="WI">WI</option>
													<option value="WY">WY</option>
												</select>
												<span></span>
												<input id="zip" type="text" name="zip" maxlength="5" required>
											</div> <!-- end row -->
										</td>
									
                           				<td id="td_2">

											<!-- <div class="tablerow">
                  								<br>			
												<span id="hd_mphone1">Mobile Phone</span>
											</div> -->
											<!-- <div class="tablerow"> 
												<input id="mphone1" type="text" name="mphone">
							
												<select id="mcarrier" title="Needed To Receive Texts From Computer" style="border-radius:15px; outline:none;">
													<option>Select Carrier</option>
													<option>Verizon</option>
													<option>AT&T</option>
													<option>Sprint</option>
													<option>T-Mobile</option>
													<option>U.S. Cellular</option>
													<option>Other</option>
												</select>
											</div>  -->
											<!-- <div class="tablerow">
												<span id="hd_hphone">Home Phone</span>
											</div> 
											<div class="row">
												<input id="hphone1" type="text" name="hPhone">
											</div>  -->
											<div class="tablerow">
												<span id="hd_wphone">Work Phone</span>
												<span></span>
												<span id="ext">Ext</span>
											</div>
											<div class="row">
												<input id="wphone1" type="text" name="wphone" maxlength="12">
												<input id="ext" type="text" name="ext" maxlength="5">
											</div>
										</td>
									</tr>
								</table>
								
							</div> <!-- end tab 1 -->
						</div> <!-- end simple tabs -->
					</div> <!-- end row -->
					<section class="row" style="margin:4rem 0" id="submitButtonSection-form"><!-- SUBMIT BUTTON SECTION ROW -->

					   <div class="tablerow">
						 <input type="submit" name="submit" class="redbutton" value="Save & Exit" onsubmit="return validate()">
                         <span></span>
						 <input type="submit" class="redbutton" value="Save & Add Another">
                         <span></span>
                         <input type="submit" class="redbutton" value="Save Account & Add Fundraising Group">
					   </div> <!-- end row -->
					 </section>
					 <br>
		</div> <!-- end table -->


<div id="Triple" class="tabcontent">
  <div class="table" style="width:100%; height:370px">


            <div class="simpleTabs" style="margin-left:30px">
            <div>
  						<h1 style="color: #cc0000"><b>Social Media Connections</b></h1>
  						<div id="row">
  							<span id="hd_fb">Facebook</span>
  							<input id="fb" type="text" name="fb" value="www.facebook.com">
  						</div> <!-- end row -->
  						<div id="row">
  							<span id="hd_tw">Twitter</span>
  							<input id="tw" type="text" name="twitter" value="www.twitter.com">
  						</div> <!-- end row -->
  						<div id="row">
  							<span id="hd_li">LinkedIn</span>
  							<input id="li" type="text" name="linkedin" value="www.linkedin.com">
  						</div> <!-- end row -->
  						<div id="row">
  							<span id="hd_pn">Pinterest</span>
  							<input id="pn" type="text" name="printrest" value="www.pinterest.com">
  						</div> <!-- end row -->
  						<div id="row">
  							<span id="hd_gp">Google+</span>
  							<input id="gp" type="text" name="googleplus" value="plus.google.com">
  						</div>
              <br>
              <br>
              <!-- end row -->
              </div>

              </div>
  			
		</div> <!-- end tab 3 -->
		
		<section class="row" style="margin:4rem 0" id="submitButtonSection-form"><!-- SUBMIT BUTTON SECTION ROW -->

						 <div class="tablerow">
						   <input type="submit" name="submit" class="redbutton" value="Save & Exit" onsubmit="return validate()">
                           <span></span>
						   <input type="submit" class="redbutton" value="Save & Add Another">
                           <span></span>
                           <input type="submit" class="redbutton" value="Save Account & Add Fundraising Group">

						 </div> <!-- end row -->
					   </section>
						<br>

    </div>

<div id="Four" class="tabcontent">
  <div class="table" style="width:100%; height:370px">


            <div class="simpleTabs" style="margin-left:30px">
            <div>
  			<h1 style="color: #cc0000"><b>Website Banner</b></h1>
            </div>
            <div> 
                <span style="font-size:17px"> Existing Website URL:
                <input type="text">
                </span>
            </div>
              <div> 
  							<span style="font-size:17px">Upload Banner:</span>
                              <br>
  							<input type="file" id="" name="uploaded_file">
  							
  							
  						</div>
              <br><br>

			
               <!-- end row -->
            </div>

  					</div> <!-- end tab 3 -->


			<section class="row" style="margin:4rem 0" id="submitButtonSection-form"><!-- SUBMIT BUTTON SECTION ROW -->
          <div class="tablerow">
            <input type="submit" name="submit" class="redbutton" value="Save & Exit">
            <span></span>
            <input type="submit" class="redbutton" value="Save & Add Another">
            <span></span>
            <input type="submit" class="redbutton" value="Save Account & Add Fundraising Group">

          </div> <!-- end row -->
        </section>
		<br>



			<!--<form class="graybackground">
				<h3>--Option 2: Add Multiple Business Associates--</h3>
				<h2>How To Add Multiple Business Associates</h2><br>
				<ol>
					<li><a href="">Download</a> Our Business Associate Setup Spreadsheet</li>
					<li>Input the Data for Each Associate You want to Add</li>
					<li>Upload the Completed Spreadsheet...</li>
				</ol>
				<input type="file" name="">
				<input class="redbutton" type="submit" name="" value="Upload File">-->
        



      </form>
		</div> <!-- end table -->
        <br>
        <div class="table">
				<form class="graybackground" action="addFundAccount.php" method="POST" enctype="multipart/form-data" id="myForm" name="myForm" onsubmit="return(validate());">
                    <h2><b>--Option 2: Add Multiple Accounts--</b></h2>
					<div style="margin-left:30px">
					<h2 style="color: #cc0000"><b>How To Add Multiple Accounts</b></h2>
					<ol>
						<li><a href="">Download</a> Our Fundraiser Setup Spreadsheet</li>
						<li>Input the Data for Each Fundraiser Account You Want to Add</li>
						<li>Upload the Completed Spreadsheet Below...</li>
					</ol>
					<br>
        <span>  
          <input class="files" type="file" name="">
          <br>
		    <input class="redbutton" type="submit" name="" value="Upload File">
         </span>
					</div>
                    <br><br>
				</form>
			</div> <!-- end table -->
   <!--end content -->

  <script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
</div> <!--end container-->
</div>
<?php include 'footer.php' ; ?>
</div>

</body>
</html>

<?php
   ob_end_flush();
?>
