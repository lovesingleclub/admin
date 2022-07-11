<?php 
require_once("_inc.php");
require_once("./include/_function.php");
?>
<STYLE TYPE="text/css">
    body,
    th,
    td,
    input,
    select,
    textarea,
    select,
    checkbox {
        font: 10pt '新細明體';
    }

    .a1:link {
        font: 10pt "新細明";
        text-decoration: none;
        color: #990066
    }

    .a1:visited {
        font: 10pt "新細明";
        text-decoration: none;
        color: #990066
    }

    a:link {
        font: 10pt"新細明";
        text-decoration: none;
        color: #0000FF
    }

    a:visited {
        font:10pt "新細明";
        text-decoration:none;
        color:#0000FF
    }

    a:active {
        font:10pt "新細明";
        text-decoration:none;
        color:#0000FF;
    }

    a:hover {
        font:10pt "新細明";
        text-decoration:underline;
        color: #ff0000;
    }

    .style14 {
        font-size:12px;
    }

    body {
        overflow-y:auto;
    }
</STYLE>
<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>

<body text="#333333" leftmargin="0" topmargin="0">
    <table width="660" border="0" cellspacing="0">
        <tr>
            <td width="660" valign="top">
                <table width="660" border="1">
                    <tr>
                        <td height="25" bgcolor="#336699">
                            <div align="center">
                                <font color="#990066" size="3"><strong>
                                        <font color="#FFFFFF">JACK - 春天會館工作日誌聯絡系統</font>
                                    </strong></font>
                            </div>
                        </td>
                    </tr>
                </table>
                <table width="660" border="1" cellpadding="1">
                    <tr>
                        <td height=200>目前無任何聯絡紀錄</td>
                    </tr>
                </table>
                <table width="660" border="1">
                    <tr>
                        <td height="25" bgcolor="#336699">
                            <div align="center">
                                <font color="#990066" size="3"><strong>
                                        <font color="#FFFFFF">新增回報紀錄</font>
                                    </strong></font>
                            </div>
                        </td>
                    </tr>
                </table>
                <form method="post" action="?st=send">
                    <table width="660" border="1" cellpadding="1">
                        <tr>
                            <td>處理情形</td>
                            <td>
                                <select name="log_type" id="log_type">
                                    <option value="一般事務">一般事務</option>
                                    <option value="持續聯絡">持續聯絡</option>
                                    <option value="已結案">已結案</option>
                                </select>　(請聯絡工程師新增處理情形)
                            </td>
                        </tr>
                        <tr>
                            <td>內容</td>
                            <td><input type="text" name="log_note" id="log_note" size=90></textarea></td>
                        </tr>
                        <tr>
                            <td>回報時間</td>
                            <td>2022/6/23 下午 02:43:34 由 JACK(JACK0906) 回報</td>
                        </tr>
                        <input type="hidden" name="an" value="12900">
                    </table>
            </td>
        </tr>
        <tr>
            <td align="center"><input type="submit"></td>
            </form>
        </tr>
    </table>
</body>

</html>

<script language="JavaScript">
    $(function() {});
</script>