<!--#include file="util_include.asp" -->
<!--#include file="page_include.asp" -->
<%
IF Session("MM_Username") = "" Then Call Alert("�Э��s�n�J�C","login.html",0) End IF

if request("n") = "" then Call Alert("Ū�����~�C", 0,0) End IF
Dim SPCon
SPConOpen
allcity = "�򶩥�,�x�_��,�s�_��,�y����,��饫,�s�˿�,�s�˥�,�]�߿�,�]�ߥ�,�x����,���ƿ�,���ƥ�,�n�뿤,�Ÿq��,�Ÿq��,���L��,�x�n��,������,�̪F��,�Ὤ��,�x�F��,���,������,����,��q,����,��L"

if request("st") = "add" And request("totals") <> "" And request("n") <> "" Then
	totals = CInt(request("totals"))
	totals = totals-1
If totals >= 0 Then
  action_branch = ""
  set rs = Server.CreateObject("ADODB.Recordset")
  rs.open "select * from action_data where ac_auto='5937'", SPCon, 1, 1
  if not rs.eof then
  	action_branch = rs("ac_branch")
    action_title = rs("ac_title")
    action_note = rs("ac_note")
    action_time = rs("ac_time")  	
  end if
  rs.close
  
  if action_branch <> "" then
  For i = 0 To totals  
    For g = 1 To 21      
      execute("n"&g&"v = Split(request(""n"&g&"""),"","")(i)")
      execute("n"&g&"v = trim(n"&g&"v)")
      execute("response.write ""n"&g&"v-""&n"&g&"v&""<br>""")
	  next

    rs.Open "SELECT top 1 * FROM love_keyin",SPCon,1,3
    rs.addnew
    	  
	  rs("k_name") = n5v
	  rs("k_area") = n21v
	  rs("k_sex") = n6v
	  rs("k_mobile") = n16v	  
	  if isdate(n7v) then
	  rs("k_bday") = n7v
	  rs("k_year") = year(n7v)
	  end if
	  rs("k_job") = n10v
	  rs("k_school") = n11v
	  rs("k_marry") = n12v
	  if n19v <> "" then
	  rs("k_line") = n19v
	  end if
	  rs("action_branch") = action_branch
	  rs("action_title") = action_title
	  rs("action_note") = action_note
  	rs("action_time") = action_time
	  rs("all_kind") = "����"
	  rs("all_note") = "�L�Ѥ������ʶפJ"
	  rs("k_come") = "�e�~����23"	  
	  rs("send_time") = now()
    rs.update
    rs.close
	  
      
	  
  Next  
  end if
  set rs=Nothing
response.end
end if

  fpath = server.mappath("temp_excel\"&request("fpath"))
  rmfile(fpath)
  
  response.redirect "test23.asp"
response.end
End if
%>
			<%TOP%>
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						�޲z�t�� <span class="divider">/</span>
					</li>
					<li>
						<a href="test23.asp">23 - �Ĺ� Excel</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid">

				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> 23 - �Ĺ� Excel</h2>						
					</div>
					<div class="box-content">
						<%
						fpath = server.mappath("temp_excel\"&request("n"))
						set rs = Server.CreateObject("ADODB.Recordset")
						Set fs=Server.CreateObject("Scripting.FileSystemObject")
            Set f=fs.OpenTextFile(fpath, 1)  '���}�奻���
            Response.write "<form id=""applyform"" action=""?st=add"" method=""post"" target=""_self"">"
            response.write "<table class=""table table-striped table-bordered bootstrap-datatable"">"
            Response.Write "<tr><td>���W����</td><td>�|���O</td><td>��/�ƨ�</td><td>�����Ҧr��</td><td>�m�W</td><td>�ʧO</td><td>�ͤ�</td><td>�P�y</td><td>�A�ȳ��</td><td>¾��</td><td>�Ǿ�</td><td>�B��</td><td>����</td><td>����</td><td>�魫</td><td>��ʹq��</td><td>�~����</td></tr>"
            ii = 0
            
            do while f.AtEndOfStream = false
	strs=f.ReadLine
	splitstr=split(strs,",")  '���Τ@�檺�r�Ŧ�  
  
  if not ii = 0 and splitstr(4) <> "" then
  	
	Response.Write("<tr>")	
	
	err = 0
	sch = 0
	for i =0 to ubound(splitstr)
  vals = trim(splitstr(i))
  
  vals = replace(vals, " ", "")
  vals = replace(vals, "'", "")
  
  if i = 6 then
    if not isdate(vals) then    	
	  		err = 1
	  		errmsg = "�X�ͤ�����~"
    end if	
    v5y = ""
    if instr(vals, "/") > 0 then
    	v5y = split(vals, "/")(0)
    	if len(v5y) < 3 then
    		vals = cint(v5y)+1911&right(vals, len(vals)-len(v5y))
    	end if    	
    end if
    
  end if
  
  if i = 5 then
    if not vals = "�k" and not vals = "�k" then
	  		err = 1
	  		errmsg = "�ʧO���~�A�п�J�k/�k"
    end if  	
  end if
  
  mmobile = ""
  ss = "95%;"
  	mcn = 0
  	lcn = 0
  	
	  if i = 15 then
	  	if vals <> "" then
	  		vals = replace(vals, "_", "")
	  		vals = replace(vals, "-", "")
	  		vals = replace(vals, " ", "")	
	  		vals = replace(vals, "'", "")	  		
	  		vals = trim(vals)
	  	end if
	  	
	  	if not left(vals, 1) = "0" then
	  		vals = "0"&vals
	  	end if

	  	if not left(vals, 2) = "09" then
	  		err = 1
	  		errmsg = "��ʹq�ܫD09�}�Y"
	  	end if	  	
	  	
	  	if not len(vals) = 10 then
	  		err = 1
	  		errmsg = "��ʹq�ܫD10�X"
	  	end if
	  	
	  
	  end if
	  
	  

	  if i = 20 then
	  	ccc = 0
	  	for each cc in split(allcity, ",")
	  	  if vals = cc then
	  	  	ccc = 1
	  	  end if
	    next
	  	
	  	if ccc = 0 then
	  		err = 1
	  		errmsg = "�a�Ϥ��b�ثe�t�Φa�Ϥ�"
	  	end if
	  end if
	 

	 response.Write("<td>"&i&"")   '�إߪ��C
   Response.Write "<input type=""text"" name=""n"&i+1&""" id=""n"&i+1&""" value=""" & vals & """ style=""width:"&ss&";"">"
   response.Write("</td>")
   
   if i >= 20 then
   	exit for
   end if   
  next
  
  Response.Write("</tr>")
  
  if err = 1 then
  	response.write "<tr><td colspan=20 style=""color:red"">�W�榳���D - "&errmsg&"</td></tr>"    
  else
  	response.write "<tr><td colspan=20 style=""color:green"">�W��q�L����"&mmobile&"</td></tr>"  	
  end if
  end if
  ii = ii+1
  
loop

if err = 0 then
Response.write "<tr><input type=""hidden"" name=""totals"" value=""" & ii-1 & """><input type=""hidden"" name=""n"" value=""" & request("n") & """><td colspan=18 align=""left""><input type=""button"" value=""�פJ"" onclick=""sendform()""></td></tr>"
end if

response.write "</form>"
Response.Write("</table>")
f.Close
Set f=Nothing
Set fs=Nothing
set rs=nothing
						%>     
					</div>
				</div><!--/span-->
			 
			</div><!--/row-->


		<hr>
	</div><!--/.fluid-container-->

<%BOTTOM%>
<script>
function sendform() {
  $("#applyform").submit();
	return true;
}

</script>