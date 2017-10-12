
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49609771-3', 'libredesign.info');
  ga('send', 'pageview');



	/*
	 * Funtion used to open registration dialog
	 */
	function regRole(meetingDateValue, roleNameValue, event){
		$.ajax({
			type: "POST",
			url: "regrole.php",
			data:{meetingdate: meetingDateValue, rolename: roleNameValue},
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@gmail.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				showRegRolePropmpt(data, event);
			}
		});
	}

	function showRegRolePropmpt(responseText, event){
		$("#regPrompt").html(responseText);
		var leftedge    = document.documentElement.clientWidth-event.clientX;
		var bottomedge  = document.documentElement.clientHeight-event.clientY;

		if(leftedge < $("#regPrompt").width()){
			leftedge = (document.documentElement.scrollLeft + event.pageX - $("#regPrompt").width()) + "px";
		} else {
			leftedge = (document.documentElement.scrollLeft + event.pageX) + "px";
		}
		if(bottomedge < $("#regPrompt").height()){
//			alert("3_" + document.documentElement.clientHeight +"-"+event.clientY+"-"+$("#regPrompt").height()+ "-"+document.documentElement.scrollTop+"_"+event.pageY);
			bottomedge = (document.documentElement.scrollTop + event.pageY - $("#regPrompt").height()) + "px";
		} else {
//			alert("4_" + document.documentElement.clientHeight +"-"+event.clientY+"-"+$("#regPrompt").height());
			bottomedge = (document.documentElement.scrollTop + event.pageY) + "px";
		}
		$("#regPrompt").css({"left": leftedge, "top": bottomedge});
		$("#regPrompt").addClass("regBorder");
		$("#inputvalue").focus();
	}

	function closeRegPrompt(){
		$("#regPrompt").empty();
		$("#regPrompt").removeClass("regBorder");
	}



	function submitReg(meetingdate, rolename, inputkey, inputvalue, levelindex, projname){
		var inputKeys = new Array("CLUB ID", "NAME");

		var PHONE_NUMBER = "Phone Number";
		var EMAIL = "Email";

		var ccLevel = new Array("TM", "CC", "ACB", "ACS", "ACG", "DTM");
		var clLevel = new Array("TM", "CL", "ALB", "ALS", "DTM");
		var clubName = new Array("NO1", "ALU", "EIR", "Mandarin", "ET", "DNA", "OTHER", "Guest");
		var THEME_STR = "theme";
		var TME_STR = "tme";
		var SPEAKER_PREFIX = "speaker";
		var EVALUATOR_PREFIX = "evaluator";
		var CL_LIST = new Array("tme", "timer", "ahcounter", "grammarian", "ttm", "ge");
		var CLUB_ID = 0;
		var CLUB_NAME = 1;

		// To check input text can not be empty
		if(($("#"+inputkey).length <= 0) || ($("#"+inputkey).val() != 2)){
			if($("#"+inputvalue).val() == ""){
				if(rolename == THEME_STR)
					alert("Theme should not be ampty, thank you!");
				else {
					alert("Please input your " + inputKeys[$("#"+inputkey).val()==0?0:1] + " to register a role!!");
				}
				return;
			}
		} else {
			if(($("#"+inputvalue).val() == "") || ($("#phonenumber").val() == "") || ($("#email").val() == "")){
				alert("Name, PhoneNumber and Email should not be empty!");
				return;
			}
		}

		var postData;
		if(rolename == THEME_STR){
			postData = "meetingdate=" + meetingdate + "&keyrolename=" + rolename + "&transferValue="+$("#"+inputvalue).val();
		} else {
			var transferValue;
			var guestLevel = "";

			if($("#"+inputkey).val() != 2){
				transferValue = $("#"+inputvalue).val()
			} else {

				if($("#guestlevel_1").find("option:selected").text() == $("#guestlevel_2").find("option:selected").text()){
					guestLevel = ccLevel[$("#guestlevel_1").val()];
				} else {
					if($("#guestlevel_1").val() != 0){
						guestLevel += ccLevel[$("#guestlevel_1").val()];
					}

					if($("#guestlevel_2").val() != 0){
						if(guestLevel != ""){
							guestLevel += ", ";
						}
						guestLevel += clLevel[$("#guestlevel_2").val()];
					}
				}
				transferValue = $("#"+inputvalue).val();
//				transferValue = $("#"+inputvalue).val() + "_,_" + guestLevel + "_,_" + clubName[$("#clubname").val()];
			}

			if((rolename.indexOf(SPEAKER_PREFIX) != -1) || (rolename.indexOf(EVALUATOR_PREFIX) != -1) || ($.inArray(rolename, CL_LIST) != -1)){
				postData = "meetingdate=" + meetingdate + "&keyrolename=" + rolename + "&inputkey=" + $("#"+inputkey).val() +
							"&transferValue=" + transferValue + "&levelindex=" + $("#"+levelindex).val() + "&project=" + $("#"+projname).val();
				if(rolename.indexOf(TME_STR) != -1){
					if($("#themevalue").val() != "" && $("#themevalue").val() != undefined){
						postData = postData + "&themeValue=" + $("#themevalue").val();
					}
				}
			} else {
				postData = "meetingdate=" + meetingdate + "&keyrolename=" + rolename + "&inputkey=" + $("#"+inputkey).val() + "&transferValue=" + transferValue;
			}

			if($("#"+inputkey).val() == 2){
				postData += "&guestlevel=" + guestLevel + "&clubindex=" + $("#clubindex").val() + "&phonenumber=" + $("#phonenumber").val() + "&email=" + $("#email").val();
//				alert( $("#clubindex").val());
			}

//			if((rolename.indexOf(SPEAKER_PREFIX) == -1) && (rolename.indexOf(EVALUATOR_PREFIX) == -1) && (!$.inArray(rolename, CL_LIST))){
//				postData = "meetingdate=" + meetingdate + "&keyrolename=" + rolename + "&inputkey=" + $("#"+inputkey).val() + "&transferValue=" + transferValue;
//				if(rolename.indexOf(TME_STR) != -1){
//					if($("#themevalue").val() != "" && $("#themevalue").val() != undefined){
//						postData = postData + "&themeValue=" + $("#themevalue").val();
//					}
//				}
//			} else {
//				postData = "meetingdate=" + meetingdate + "&keyrolename=" + rolename + "&inputkey=" + $("#"+inputkey).val() + "&transferValue=" + transferValue + "&cclevel=" + $("#"+cclevel).val() + "&project=" + $("#"+projname).val();
//			}
		}

		if(rolename == THEME_STR)
			$("#regPrompt").html("Adding new Theme for regular meeting on " + meetingdate + " which is " + $("#"+inputvalue).val() +"......");
		else
			$("#regPrompt").html("Your role is submitted, please wait for the result.....");

		$.ajax({
			type: "POST",
			url: "submitrole.php",
			data: postData,
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				solveSubmitResponse(data);
			}
		});
	}

	function solveSubmitResponse(data){
		var response = parseInt(data);

		$("#regPrompt").empty();
		$("#regPrompt").removeClass("regBorder");

		var OK_CODE = 0;
		var ERROR_CODE_ALREADY_TAKEN = 1;
		var ERROR_CODE_NO_SUCH_USER_EXIT = 2;
		var ERROR_CODE_NOT_VALID_USER = 3;
		var ERROR_CODE_MYSQL_QUERY_FAIL = 4;
		var ERROR_CODE_RE_PARTICIPATE = 5;
		var WARNING_CODE_THEME_ALREADY_REG_OUTSIDE_TTM = 6;
		var ERROR_CODE_RE_PARTICIPATE_GUEST = 7;

		switch(response){
			case OK_CODE:
				reDrawAgendaTable();
				break;
			case ERROR_CODE_ALREADY_TAKEN:
				alert("This role is already taken by another people, please refresh the page to see available roles.\n Thank you!");
				break;
			case ERROR_CODE_NO_SUCH_USER_EXIT:
				alert("Please input a valid club id or member name, this one does not exist. \nIf you want to join our club, please contact VPM: Jerry Jiang, tojiangkun@qq.com");
				break;
			case ERROR_CODE_NOT_VALID_USER:
				alert("You haven't renew your membership in our club, please contact VPM for renew. VPM: Jerry Jiang, tojiangkun@qq.com");
				break;
			case ERROR_CODE_RE_PARTICIPATE:
				alert("You have already taken a role, give others a chance and have a rest!!");
				break;
			case ERROR_CODE_MYSQL_QUERY_FAIL:
				alert("Other error happens, please refresh the page and try again later.\n Thank you!");
				break;
			case WARNING_CODE_THEME_ALREADY_REG_OUTSIDE_TTM:
				alert("Theme is already registered not along with Table Topic Master, Only TTM is registered this time, please refresh the page to see the theme!");
				reDrawAgendaTable();
				break;
			case ERROR_CODE_RE_PARTICIPATE_GUEST:
				alert("This phone number is already used as registration of role this week, please make sure your input is correct!");
				reDrawAgendaTable();
				break;
			default:
				alert(data);
				break;
		}
	}

	function reDrawAgendaTable(){
		var centralMeetingOrder = $("#centralMeetingOrder").val();
		$.ajax({
			type: "POST",
			url: "redrawagendatable.php",
			data: {centralMeetingDateOrder: centralMeetingOrder},
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				redraw(data);
			}
		});
	}

	function redraw(data){
		$("#mainPageContainer").html(data);
	}

	function delRole(meetingDateValue, roleNameValue){
		if(!confirm("Are you sure to delete this role?")){
			return;
		}

		$.ajax({
			type: "POST",
			url: "delrole.php",
			data: {meetingdate: meetingDateValue, rolename: roleNameValue},
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				solveDelRole(data);
			}
		});
	}

	function solveDelRole(data){
		if(data == "Y"){
			reDrawAgendaTable();
		}
	}


	function resetRegForm(submitRole, inputKey, inputValue){
		var backupInputKey = $("#"+inputKey).val();
		var backupInputValue = $("#"+inputValue).val();
		$("#"+submitRole)[0].reset();
	}

	/*
	 * Update for the reg role div
	 */
	function inputKeyChange(inputkeyid, inputvalueid, exampleid){
		var CONST_INPUTKEY_VALUE_CLUBID = 0;
		var CONST_INPUTKEY_VALUE_NAME = 1;
		var CONST_INPUTKEY_VALUE_NOT_A_MEMBER = 2;

		var exampleContent = new Array("Example: 1 (1 is Lila Duanmu)","Example: Lila Duanmu (Ignore case)",
									   "Fullfill the information, name example: Lila Duanmu.<br> Don't know? Ask your VPE, or leave as default.");
//
//		var clubList = new Array("Nanjing NO.1 TMC",
//								 "Nanjing ALU TMC",
//								 "Nanjing Ericsson TMC",
//								 "Nanjing NO.1 Mandarin TMC",
//								 "Nanjing ET TMC",
//								 "Nanjing DNA TMC",
//								 "Another TMC...",
//								 "Invited Guest");

		var ccLevel = new Array("TM", "CC", "ACB", "ACS", "ACG", "DTM");
		var clLevel = new Array("TM", "CL", "ALB", "ALS", "DTM");

		$("#inputName").empty();
		var innerHTMLInputName = "";
		var innerHTMLOther = "";

		var inputKeyValue = document.getElementById(inputkeyid);
		if(inputKeyValue.value != CONST_INPUTKEY_VALUE_NOT_A_MEMBER){
			innerHTMLInputName += "<input type='text' style='width:250px' size='50' id='inputvalue' />&nbsp;<font color='red'>*</font><br />";
		} else {
			innerHTMLInputName += "<table>";

			innerHTMLInputName += "<tr><td style='width:95px'>Your Name:</td><td  colspan='2'><input type='text' style='width:200px' size='20' id='inputvalue' />&nbsp;<font color='red'>*</font></td></tr>";

			innerHTMLInputName += "<tr><td>Your Club:</td><td colspan='2'><select id='clubindex' style='width:206px'>";
//				for(var i=0; i < clubList.length; i++){
//					innerHTMLInputName += "<option value='"+i+"'>"+clubList[i]+"</option>";
//				}
			var clubInfoData = "tag=clublist";

			$.ajax({
				type: "POST",
				url: "retrievesimpleassistdata.php",
				data: clubInfoData,
				async: true,
				statusCode: {
					404: function() {
					    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
					}
				},
				success: function(data, textStatus){
					handleClubList(data, "clubindex");
				}
			});

			innerHTMLInputName += "</select></td></tr>";

			innerHTMLInputName += "<tr><td>Your</td><td>CC:&nbsp;&nbsp;&nbsp;&nbsp;<select id='guestlevel_1'>";
			for(var j = 0; j < ccLevel.length; j++){
				innerHTMLInputName += "<option value='"+j+"'>"+ccLevel[j]+"</option>";
			}

			innerHTMLInputName += "</select></td><td>CL:&nbsp;&nbsp;&nbsp;&nbsp;<select id='guestlevel_2'>";
			for(var k = 0; k < clLevel.length; k++){
				innerHTMLInputName += "<option value='"+k+"'>"+clLevel[k]+"</option>";
			}

			innerHTMLInputName += "</select></td></tr>";
//			innerHTMLInputName += "<tr><td colspan='3'><font color='red'><label id='example'></label></font></td></tr>"
			innerHTMLInputName += "</table>";
		}

		innerHTMLOther += "<table>";
		innerHTMLOther += "<tr><td colspan='2'><font color='red'><label id='example'></label></font></td></tr>";
		if(inputKeyValue.value == CONST_INPUTKEY_VALUE_NOT_A_MEMBER){
			innerHTMLOther +="<tr><td style='width:95px'>Phone Numer:</td><td><input type='text' id='phonenumber' style='width:200px' size='20'/>&nbsp;<font color='red'>*</font></td><tr>";
			innerHTMLOther +="<tr><td>Email:</td><td><input type='text' id='email' style='width:200px' size='50' />&nbsp;<font color='red'>*</font></td></tr>";
		}
		innerHTMLOther += "</table>";

		$("#inputName").html(innerHTMLInputName);
		$("#inputOther").html(innerHTMLOther);
		$("#"+inputvalueid).val("");
		$("#"+inputvalueid).focus();
		$("#"+exampleid).html(exampleContent[inputKeyValue.value]);

		if($("#cclevel").length > 0){
			$("#cclevel").get(0).options[0].selected = true;
		}

		if($('#cllevel').length > 0){
			$('#cllevel').get(0).options[0].selected = true;
		}

		if($('#ccproject').length > 0){
			$('#ccproject').get(0).options[0].selected = true;
		}

		if($('#clproject').length > 0){
			$('#clproject').get(0).options[0].selected = true;
		}
	}

	function handleClubList(JSONText, elementId){
		if(JSONText === "-1"){
			$("#"+elementId).html("Error to get information from the server, please try it later!");
			return;
		}

		var clubFullNameIndex = 0;
		var clubShortNameIndex = 1;

		var anotherTMCIndexStr = "0";
		var guestIndexStr = "1";

		var jsonObj = $.parseJSON(JSONText);

		var optionHtml = "";

		for(var index in jsonObj){
			if((index !== anotherTMCIndexStr) && (index != guestIndexStr))
			optionHtml += "<option value='" + index + "'>" + jsonObj[index][clubFullNameIndex] + "</option>";
		}

		optionHtml += "<option value='" + anotherTMCIndexStr + "'>" + jsonObj[anotherTMCIndexStr][clubFullNameIndex] + "</option>";
		optionHtml += "<option value='" + guestIndexStr + "'>" + jsonObj[guestIndexStr][clubFullNameIndex] + "</option>";

		$("#"+elementId).html(optionHtml);
	}

	function changeccProject(){
		var optionCount = $("#project option").length;
		$("#ccproject").get(0).options[0].selected = true;
	}

	function changeclProject(){
		$("#clproject").get(0).options[0].selected = true;
	}

	function changeColorMouseOverOrOut(idName, noOfAgendasInOneScreen, isOn){
		for(index = 0; index <= noOfAgendasInOneScreen; index++){
			if(index == 0){
				if(isOn){
					$("#"+idName+index).css({background: "#90A4BE"});
				} else {
					$("#"+idName+index).css({background: "#B0C4DE"});
				}
			}
			else{
				if(isOn){
					$("#"+idName+index).css({background: "#C6C6DA"});
				} else {
					$("#"+idName+index).css({background: "#E6E6FA"});
				}
			}
		}
	}

	function setToFoot(idName, relativeToIdName, relativeTo){
		if($("#" + idName) != undefined && $("#" + relativeToIdName) != undefined){
			if(($(window).height() - parseInt($("#" + idName).css("line-height"))) >
					($("#" + relativeToIdName).offset().top + parseInt($("#" + relativeToIdName).height()))){
				$("#" + idName).css({top: $(document).height() - parseInt($("#" + idName).css("line-height")), position: "absolute"});
			} else {
				if(relativeTo){
					$("#" + idName).css({top: ($("#" + relativeToIdName).height() +30) + "px", position: "relative"});
				} else {
					$("#" + idName).css({top: "30px", position: "relative"});
				}
			}
		}
	}

	function setComponentWidth(componentID, widthValue){
		if($(window).width() < widthValue){
			if($("#" + componentID) != undefined){
				$("#" + componentID).css({width: widthValue + "px"});
			}
		} else {
			if($("#" + componentID) != undefined){
				$("#" + componentID).css({width: "100%"});
			}
		}
	}

	function resizeComponents(comArray, widthValue){
		if(comArray instanceof Array){
			for(index = 0; index < comArray.length; index++){
				setComponentWidth(comArray[index], widthValue);
			}
		}
	}

	function singleRoleContentAction(idName, idRoleIntro, isOn){
		if($("#" + idRoleIntro).val() == 0){
			if(isOn){
				$("#" + idName).css({background: "#F8F8FF"});
			} else {
				$("#" + idName).css({background: "#FFFFFF"});
			}
		}
	}

	function clickRoleIntro(roleId, roleIntroId, idIndex, numOfAllResponse){
		if($("#" + roleIntroId + idIndex).val() == 0){
			$("#" + roleIntroId + idIndex).val(1);
			$("#" + roleId  + idIndex).css({background: "#F8F8FF"});
		} else {
			$("#" + roleIntroId + idIndex).val(0);
			$("#" + roleId + idIndex).css({background: "#FFFFFF"});
		}

		for(index = 0; index < numOfAllResponse; index++){
			if(index != idIndex){
				$("#" + roleIntroId + index).val(0);
				$("#" + roleId + index).css({background: "#FFFFFF"});
			}
		}
	}

	function setBackToTopPosition(idName){
		topPosition = $(window).scrollTop() + $(window).height() - $("#" + idName).height() -50 + "px";
		leftPosition = $(window).scrollLeft() + $(window).width() - $("#" + idName).width() - 40  + "px";
		$("#" + idName).css({top: topPosition, left: leftPosition});
	}

	function setToTop(){
		$(window).scrollTop(0);
		$(window).scrollLeft(0);
	}

	/**
	 *
	 * @param userName
	 * @param password
	 * @param passwordMD5
	 */
	function checkBeforeLogin(userName, password, passwordMD5){
		if($("#" + userName).val() == ""  || $("#" + password).val() == ""){
  			alert("Please input the User Name or Password to login!!");
  			return false;
  		} else {
  			$("#" + passwordMD5).val(hex_md5($("#" + password).val()));
			$("#" + password).val("");
			return true;
		}
	}

	function mouseOverorOutMemberLine(idName, index, isOn, isValidMember){
		if(isOn){
			$("#" + idName).css({background: "#98FB98"});
		} else {
			$("#" + idName).parent().children().each(function(i){
				if(i != 0){
					if($(this).is($("#" + idName))){
						if(i % 2 == 0){
							$("#" + idName).css({background: "#B0C4DE"});
						} else {
							$("#" + idName).css({background: "#E6E6FA"});
						}
					}
				}
			});
		}
	}

	function editMemberLine(trID, hiddenID, happyBirthdayId, showAllMembersTag){
		var inputKeys_All = new Array("editKey", "clubId", "memberId", "name", "validStatus", "cc", "cl", "chineseName", "email", "phoneNo", "qq", "weiboId", "birthday", "pcc", "pcl");

		var CONST_EDITKEY_INDEX = 0;
		var CONST_CLUBID_INDEX = 1;
		var CONST_MEMBERID_INDEX = 2;
		var CONST_NAME_INDEX = 3;
		var CONST_CHINESENAME_INDEX = 7;
		var CONST_EMAIL_INDEX = 8;
		var CONST_PHONENO_INDEX = 9;
		var CONST_QQ_INDEX = 10;
		var CONST_WEIBOID_INDEX = 11;
		var CONST_BIRTHDAY_INDEX = 12;

		var CONST_EDIT_KEY_NA = 0;
		var CONST_EDIT_KEY_ONESELF = 1;
		var CONST_EDIT_KEY_NORMAL_ADMIN = 2;
		var CONST_EDIT_KEY_SUPER_ADMIN = 3;

		var hiddenValue = $("#" + hiddenID).val();
		var valueArray = hiddenValue.split("::");

		$("#" + trID).empty();

//		$("#" + trID).append("<form name='updateMember" + valueArray[CONST_CLUBID_INDEX] + "'>");
		$("#" + trID).append("<input type='hidden' name='" + inputKeys_All[CONST_EDITKEY_INDEX] + "' id='" + inputKeys_All[CONST_EDITKEY_INDEX] + valueArray[CONST_CLUBID_INDEX] + "' value='" + valueArray[CONST_EDITKEY_INDEX] + "' />");
		if(valueArray[CONST_EDITKEY_INDEX] == CONST_EDIT_KEY_ONESELF){
			for(var index = CONST_CLUBID_INDEX; index < valueArray.length; index++){
				if(index == CONST_NAME_INDEX || index == CONST_CHINESENAME_INDEX || index == CONST_EMAIL_INDEX ||
						index == CONST_PHONENO_INDEX || index == CONST_QQ_INDEX || index == CONST_WEIBOID_INDEX || index == CONST_BIRTHDAY_INDEX){
					$("#" + trID).append("<td><input style=\"width: 90%\" type=\"text\" value=\"" + valueArray[index] + "\" name=\"" + inputKeys_All[index] + "\" id=\"" + inputKeys_All[index] +  + valueArray[CONST_CLUBID_INDEX] + "\" /></td>");
				} else {
					$("#" + trID).append("<td>" + valueArray[index] + "</td>");
				}
			}
		} else if(valueArray[CONST_EDITKEY_INDEX] == CONST_EDIT_KEY_NORMAL_ADMIN){
			for(var index = CONST_CLUBID_INDEX; index < valueArray.length; index++){
				if(index != CONST_CLUBID_INDEX && index != CONST_MEMBERID_INDEX){
					$("#" + trID).append("<td><input style=\"width: 90%\" type=\"text\" value=\"" + valueArray[index] + "\" name=\"" + inputKeys_All[index] + "\" id=\"" + inputKeys_All[index] +  + valueArray[CONST_CLUBID_INDEX] + "\" /></td>");
				} else {
					$("#" + trID).append("<td>" + valueArray[index] + "</td>");
				}
			}
		} else if(valueArray[CONST_EDITKEY_INDEX] == CONST_EDIT_KEY_SUPER_ADMIN){
			for(var index = CONST_CLUBID_INDEX; index < valueArray.length; index++){
				$("#" + trID).append("<td><input style=\"width: 90%\" type=\"text\" value=\""+valueArray[index] + "\" name=\"" + inputKeys_All[index] + "\" id=\"" + inputKeys_All[index] +  + valueArray[CONST_CLUBID_INDEX] + "\" /></td>");
			}
		} else {
			for(var index = CONST_CLUBID_INDEX; index < valueArray.length; index++){
				$("#" + trID).append("<td>" + valueArray[index] + "</td>");
			}
		}

		$("#" + trID).append("<td><input type='submit' value='OK' onClick='updateMemberInfo(" + valueArray[CONST_CLUBID_INDEX] +", \"" + trID + "\", \"" + hiddenID + "\", " + valueArray[CONST_EDITKEY_INDEX] + ", \"" + happyBirthdayId + "\", " + (showAllMembersTag ? "true" : "false") + ")' />");
//		$("#" + trID).append("</form>");
	}

	function updateMemberInfo(clubId, trId, hiddenId, editAbleKey, happyBirthdayId, showAllMembersTag){
		if(!confirm("Are you sure to update the information of this member?")){
			return;
		}

		var inputKeys_All = new Array("editKey", "clubId", "memberId", "name", "validStatus", "cc", "cl", "chineseName", "email", "phoneNo", "qq", "weiboId", "birthday", "pcc", "pcl");

		var TABLE_KEY = "cId";
		var TR_ID = "trId";
		var HIDDEN_ID = "hiddenId";
		var HAPPY_BIRTHDAY_ID = "happyBirthdayId"
		var SHOW_ALL_MEMBERS_TAG_KEY = "sAllMembers";

		var CONST_EDITKEY_INDEX = 0;
		var CONST_CLUBID_INDEX = 1;
		var CONST_MEMBERID_INDEX = 2;
		var CONST_NAME_INDEX = 3;
		var CONST_VALIDSTATUS_INDEX = 4;
		var CONST_CHINESENAME_INDEX = 7;
		var CONST_EMAIL_INDEX = 8;
		var CONST_PHONENO_INDEX = 9;
		var CONST_QQ_INDEX = 10;
		var CONST_WEIBOID_INDEX = 11;
		var CONST_BIRTHDAY_INDEX = 12;

		var CONST_EDIT_KEY_NA = 0;
		var CONST_EDIT_KEY_ONESELF = 1;
		var CONST_EDIT_KEY_NORMAL_ADMIN = 2;
		var CONST_EDIT_KEY_SUPER_ADMIN = 3;

		var lineToRemove = false;

		postData = TR_ID + "=" + trId + "&" + HIDDEN_ID + "=" + hiddenId + "&" + TABLE_KEY + "=" + clubId + "&" + inputKeys_All[CONST_EDITKEY_INDEX]
			+ "=" + $("#" + inputKeys_All[CONST_EDITKEY_INDEX] + clubId).val() + "&" + HAPPY_BIRTHDAY_ID + "=" + happyBirthdayId + "&" + SHOW_ALL_MEMBERS_TAG_KEY + "=" + (showAllMembersTag ? "true" : "false");

		if(editAbleKey == CONST_EDIT_KEY_SUPER_ADMIN){
			for(var index = CONST_CLUBID_INDEX; index < inputKeys_All.length; index++){
				if($("#" + inputKeys_All[index] + clubId).val() == ""){
					alert("Please full fill all the fields!");
					return;
				} else {
					postData = postData + "&" + inputKeys_All[index] + "=" + $("#" + inputKeys_All[index] + clubId).val();
				}

				if(index == CONST_VALIDSTATUS_INDEX){
					if(!showAllMembersTag && ($("#" + inputKeys_All[index] + clubId).val() == 0)){
						lineToRemove = true;
					} else {
						var suffix = $("#" + trId).prop("class").substring($("#" + trId).prop("class").length-1)
						if($("#" + inputKeys_All[index] + clubId).val() == 1){
							$("#" + trId).removeClass("memberInvalid" + suffix);
							$("#" + trId).addClass("memberLine" + suffix);
						} else if ($("#" + inputKeys_All[index] + clubId).val() == 0){
							$("#" + trId).removeClass("memberLine" + suffix);
							$("#" + trId).addClass("memberInvalid" + suffix);
						}
					}
				}
			}
		} else if(editAbleKey == CONST_EDIT_KEY_NORMAL_ADMIN){
			for(var index = CONST_CLUBID_INDEX; index < inputKeys_All.length; index++){
				if(index != CONST_CLUBID_INDEX && index != CONST_MEMBERID_INDEX){
					if($("#" + inputKeys_All[index] + clubId).val() == ""){
						alert("Please full fill all the fields!");
						return;
					} else {
						postData = postData + "&" + inputKeys_All[index] + "=" + $("#" + inputKeys_All[index] + clubId).val();
					}
				}
			}
		} else if(editAbleKey == CONST_EDIT_KEY_ONESELF){
			for(var index = CONST_CLUBID_INDEX; index < inputKeys_All.length; index++){
				if(index == CONST_NAME_INDEX || index == CONST_CHINESENAME_INDEX || index == CONST_EMAIL_INDEX ||
						index == CONST_PHONENO_INDEX || index == CONST_QQ_INDEX || index == CONST_WEIBOID_INDEX || index == CONST_BIRTHDAY_INDEX){
					if($("#" + inputKeys_All[index] + clubId).val() == ""){
						alert("Please full fill all the fields!");
						return;
					} else {
						postData = postData + "&" + inputKeys_All[index] + "=" + $("#" + inputKeys_All[index] + clubId).val();
					}
				}
			}
		} else {
			return;
		}

		$.ajax({
			type: "POST",
			url: "updatememberinfo.php",
			data: postData,
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				reDrawMemberLine(data, clubId, trId, happyBirthdayId, showAllMembersTag, lineToRemove);
			}
		});
	}

	function reDrawMemberLine(backData, clubId, trId, happyBirthdayId, showAllMembersTag, lineToRemove){
		if(lineToRemove){
			var parentObject = $("#" + trId).parent();
			$("#" + trId).remove();
			parentObject.children().each(function(i){
				if(i != 0){
					if(i % 2 ==0){
						$(this).css({background: "#B0C4DE"});
					} else {
						$(this).css({background: "#E6E6FA"});
					}
				}
			});
		} else {
			reDrawComponentContent(backData, trId);
		}

		$.ajax({
			type: "POST",
			url: "updatepartmemberinfo.php",
			async: false,
			data: {updateTag: "birthday", cId: clubId, sAllMembers: (showAllMembersTag ? "true" : "false")},
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				reDrawComponentContent(data, happyBirthdayId);
			}
		});
	}

	function reDrawComponentContent(backData, componentId){
		$("#" + componentId).empty();
		$("#" + componentId).html(backData);
	}

	function showMembers(containerId, clubId, showAllMembersTag){
		$.ajax({
			type: "POST",
			url: "updatepartmemberinfo.php",
			data: {updateTag: "All", cId: clubId, sAllMembers: (showAllMembersTag ? "true" : "false")},
			async: false,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				reDrawComponentContent(data, containerId);
			}
		});
	}

	function addANewMember(){
		alert("Under Implementation!");
	}

	function changeColorMeetingNotRegular(idName, mouseOver){
		if(mouseOver){
			$("#" + idName).css({background: "#C1FFC1", color: "#9400D3"});
		} else {
			$("#" + idName).css({background: "#B0E0E6", color: "#0000CD"});
		}
	}

	function closeMeeting(){
		alert("alala");
	}



	/**
	 * These functions are used for managing agendas, selector action, button actions
	 */
	function actionDateSelectedForAgendaManage(){

	}

	function updateAgenda(formId, roleToBeChosenPrefix){
		var $jsonObject;

		var postData = "tag=nonduplicatedroles";

		$.ajax({
			type: "POST",
			url: "retrievesimpleassistdata.php",
			data: postData,
			async: true,
			statusCode: {
				404: function() {
					alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				if(!checkRolesAreBookedDuplicated(data, roleToBeChosenPrefix)){
					$("#" + formId).submit();
				}
			}
		});
	}

	function checkRolesAreBookedDuplicated(JSONText, roleToBeChosenPrefix){
		if(JSONText === "-1"){
			alert("Error to get information from the server, please try it later!");
			return true;
		} else if(JSONText === "-2"){
			alert("Error info provided, please check it again!!");
			return true;
		}

		jsonObj = $.parseJSON(JSONText);

		for(var keyToCompare in jsonObj){
			for(var keyCompareTo in jsonObj){
				if((keyToCompare != keyCompareTo)
						&& ($("#" + roleToBeChosenPrefix + keyToCompare).val() == $("#" + roleToBeChosenPrefix + keyCompareTo).val())
						&& ($("#" + roleToBeChosenPrefix + keyToCompare).val() != 0)){
					alert(jsonObj[keyToCompare] + " and " + jsonObj[keyCompareTo] + " couldn't be the same person, please check!");
					return true;
				}
			}
		}

		return false;
	}

	function closeReopen(){
		alert("Close and Reopen happy");
	}

	function notAMemberCheckOn(checkBoxId){
		var isCheckedOn = $("#"+checkBoxId).prop("checked");

		if(isCheckedOn == true){
			alert("Checked");
		} else {
			alert("Unchecked");
		}
	}

	function memberNameChangeOnManageAgendaPage(roleKey, myId, levelId, projectId){
		//1. To know whether this role is allowed to be duplicated
		//2. To see whether this role is submitted duplicatedly

		var CONST_NA_VALID_CLUB_ID = 0;

		var clubId = $("#"+myId).val();

		if(clubId == CONST_NA_VALID_CLUB_ID){
			 $("#" + levelId).prepend("<option value='TM'>-</option>");
			 $("#" + levelId + " option[value='TM']").prop("selected", true);
			 $("#" + levelId).prop("disabled", true);

			 $("#" + projectId).prepend("<option value='0'>-</option>");
			 $("#" + projectId + " option[value='0']").prop("selected", true);
			 $("#" + projectId).prop("disabled", true);
		} else {
			$("#" + levelId + " option[value='TM']").remove();
			$("#" + projectId + " option[value='0']").remove();

			var postData = "tag=levelandprojectupdate&clubid="+clubId+"&typeofrole="+roleKey;

			$.ajax({
				type: "POST",
				url: "retrievesimpleassistdata.php",
				data: postData,
				async: true,
				statusCode: {
					404: function() {
						alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
					}
				},
				success: function(data, textStatus){
					handleClubMemberNameChangeAfterReceive(data);
				}
			});

			$("#" + levelId).prop("disabled", false);
			$("#" + projectId).prop("disabled", false);
		}
	}


	function meetingDateChangeOfAgendaManage(formId){
		$("#" + formId).submit();
	}

	// if want to change the cc or cl level, reset the project from default option
	function levelChangeListener(projectId){
		$("#"+projectId).val($("#"+projectId).prop("defaultSelected"));
	}

	/**
	 * This function is used for the regRole prompt for the main page
	 * @param selectId
	 * @param typeOfRoleName
	 */
	function memberNameListChange(selectId, typeOfRoleName){
		var postdata = "tag=cccllevel&clubid="+$("#"+selectId).val()+"&typeofrole="+typeOfRoleName;

		$.ajax({
			type: "POST",
			url: "retrievesimpleassistdata.php",
			data: postdata,
			async: true,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				handleClubMemberNameChangeAfterReceive(data);
			}
		});
	}

	/**
	 * This is a common used function, as the id and value are returned from server
	 * This function can be used for different places
	 * @param JSONText
	 */
	function handleClubMemberNameChangeAfterReceive(JSONText){
		if(JSONText === "-1"){
			alert("Error to get information from the server, please try it later!");
			return;
		} else if(JSONText === "-2"){
			alert("Error info provided, please check it again!!");
			return;
		}

		jsonObj = $.parseJSON(JSONText);

		$("#"+jsonObj[0]+" option[value='"+jsonObj[1]+"']").prop("selected", true);
		$("#"+jsonObj[2]+" option[value='"+jsonObj[3]+"']").prop("selected", true);
	}


	function submitContestReg(regContestInputContainerId, regContestNameInputId, checkBoxSpeechRegListIdPrefix, numOfCheckedBox){

		// Simple check before submit the data to server
		if($("#"+regContestNameInputId).val() == ""){
			alert("Please input your name before submit");
			return;
		}

		anySpeechContestSelected = false;

		for(checkIndex = 0; checkIndex < numOfCheckedBox; checkIndex++){
			if($("#"+checkBoxSpeechRegListIdPrefix+checkIndex).prop("checked")){
				anySpeechContestSelected = true;
				break;
			}
		}

		if(!anySpeechContestSelected){
			alert("Please choose at least one speech contest");
			return;
		}

		// Begin to handle reg data

		var contestValueArray = [];

		contestValueArray.push($("#"+regContestNameInputId).val());

		for(index = 0; index < numOfCheckedBox; index++){
			speechValueItem = $("#"+checkBoxSpeechRegListIdPrefix+index).val() + ":" + ($("#"+checkBoxSpeechRegListIdPrefix+index).prop("checked")?"1":"0");
			contestValueArray.push(speechValueItem);
		}

		$.ajax({
			type: "POST",
			url: "speechcontestreg.php",
			data: {"contestValueArray[]" : contestValueArray},
			async: true,
			traditional:true,
			statusCode: {
				404: function() {
				    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
				}
			},
			success: function(data, textStatus){
				handleAfterContestInfoSubmitted(data, regContestInputContainerId);
			}
		});
	}

	function handleAfterContestInfoSubmitted(data, regContestInputContainerId){
		var response = parseInt(data);

		var OK_CODE = 0;
		var ERROR_CODE_NO_SUCH_USER_EXIT = 1;

		switch(response){
			case OK_CODE:
				$("#" + regContestInputContainerId).html("Your registrition data has been updated successfully.<br>" +
						"Please <a href='login.php'>log in</a> to see the names for each contest.<br>");
				break;
			case ERROR_CODE_NO_SUCH_USER_EXIT:
				alert("Please input a valid club id or member name, this one does not exist. \nIf you want to join our club, please contact VPM: Jerry Jiang, tojiangkun@qq.com");
				break;
		}
	}

	function officerDutyLinkListener(sourceInputKey, formContainerId){
		var inputKeys = new Array("OU", "SD", "CM");
		var suffix = "_link";

		const OU = 0;
		const SD = 1;
		const CM = 2;

		for(var i = 0 ; i < inputKeys.length; i++){
			if(sourceInputKey == (inputKeys[i] + suffix)){
				switch(i){
				case OU:
					$.ajax({
						type: "POST",
						url: "officerupdate.php",
						data: "tag=get" + "&value=" + inputKeys[i],
						async: true,
						statusCode: {
							404: function() {
							    alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
							}
						},
						success: function(data, textStatus){
							handleAfterOfficerResponse(data, formContainerId);
						}
					});
          // $("#"+formContainerId).prop("hidden", false);
					break;
				case SD:
					break;
				case CM:
					break;
				default:
					alert("Not Possible, error happened!!");
					break;
				}
			}
		}
	}

	function getOfficerUpdateForm(){
		var officerUpdateFormText = "<form name='officerUpdateFormName' id='officerUpdateFormId'>";
		officerUpdateFormText += "</form>";
	}

	function handleAfterOfficerResponse(data, formContainerId){
    // alert(data);
		$("#" + formContainerId).html(data);
	}

	function submitUpdateOfficer(formContainerId){
    var startDateStr = "startDate";
    var expireDateStr = "expireDate";
    var officerName = new Array("President", "VPE", "VPM", "VPPR", "SAA", "Treasurer", "Secretary");

    var value = "tag=update";

    value = value + "&" + startDateStr + "=" + $("#" + startDateStr).val() + "&" + expireDateStr + "=" + $("#" + expireDateStr).val();
    for(var i = 0; i < officerName.length; i++){
      if($("#" + officerName[i]).val() !== undefined){
        value = value + "&" + officerName[i] + "=" + $("#" + officerName[i]).val();
      }
    }

    alert(value);

    $.ajax({
      type: "POST",
      url: "officerupdate.php",
      data: value,
      async: true,
      statusCode: {
        404: function() {
            alert('It seems something wrong happens in server side, please contact tojiangkun@qq.com to fix it!!');
        }
      },
      success: function(data, textStatus){
        handleAfterOfficerUpdateSubmit(data);
      }
    });
	}

  function handleAfterOfficerUpdateSubmit(data){
    alert("handleAfterOfficerUpdateSubmit");
    alert(data);
  }
