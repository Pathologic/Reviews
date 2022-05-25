<!DOCTYPE html>
<html>
<head>
    <title>[%module.title%]</title>
    <link rel="stylesheet" type="text/css" href="[+manager_url+]media/style/[+theme+]/style.css"/>
    <link rel="stylesheet" href="[+manager_url+]media/style/common/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="[+site_url+]assets/js/easy-ui/themes/modx/easyui.css"/>
    <script type="text/javascript" src="[+manager_url+]media/script/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/js/easy-ui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/js/easy-ui/locale/easyui-lang-en.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/js/easy-ui/locale/easyui-lang-[+lang+].js"></script>
    [+lexicon+]
    <script type="text/javascript">
        var Config = {
            site_url: '[+site_url+]',
            url: '[+connector+]',
            kcfinder_url: '[+manager_url+]media/browser/mcpuk/browse.php?type=images'
        };
    </script>
    <script type="text/javascript" src="[+site_url+]assets/modules/reviews/js/module.js"></script>
    <style>
        .sectionBody > .tab-pane > .tab-page {
            margin-top: 0;
        }

        .datagrid-view td {
            vertical-align: middle;
        }

        .pagination td {
            font-size: 12px;
        }

        .formGroup {
            margin-bottom: 10px;
        }

        .btn-green {
            color: green;
        }

        .btn-red {
            color: red;
        }

        .datagrid {
            margin-top: 15px;
        }

        .delete, .delete:hover {
            color: red;
        }

        .panel-header, .panel-body {
            width: auto !important;
        }

        .datagrid-wrap {
            width: auto !important;
        }
        .help-block {
            font-size:11px;
            color:red;
        }
    </style>
</head>
<body>
<h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-bullhorn"></i>
  </span>
    <span class="pagetitle-text">
    [%module.title%]
  </span>
</h1>
<div id="actions">
    <div class="btn-group">
        <a id="Button1" class="btn btn-success" href="javascript:;" onclick="window.location.href='index.php?a=106';">
            <i class="fa fa-times-circle"></i><span>[%module.close%]</span>
        </a>
    </div>
</div>
<div class="sectionBody">
    <div class="dynamic-tab-pane-control tab-pane">
        <div class="tab-page">
            <table id="reviews" width="100%"></table>
        </div>
    </div>
</div>
<script>
    GridHelper.initGrid();
</script>
<script type="text/template" id="editFormTpl">
    <div id="editForm" style="padding:10px 1.25rem;">
        <form>
            <input type="hidden" name="formid" value="review">
            <input type="hidden" name="mode" value="edit">
            <input type="hidden" name="id" value="{%id%}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" data-field="name"><label><b>[%name%]</b></label><input name="name" class="form-control" value="{%name%}"></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" data-field="email"><label><b>Email</b></label><input name="email" class="form-control" value="{%email%}"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" data-field="review"><label><b>[%review%]</b></label><textarea class="form-control" name="review" style="height:150px;">{%review%}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"><label><b>[%image%]</b></label>
                        <div class="input-group"><input name="image" class="form-control" value="{%image%}">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="browseImage"><i class="fa fa-folder"></i></button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" id="viewImage"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom:10px;">
                <div class="col-md-4"><b>[%createdon%]:</b><br>{%createdon%}</div>
                <div class="col-md-4"><b>[%rate%]: </b>{%rate%}</div>
                <div class="col-md-4">
                    <div class="form-group"><label><b>[%publish%]:</b> <input type="checkbox" name="active" value="1" {%publish%}></label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</script>
</body>
</html>
