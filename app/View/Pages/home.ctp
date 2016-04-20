<?php echo $this->Html->script('adminView', array('inline' => false)); ?>
<h1><?= $settings['project_label']; ?> Database</h1>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#subjects" role="tab" data-toggle="tab">Subjects</a></li>
            <li><a href="#users" role="tab" data-toggle="tab">Users</a></li>
            <li><a href="#groups" role="tab" data-toggle="tab">Groups</a></li>
            <li><a href="#sites" role="tab" data-toggle="tab">Sites</a></li>
            <li><a href="#settings" role="tab" data-toggle="tab">Settings</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="subjects">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?= $settings['external_id_label']; ?></th>
                                    <th>Assigned To</th>
                                    <th>Site</th>
                                    <th>Site Ratio</th>
                                    <th>Added By</th>
                                    <th>Created</th>
                                    <th>Invalidated</th>
                                    <th>Last Modifier</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: subjects">
                                <tr data-bind='click: $root.selectSubject, clickBubble: false'>
                                    <td data-bind="text: Subject.external_id"></td>
                                    <td data-bind="text: Group.group_name"></td>
                                    <td data-bind="text: Site.site_name"></td>
                                    <td data-bind="text: Site.site_ratio"></td>
                                    <td data-bind="text: User.fullname"></td>
                                    <td data-bind="text: Subject.created"></td>
                                    <td data-bind="text: Subject.record_invalid"></td>
                                    <td data-bind="text: Disabler.username == null ? '' : Disabler.fullname + ' ('+Disabler.username+ ')'"></td>
                                </tr>
                            </tbody>                    
                        </table>    
                        <!-- ko if: subjectPaging() && subjectPaging().prevPage === true -->
                        <button type="button" data-bind="click:lastSubjectsClick" class="btn btn-default">Previous 10</button>
                        <!-- /ko -->
                        <!-- ko if: subjectPaging() && subjectPaging().nextPage === true -->
                        <button type="button" data-bind="click:nextSubjectsClick" class="btn btn-default">Next 10</button>
                        <!-- /ko -->
                        <p><a href='<?= $baseURL?>/subjects/export.csv'>Export as CSV</a></p>
                    </div>
                    <div class="row" data-bind='with: selectedSubject'>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle='collapse' href='#EditSubjectPanelBody'>Edit Subject</a>
                                    </h3>
                                </div>
                                <div id='editSubjectPanelBody' class="panel-body collapse in">
                                    <form id="editSubjectForm" class="form-horizontal">
                                        <fieldset>
                                            <div class="checkbox">
                                                <label class='col-md-4'>
                                                    <input type="checkbox" name='record_invalid' data-bind='checked: Subject.record_invalid'> Invalidate Subject Record?
                                                </label>
                                            </div>

                                            <!-- Button (Double) -->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="submitEditSubject"></label>
                                                <div class="col-md-8">
                                                    <button id="submitEditSubject" name="submitEditSubject" class="btn btn-primary" data-bind='click: $root.saveEditSubject'>Save Changes</button>
                                                    <button id="cancelEditSubject" name="cancelEditSubject" class="btn btn-danger" data-bind='click: $root.cancelEditSubject'>Cancel</button>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </form>
                                    <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.subjectError"></p>
                                    <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.subjectResult"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="users">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Created</th>
                                    <th>Modified By</th>
                                    <th>Disabled?</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: users">
                                <tr data-bind='click: $root.selectUser, clickBubble: false'>
                                    <td data-bind="text: User.username"></td>
                                    <td data-bind="text: User.fullname"></td>
                                    <td data-bind="text: User.email"></td>
                                    <td data-bind="text: User.created"></td>
                                    <td data-bind="text: (ModifiedBy) ? ModifiedBy.username : 'N/A'"></td>
                                    <td data-bind="text: User.disabled"></td>'
                                </tr>
                            </tbody>
                        </table>   
                        <!-- ko if: userPaging() && userPaging().prevPage === true -->
                        <button type="button" data-bind="click:lastUsersClick" class="btn btn-default">Previous 10</button>
                        <!-- /ko -->
                        <!-- ko if: userPaging() && userPaging().nextPage === true -->
                        <button type="button" data-bind="click:nextUsersClick" class="btn btn-default">Next 10</button>
                        <!-- /ko -->
                    </div>
                    <div class="row" data-bind='with: selectedUser'>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle='collapse' href='#EditUserPanelBody'>Edit User</a>
                                    </h3>
                                </div>
                                <div id='editUserPanelBody' class="panel-body collapse in">
                                    <form id="editUserForm" class="form-horizontal">
                                        <fieldset>
                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="fullname">Full Name</label>  
                                                <div class="col-md-4">
                                                    <input id="fullname" name="fullname" data-bind='value:User.fullname' type="text" placeholder="First I. Last" class="form-control input-md" required="">
                                                    <span class="help-block">User's real name</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="username">Username</label>  
                                                <div class="col-md-4">
                                                    <input id="username" name="username" data-bind='value:User.username' type="text" placeholder="username" class="form-control input-md" required="">
                                                    <span class="help-block">Username for site (lowercase)</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="password">Password</label>  
                                                <div class="col-md-4">
                                                    <input id="password" name="password" data-bind='value:$root.editUserPassword' type="text" placeholder="1re9a!3z3.y" class="form-control input-md" required="">
                                                    <span class="help-block">Replace the user's password - use a mix of letters, numbers, and punctuation</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="email">Email Address</label>  
                                                <div class="col-md-4">
                                                    <input id="email" name="email" data-bind='value:User.email' type="text" placeholder="user@domain.com" class="form-control input-md" required="">
                                                    <span class="help-block">Email address for user (will receive copy of assignments created by this user)</span>  
                                                </div>
                                            </div>

                                            <!-- ko if: Role.role_id == 1 -->
                                            <div class='form-group'>
                                                <label class="col-md-4 control-label" >User Role</label>
                                                <div class='col-md-4'>
                                                    <p>Superuser</p>
                                                </div>
                                            </div>
                                            <!-- /ko -->

                                            <!-- ko if: Role.role_id > 1 -->
                                            <!-- Select Basic -->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="role">User Role</label>
                                                <div class="col-md-4">
                                                    <select id="role" name="role" class="form-control" data-bind='options:$root.roles, optionsText: function(item){ return item.Role.role_name;}, optionsValue:function(item){ return item.Role.role_id;}, value:User.role_id'>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="checkbox">
                                                <label class='col-md-4'>
                                                    <input type="checkbox" name='disabled' data-bind='checked: User.disabled'> Disable User
                                                </label>
                                            </div>
                                            <!-- /ko -->

                                            <!-- Button (Double) -->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="submitEditUser"></label>
                                                <div class="col-md-8">
                                                    <button id="submitEditUser" name="submitEditUser" class="btn btn-primary" data-bind='click: $root.saveEditUser'>Save Changes</button>
                                                    <button id="cancelEditUser" name="cancelEditUser" class="btn btn-danger" data-bind='click: $root.cancelEditUser'>Cancel</button>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </form>
                                    <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.userError"></p>
                                    <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.userResult"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle='collapse' href='#addUserPanelBody'>Add User</a>
                                    </h3>
                                </div>
                                <div id='addUserPanelBody' class="panel-body collapse">
                                    <form id="addUserForm" class="form-horizontal">
                                        <fieldset>
                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="fullname">Full Name</label>  
                                                <div class="col-md-4">
                                                    <input id="addfullname" name="fullname" type="text" placeholder="First I. Last" class="form-control input-md" required="">
                                                    <span class="help-block">User's real name</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="username">Username</label>  
                                                <div class="col-md-4">
                                                    <input id="addusername" name="username" type="text" placeholder="username" class="form-control input-md" required="">
                                                    <span class="help-block">Username for site (lowercase)</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="password">Password</label>  
                                                <div class="col-md-4">
                                                    <input id="addpassword" name="password" type="text" placeholder="1re9a!3z3.y" class="form-control input-md" required="">
                                                    <span class="help-block">Use a mix of letters, numbers, and punctuation</span>  
                                                </div>
                                            </div>

                                            <!-- Text input-->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="email">Email Address</label>  
                                                <div class="col-md-4">
                                                    <input id="addemail" name="email" type="text" placeholder="user@domain.com" class="form-control input-md" required="">
                                                    <span class="help-block">Email address for user (will receive copy of assignments created by this user)</span>  
                                                </div>
                                            </div>

                                            <!-- Select Basic -->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="role">User Role</label>
                                                <div class="col-md-4">
                                                    <select id="addrole" name="role_id" class="form-control" data-bind='options:$root.roles, optionsText:function(item){ return item.Role.role_name;}, optionsValue:function(item){ return item.Role.role_id;}'>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Button (Double) -->
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="submitAddUser"></label>
                                                <div class="col-md-8">
                                                    <button id="submitAddUser" name="submitAddUser" class="btn btn-primary" data-bind='click:saveNewUser'>Add User</button>
                                                    <button id="cancelAddUser" name="cancelAddUser" class="btn btn-danger" data-bind='click:clearNewUser'>Clear</button>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </form>
                                    <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.adduserError"></p>
                                    <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.adduserResult"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="groups">
                <ul data-bind="foreach: groups">
                    <li data-bind="text: Group.group_name"></li>
                </ul>
            </div>
            <div class="tab-pane" id="sites">
                <div class="row">
                    <div class="col-md-12">
                        <p>Note: Site Ratio describes chance to be placed in <?=$settings['group_label'];?>.  At a site ratio of 0.1 there is a 90% chance that the subject will be assigned to the control group.</p>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Site Name</th>
                                    <th>Site Ratio</th>
                                    <th>Disabled?</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: sites">
                                <tr data-bind='click: $root.selectSite, clickBubble: false'>
                                    <td data-bind="text: Site.site_name"></td>
                                    <td data-bind="text: Site.site_ratio"></td>
                                    <td data-bind="text: Site.disabled"></td>
                                </tr>
                            </tbody>
                        </table>   
                    </div>
                </div>
                <div class="row" data-bind='with: selectedSite'>
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle='collapse' href='#editSitePanelBody'>Edit Site</a>
                                </h3>
                            </div>
                            <div id='editSitePanelBody' class="panel-body collapse in">
                                <form id="editSiteForm" class="form-horizontal">
                                    <fieldset>
                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="siteName">Site Name</label>  
                                            <div class="col-md-4">
                                                <input id="siteName" name="site_name" data-bind="value: Site.site_name" type="text" placeholder="Site Name" class="form-control input-md" required="">
                                                <span class="help-block">Name of new site</span>  
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="siteRatio">Site Ratio</label>  
                                            <div class="col-md-4">
                                                <input id="siteRatio" name="site_ratio" data-bind="value: Site.site_ratio" type="text" placeholder="0.5000" class="form-control input-md" required="">
                                                <span class="help-block">Site assignment ratio percentage as decimal (50% = 0.5000)</span>  
                                            </div>
                                        </div>

                                        <div class="checkbox">
                                            <label class='col-md-4'>
                                                <input type="checkbox" name='disabled' value='true' data-bind='checkedValue: function(item) { return item.Site.disabled === "1";}, checked: Site.disabled'> Disable Site
                                            </label>
                                        </div>

                                        <!-- Button (Double) -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submitEditSite"></label>
                                            <div class="col-md-8">
                                                <button id="submitEditSite" name="submitEditSite" data-bind='click: $root.saveEditSite' class="btn btn-primary">Save Changes</button>
                                                <button id="cancelEditSite" name="cancelEditSite" data-bind='click: $root.cancelEditSite' class="btn btn-danger">Cancel</button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                                <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.siteError"></p>
                                <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.siteResult"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle='collapse' href='#addSitePanelBody'>Add Site</a>
                                </h3>
                            </div>
                            <div id='addSitePanelBody' class="panel-body collapse">
                                <form id="addSiteForm" class="form-horizontal">
                                    <fieldset>
                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="siteName">Site Name</label>  
                                            <div class="col-md-4">
                                                <input id="siteName" name="site_name" type="text" placeholder="Site Name" class="form-control input-md" required="">
                                                <span class="help-block">Name of new site</span>  
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="siteRatio">Site Ratio</label>  
                                            <div class="col-md-4">
                                                <input id="siteRatio" name="site_ratio" type="text" placeholder="0.5000" class="form-control input-md" required="">
                                                <span class="help-block">Site assignment ratio percentage as decimal (50% = 0.5000)</span>  
                                            </div>
                                        </div>

                                        <!-- Button (Double) -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submitAddSite"></label>
                                            <div class="col-md-8">
                                                <button id="submitAddSite" name="submitAddSite" data-bind='click: $root.saveNewSite' class="btn btn-primary">Add Site</button>
                                                <button id="cancelAddSite" name="cancelAddSite" data-bind='click: $root.clearNewSite' class="btn btn-danger">Clear</button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                                <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.addsiteError"></p>
                                <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.addsiteResult"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="tab-pane" id="settings">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: settings">
                                <tr data-bind='click: $root.selectSetting, clickBubble: false'>
                                    <td data-bind="text: Setting.key"></td>
                                    <td data-bind="text: Setting.value"></td>
                                </tr>
                            </tbody>
                        </table>   
                    </div>
                </div>
                <div class="row" data-bind="with:selectedSetting">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle='collapse' href='#editSettingPanelBody'>Edit Setting</a>
                                </h3>
                            </div>
                            <div id='editSettingPanelBody' class="panel-body collapse in">
                                <form id="editSettingForm" class="form-horizontal">
                                    <fieldset>
                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="settingName">Setting Key</label>  
                                            <div class="col-md-4">
                                                <input id="settingName" name="key" data-bind='value:Setting.key' type="text" placeholder="MySetting" class="form-control input-md" required="">
                                                <span class="help-block">Name of new setting</span>  
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="settingValue">Setting Value</label>  
                                            <div class="col-md-4">
                                                <input id="settingRatio" name="value" data-bind='value:Setting.value' type="text" placeholder="value" class="form-control input-md" required="">
                                                <span class="help-block">Value for new setting</span>  
                                            </div>
                                        </div>

                                        <!-- Button (Double) -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submitEditSetting"></label>
                                            <div class="col-md-8">
                                                <button id="submitEditSetting" name="submitEditSetting" data-bind='click: $root.saveEditSetting' class="btn btn-primary">Save Changes</button>
                                                <button id="cancelEditSetting" name="cancelEditSetting" data-bind='click: $root.cancelEditSetting' class="btn btn-danger">Cancel</button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                                <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.settingError"></p>
                                <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.settingResult"></p>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle='collapse' href='#addSettingPanelBody'>Add Setting</a>
                                </h3>
                            </div>
                            <div id='addSettingPanelBody' class="panel-body collapse">
                                <form id="addSettingForm" class="form-horizontal">
                                    <fieldset>
                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="settingName">Setting Key</label>  
                                            <div class="col-md-4">
                                                <input id="settingName" name="key" type="text" placeholder="MySetting" class="form-control input-md" required="">
                                                <span class="help-block">Name of new setting</span>  
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="settingValue">Setting Value</label>  
                                            <div class="col-md-4">
                                                <input id="settingRatio" name="value" type="text" placeholder="value" class="form-control input-md" required="">
                                                <span class="help-block">Value for new setting</span>  
                                            </div>
                                        </div>

                                        <!-- Button (Double) -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submitAddSetting"></label>
                                            <div class="col-md-8">
                                                <button id="submitAddSetting" name="submitAddSetting" data-bind='click: $root.saveNewSetting' class="btn btn-primary">Add Setting</button>
                                                <button id="cancelAddSetting" name="cancelAddSetting" data-bind='click: $root.clearNewSetting' class="btn btn-danger">Clear</button>
                                            </div>
                                        </div>

                                    </fieldset>
                                </form>
                                <p class="col-md-4 col-offset-4 text-danger" data-bind="text: $root.addsettingError"></p>
                                <p class="col-md-4 col-offset-4 text-success" data-bind="text: $root.addsettingResult"></p>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
var baseURL = '<?= $baseURL?>';
</script>