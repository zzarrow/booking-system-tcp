#!/usr/bin/php

<?php



        $log = array();

        $db = mysql_connect("fling.seas.upenn.edu", "mwester", "tcpfellows");
        mysql_select_db("mwester");

        date_default_timezone_set("America/New_York");

        function add_to_log($msg){
                //Log error
                //Send mail to sysadmin with error information
                global $log;
                $log[] = $msg;
                echo('Logging: ' . $msg . '\r\n');
        }



        function log_possibly_fatal_error($err){
                global $log;
                $log[] = $err;

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: Zach Zarrow<zzarrow@seas.upenn.edu>' . "\r\n";
                $headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";

                $subj = "TCP CRON Job Error Report: Daily Feedback Request Notifications";

                $msg = "<b><font color=\"#FF0000\">An error occurred while processing the Daily Appointment Reminders.</font></b>  The error occurred at <b>" . date("m/d/Y h:i:s a", time()) . "</b>.<br /><br /><font face=\"Courier New\" color=\"#FF0000\">";

                $msg .= $err;

                $msg .= "</font><br /><br />At the time of the error, the execution log contains:<br /><br /><font face=\"Courier New\">";

                for($i = 0; $i < sizeof($log); $i++){
                        $msg .= $GLOBALS['log'][$i];
                        $msg .= "<br />";
                }

                $msg .= "</font>";

                mail("",
                        $subj,
                        $msg,
                        $headers);

        }

        function flush_log(){
                //TODO: Implement a more modular solution here

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'To: Zach Zarrow<zzarrow@seas.upenn.edu>' . "\r\n";
                $headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . "\r\n";

                $subj = "TCP CRON Job Log: Daily Feedback Request Notifications";

                $msg = "Job completed at <b>" . date("m/d/Y h:i:s a", time()) . "</b>.<br /><br /><font face=\"Courier New\">";

                global $log;
                for($i = 0; $i < sizeof($log); $i++){
                        $msg .= $log[$i];
                        $msg .= "<br />";
                }

                $msg .= "</font>";

                mail("",
                        $subj,
                        $msg,
                        $headers);

        }

        function index_to_str($_time_index){
                        $temp_arr = array(
                                        0 => "9:00am",
                                        1 => "9:30am",
                                        2 => "10:00am",
                                        3 => "10:30am",
                                        4 => "11:00am",
                                        5 => "11:30am",
                                        6 => "12:00pm",
                                        7 => "12:30pm",
                                        8 => "1:00pm",
                                        9 => "1:30pm",
                                        10 => "2:00pm",
                                        11 => "2:30pm",
                                        12 => "3:00pm",
                                        13 => "3:30pm",
                                        14 => "4:00pm",
                                        15 => "4:30pm",
                                        16 => "5:00pm",
                                        17 => "5:30pm",
                                        18 => "6:00pm",
                                        19 => "6:30pm",
                                        20 => "7:00pm",
                                        21 => "7:30pm",
                                        22 => "8:00pm",
                                        23 => "8:30pm"
                         );

                         return $temp_arr[$_time_index];
        }


        $yesterday = date('Ymd', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
        $q = mysql_query("SELECT * from `appointments` WHERE `apt_date` = '" . $yesterday . "'") or log_possibly_fatal_error('Appointments query failed.  If a Job Completed e-mail is not received, this error was fatal and feedback notifications may not have been sent.  MySQL said: ' . mysql_error());

        while($f = mysql_fetch_object($q)){
                send_student_email($f->apt_date, $f->time_index, $f->firstname, $f->lastname, $f->email);
                add_to_log('Feedback request sent to student ' . $f->firstname . ' ' . $f->lastname . '(' . $f->email . ') for appointment on ' . $f->apt_date . ' at time index ' . $f->time_index);
        }

        flush_log();
        mysql_close($db);


function send_student_email($_apt_date, $_time_index, $_firstname, $_lastname, $_email){

// subject
$subject = 'Evaluation of Meeting With TCP Fellow';

// message
$message = '<html><center><br />If you cannot view the form below, please <a href = "https://spreadsheets.google.com/viewform?formkey=dGdWU01BOG9xN1VmdjJxSHJNUXhqblE6MA">click here to fill it out in your browser</a>.</center><br /><head><link rel="shortcut icon" href="https://spreadsheet.google.com/images/forms/favicon2.ico" type="image/x-icon">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title>Evaluation of meeting with TCP Fellow</title>

<link href=\'https://spreadsheets.google.com/client/css/4176578971-published_form_compiled.css\' type=\'text/css\' rel=\'stylesheet\'>
<link href=\'https://spreadsheets.google.com/client/css/4176578971-published_form_compiled.css\' type=\'text/css\' rel=\'stylesheet\'>
</head>
<body class="ss-base-body" dir="ltr"><div class="ss-form-container">
<div class="ss-form-heading"><h1 class="ss-form-title">Evaluation of meeting with TCP Fellow</h1>
<p></p>
<div class="ss-form-desc ss-no-ignore-whitespace">Hello ' . $_firstname . ',<br /><br />Our record shows that you recently had an appointment with a TCP Fellow..  Please help us improve TCP by giving us your feedback on the meeting.
Thanks!
</div>
<p></p>

<hr class="ss-email-break" style="display:none;">

<div class="ss-required-asterisk">* Required</div></div>
<div class="ss-form"><form action="https://spreadsheets.google.com/formResponse?formkey=dGdWU01BOG9xN1VmdjJxSHJNUXhqblE6MA&amp;ifq" method="POST" id="ss-form">
<input type="hidden" name="entry.0.single" id="entry_0" value="Yes">
<input type="hidden" name="entry.1.single" id="entry_1" value="N/A">
<br><div class="errorbox-good">
<div class="ss-item ss-item-required ss-paragraph-text"><div class="ss-form-entry"><label class="ss-q-title" for="entry_6">What assignment(s) did you meet to discuss?
<span class="ss-required-asterisk">*</span></label>
<label class="ss-q-help" for="entry_6">Please name the class as well as the assignment.</label>
<textarea name="entry.6.single" rows="8" cols="75" class="ss-q-long" id="entry_6"></textarea></div></div></div>
<br> <div class="errorbox-good">
<div class="ss-item ss-item-required ss-checkbox"><div class="ss-form-entry"><label class="ss-q-title" for="entry_4">Please name any TCP personnel you met with.

<span class="ss-required-asterisk">*</span></label>
<label class="ss-q-help" for="entry_4"></label>
<ul class="ss-choices">
';


$fq = mysql_query("SELECT * FROM `fellows` WHERE is_active='1' ORDER BY firstname ASC") or log_possibly_fatal_error('Error retrieving active fellows from database.  MySQL said: ' . mysql_error());

$i = 0;
while($fetch = mysql_fetch_object($fq)){
	$message .= '
		<li class="ss-choice-item">
			<label class="ss-choice-label">
				<input type="checkbox" name="entry.4.group" value="' . $fetch->firstname . ' ' . $fetch->lastname . '" class="ss-q-checkbox" id="group_4_' . ($i + 1) . '">
					' . $fetch->firstname . ' ' . $fetch->lastname . '
			</label>
		</li>
	';
	$i++;
}

$message .= '
		<li class="ss-choice-item">
			<label class="ss-choice-label">
				<input type="checkbox" name="entry.4.group" value="Other Fellow / Can\'t Remember" class="ss-q-checkbox" id="group_4_' . ($i + 2) . '">  Other Fellow / Can\'t Remember</label>
		</li>
</ul>
</div></div></div>
<br> <div class="errorbox-good">
<div class="ss-item ss-item-required ss-select"><div class="ss-form-entry"><label class="ss-q-title" for="entry_8">Did you send the Fellow your written material at least 24 hours in advance of the meeting?
<span class="ss-required-asterisk">*</span></label>
<label class="ss-q-help" for="entry_8"></label>
<select name="entry.8.single" id="entry_8"><option value="Yes, 24 hours in advance">Yes, 24 hours in advance</option> <option value="Sent, but not 24 hours in advance">Sent, but not 24 hours in advance</option> <option value="Not relevant;  this was not a written assignment">Not relevant;  this was not a written assignment</option> <option value="Didn&#39;t send material in advance.">Didn&#39;t send material in advance.</option></select></div></div></div>
<br> <div class="errorbox-good">

<div class="ss-item ss-item-required ss-select"><div class="ss-form-entry"><label class="ss-q-title" for="entry_10">Was a meeting with TCP required for this assignment?
<span class="ss-required-asterisk">*</span></label>
<label class="ss-q-help" for="entry_10"></label>
<select name="entry.10.single" id="entry_10"><option value="Yes">Yes</option> <option value="No">No</option></select></div></div></div>
<br> <div class="ss-item ss-item-required ss-grid"><div class="ss-form-entry"><label class="ss-q-title" for="entry_13">Evaluate the help your received from TCP.
<span class="ss-required-asterisk">*</span></label>
<label class="ss-q-help" for="entry_13">If you met with more than one Fellow, or met more than once, give a general evaluation and add a comment to clarify.</label>
<div class="errorbox-component errorbox-good"><table border="0" cellpadding="5" cellspacing="0"><thead><tr><td class="ss-gridnumbers" style="width: 24%;"></td>
<td class="ss-gridnumbers" style="width: 6%;"></td>
<td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">1Very useful/very true</label></td> <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">2 Useful/true</label></td> <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">3 Not  useful/not true</label></td> <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber">NA (I already had this under control)</label></td> <td class="ss-gridnumbers" style="width: 12%;"><label class="ss-gridnumber"></label></td>

<td class="ss-gridnumbers" style="width: 6%;"></td></tr></thead>
<tbody><div class="errorbox-good"><tr class="ss-gridrow ss-grid-row-odd"><td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">The Fellow helped me see how to improve overall organization of the document</td>
<td class="ss-gridrow" style="width: 6%;"></td>
<td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.15.group" value="1Very useful/very true" class="ss-q-radio" id="group_15_1"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.15.group" value="2 Useful/true" class="ss-q-radio" id="group_15_2"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.15.group" value="3 Not  useful/not true" class="ss-q-radio" id="group_15_3"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.15.group" value="NA (I already had this under control)" class="ss-q-radio" id="group_15_4"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.15.group" value="" class="ss-q-radio" id="group_15_5"></td>
<td class="ss-gridrow" style="width: 6%;"></td></tr></div> <div class="errorbox-good"><tr class="ss-gridrow ss-grid-row-even"><td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">The Fellow helped me improve grammar</td>
<td class="ss-gridrow" style="width: 6%;"></td>
<td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.16.group" value="1Very useful/very true" class="ss-q-radio" id="group_16_1"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.16.group" value="2 Useful/true" class="ss-q-radio" id="group_16_2"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.16.group" value="3 Not  useful/not true" class="ss-q-radio" id="group_16_3"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.16.group" value="NA (I already had this under control)" class="ss-q-radio" id="group_16_4"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.16.group" value="" class="ss-q-radio" id="group_16_5"></td>

<td class="ss-gridrow" style="width: 6%;"></td></tr></div> <div class="errorbox-good"><tr class="ss-gridrow ss-grid-row-odd"><td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">The Fellow helped me improve wording</td>
<td class="ss-gridrow" style="width: 6%;"></td>
<td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.17.group" value="1Very useful/very true" class="ss-q-radio" id="group_17_1"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.17.group" value="2 Useful/true" class="ss-q-radio" id="group_17_2"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.17.group" value="3 Not  useful/not true" class="ss-q-radio" id="group_17_3"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.17.group" value="NA (I already had this under control)" class="ss-q-radio" id="group_17_4"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.17.group" value="" class="ss-q-radio" id="group_17_5"></td>
<td class="ss-gridrow" style="width: 6%;"></td></tr></div> <div class="errorbox-good"><tr class="ss-gridrow ss-grid-row-even"><td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">The Fellow helped me focus the content </td>
<td class="ss-gridrow" style="width: 6%;"></td>
<td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.18.group" value="1Very useful/very true" class="ss-q-radio" id="group_18_1"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.18.group" value="2 Useful/true" class="ss-q-radio" id="group_18_2"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.18.group" value="3 Not  useful/not true" class="ss-q-radio" id="group_18_3"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.18.group" value="NA (I already had this under control)" class="ss-q-radio" id="group_18_4"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.18.group" value="" class="ss-q-radio" id="group_18_5"></td>

<td class="ss-gridrow" style="width: 6%;"></td></tr></div> <div class="errorbox-good"><tr class="ss-gridrow ss-grid-row-odd"><td class="ss-gridrow ss-leftlabel ss-gridrow-leftlabel" style="width: 24%;">The Fellow helped me with use of sources</td>
<td class="ss-gridrow" style="width: 6%;"></td>
<td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.19.group" value="1Very useful/very true" class="ss-q-radio" id="group_19_1"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.19.group" value="2 Useful/true" class="ss-q-radio" id="group_19_2"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.19.group" value="3 Not  useful/not true" class="ss-q-radio" id="group_19_3"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.19.group" value="NA (I already had this under control)" class="ss-q-radio" id="group_19_4"></td> <td class="ss-gridrow" style="width: 12%;"><input type="radio" name="entry.19.group" value="" class="ss-q-radio" id="group_19_5"></td>
<td class="ss-gridrow" style="width: 6%;"></td></tr></div></tbody></table></div></div></div>
<br> <div class="errorbox-good">
<div class="ss-item  ss-paragraph-text"><div class="ss-form-entry"><label class="ss-q-title" for="entry_20">Do you have any other comments or suggestions?
</label>
<label class="ss-q-help" for="entry_20"></label>
<textarea name="entry.20.single" rows="8" cols="75" class="ss-q-long" id="entry_20"></textarea></div></div></div>

<br>
<input type="hidden" name="pageNumber" value="0">
<input type="hidden" name="backupCache" value="">

<div class="ss-item ss-navigate"><div class="ss-form-entry">
<input type="submit" name="submit" value="Submit"></div></div></form>
<script type="text/javascript">
      
      (function() {
var divs = document.getElementById(\'ss-form\').
getElementsByTagName(\'div\');
var numDivs = divs.length;
for (var j = 0; j < numDivs; j++) {
if (divs[j].className == \'errorbox-bad\') {
divs[j].lastChild.firstChild.lastChild.focus();
return;
}
}
for (var i = 0; i < numDivs; i++) {
var div = divs[i];
if (div.className == \'ss-form-entry\' &&
div.firstChild &&
div.firstChild.className == \'ss-q-title\') {
div.lastChild.focus();
return;
}
}
})();
      </script></div>
<div class="ss-footer"><div class="ss-attribution"></div>
<div class="ss-legal"><span class="ss-powered-by">Powered by <a href="http://docs.google.com">Google Docs</a></span>
<span class="ss-terms"><small><a href="https://spreadsheets.google.com/reportabuse?formkey=dGdWU01BOG9xN1VmdjJxSHJNUXhqblE6MA&amp;source=https%253A%252F%252Fspreadsheets.google.com%252Fviewform%253Fformkey%253DdGdWU01BOG9xN1VmdjJxSHJNUXhqblE6MA">Report Abuse</a>
-
<a href="http://www.google.com/accounts/TOS">Terms of Service</a>

-
<a href="http://www.google.com/google-d-s/terms.html">Additional Terms</a></small></span></div></div></div></body></html>';

// Add MIME vsn and charset to support HTML
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: ' . $_firstname . ' ' . $_lastname . ' <'. $_email .">\r\n";
$headers .= 'From: Technical Communication Program <tcp@seas.upenn.edu>' . ">\r\n";

// Mail it
if(mail("", $subject, $message, $headers) != TRUE){
	log_possibly_fatal_error('An error occurred sending mail to ' . $_firstname . ' ' . $_lastname . ' (' . $_email . ').  The feedback request might not have been sent.');	
}


}	









?>
