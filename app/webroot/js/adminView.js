var uvModel = null;
function AdminViewModel() {
    var self = this;
    self.contextCheck = "success";
    
    self.users = ko.observableArray();
    self.sites = ko.observableArray();
    self.roles = ko.observableArray();
    self.groups = ko.observableArray();
    self.subjects = ko.observableArray();
    self.settings = ko.observableArray();

    self.subjectPaging = ko.observable();
    self.userPaging = ko.observable();

    self.siteError = ko.observable();
    self.siteResult = ko.observable();
    self.selectedSite = ko.observable();

    self.selectSite = function(site) {
        self.selectedSite(site);
    };

    self.saveEditSite = function(site) {
        saveSite(site, function(result) {
            if (result.message.type == 'error') {
                self.siteError(result.message.text);
                self.siteResult(null);
            } else {
                self.siteResult(result.message.text);
                self.siteError(null);
                self.selectedSite(null);
                getSiteList();
            }
        });
    };

    self.cancelEditSite = function() {
        self.selectedSite(null);
        self.editSitePassword(null);
    };

    self.siteAddError = ko.observable();
    self.siteAddResult = ko.observable();

    self.clearNewSite = function() {
        $('#addSiteForm').trigger('reset');
    };

    self.saveNewSite = function() {
        var site = $('#addSiteForm').serializeObject();
        $('#addSiteForm').trigger('reset');
        addSite(site, function(result) {
            if (result.message.type == 'error') {
                self.siteAddError(result.message.text);
                self.siteAddResult(null);
            } else {
                self.siteAddResult(result.message.text);
                self.siteAddError(null);
                getSiteList();
            }
        });
    };

    self.settingError = ko.observable();
    self.settingResult = ko.observable();
    self.selectedSetting = ko.observable();

    self.selectSetting = function(setting) {
        self.selectedSetting(setting);
    };

    self.saveEditSetting = function(setting) {
        saveSetting(setting, function(result) {
            if (result.message.type == 'error') {
                self.settingError(result.message.text);
                self.settingResult(null);
            } else {
                self.settingResult(result.message.text);
                self.settingError(null);
                self.selectedSetting(null);
                getSettingsList();
            }
        });
    };

    self.cancelEditSetting = function() {
        self.selectedSetting(null);
        self.editSettingPassword(null);
    };

    self.settingAddError = ko.observable();
    self.settingAddResult = ko.observable();

    self.clearNewSetting = function() {
        $('#addSettingForm').trigger('reset');
    };

    self.saveNewSetting = function() {
        var setting = $('#addSettingForm').serializeObject();
        $('#addSettingForm').trigger('reset');
        saveSetting(setting, function(result) {
            if (result.message.type == 'error') {
                self.settingAddError(result.message.text);
                self.settingAddResult(null);
            } else {
                self.settingAddResult(result.message.text);
                self.settingAddError(null);
                getSettingsList();
            }
        });
    };

    self.userError = ko.observable();
    self.userResult = ko.observable();
    self.selectedUser = ko.observable();
    self.editUserPassword = ko.observable();

    self.selectUser = function(user) {
        self.userResult(null);
        self.userError(null);
        self.selectedUser(user);
    };

    self.saveEditUser = function(user) {
        user.User.password = self.editUserPassword();
        self.editUserPassword(null);
        saveUser(user, function(result) {
            if (result.message.type == 'error') {
                self.userError(result.message.text);
                self.userResult(null);
            } else {
                self.userResult(result.message.text);
                self.userError(null);
                self.selectedUser(null);
                getUserList(self.userPaging().page);
            }
        });
    };

    self.cancelEditUser = function() {
        self.selectedUser(null);
        self.editUserPassword(null);
    };

    self.userAddError = ko.observable();
    self.userAddResult = ko.observable();

    self.clearNewUser = function() {
        $('#addUserForm').trigger('reset');
    };

    self.saveNewUser = function() {
        var user = $('#addUserForm').serializeObject();
        $('#addUserForm').trigger('reset');
        addUser(user, function(result) {
            if (result.message.type == 'error') {
                self.userAddError(result.message.text);
                self.userAddResult(null);
            } else {
                self.userAddResult(result.message.text);
                self.userAddError(null);
                getUserList(self.userPaging().page);
            }
        });
    };
    
    self.subjectError = ko.observable();
    self.subjectResult = ko.observable();
    self.selectedSubject = ko.observable();

    self.selectSubject = function(subject) {
        self.subjectError(null);
        self.subjectResult(null);
        self.selectedSubject(subject);
    };
    
    self.saveEditSubject = function(subject) {
        saveSubject(subject, function(result) {
            if (result.message.type == 'error') {
                self.subjectError(result.message.text);
                self.subjectResult(null);
            } else {
                self.subjectResult(result.message.text);
                self.subjectError(null);
                self.selectedSubject(null);
                getSubjectList(self.subjectPaging().page);
            }
        });
    };
    
    self.cancelEditSubject = function() {
        self.selectedSubject(null);
    };

    self.nextSubjectsClick = function() {
        if (self.subjectPaging().nextPage) {
            getSubjectList(self.subjectPaging().page + 1);
        }
    };

    self.lastSubjectsClick = function() {
        if (self.subjectPaging().prevPage) {
            getSubjectList(self.subjectPaging().page - 1);
        }
    };

    self.nextUsersClick = function() {
        if (self.userPaging().nextPage) {
            getUserList(self.userPaging().page + 1);
        }
    };

    self.lastUsersClick = function() {
        if (self.userPaging().prevPage) {
            getUserList(self.userPaging().page - 1);
        }
    };
}

function addSite(site, callback) {
    $.ajax({
        type: "POST",
        data: {
            'site_name': site.site_name,
            'site_ratio': site.site_ratio,
        },
        url: baseURL+"/sites/add.json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.siteError("Failure");
    });
}

function saveSubject(subject, callback) {
    $.ajax({
        type: "POST",
        data: {
            'subject_id': subject.Subject.subject_id,
            'record_invalid': subject.Subject.record_invalid === '1' ? null : subject.Subject.record_invalid
        },
        url: baseURL+"/subjects/edit/" + subject.Subject.subject_id+ ".json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.subjectError("Failure");
    });
}

function saveSite(site, callback) {
    $.ajax({
        type: "POST",
        data: {
            'site_id': site.Site.site_id,
            'site_name': site.Site.site_name,
            'site_ratio': site.Site.site_ratio,
            'disabled': site.Site.disabled === '1' ? null : site.Site.disabled
        },
        url: baseURL+"/sites/edit/" + site.Site.site_id + ".json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.siteError("Failure");
    });
}

function saveSetting(setting, callback) {
    $.ajax({
        type: "POST",
        data: {
            'key': (setting.key) ? setting.key : setting.Setting.key,
            'value': (setting.value) ? setting.value : setting.Setting.value
        },
        url: baseURL+"/settings/save.json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.settingError("Failure");
    });
}

function addUser(user, callback) {
    $.ajax({
        type: "POST",
        data: {
            'username': user.username,
            'fullname': user.fullname,
            'email': user.email,
            'role_id': user.role_id,
            'password': user.password
        },
        url: baseURL+"/users/add.json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.userError("Failure");
    });
}

function saveUser(user, callback) {
    $.ajax({
        type: "POST",
        data: {
            'user_id': user.User.user_id,
            'username': user.User.username,
            'fullname': user.User.fullname,
            'email': user.User.email,
            'role_id': user.User.role_id,
            'password': user.User.password,
            'disabled': user.User.disabled == false ? null : user.User.disabled
        },
        url: baseURL+"/users/edit/" + user.User.user_id + ".json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        avModel.userError("Failure");
    });
}

function getRoleList() {
    $.ajax({
        type: "GET",
        url: baseURL+"/roles.json",
        dataType: "json"
    }).done(function(data) {
        avModel.roles(data.roles);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

function getSiteList() {
    $.ajax({
        type: "GET",
        url: baseURL+"/sites.json",
        dataType: "json"
    }).done(function(data) {
        avModel.sites(data.sites);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

function getSubjectList(page) {
    if (!page) {
        page = 1;
    }
    $.ajax({
        type: "GET",
        url: baseURL+"/subjects/page/page:" + page + ".json",
        dataType: "json"
    }).done(function(data) {
        avModel.subjects(data.subjects);
        avModel.subjectPaging(data.paging.Subject);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

function getGroupList() {
    $.ajax({
        type: "GET",
        url: baseURL+"/groups.json",
        dataType: "json"
    }).done(function(data) {
        avModel.groups(data.groups);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

function getUserList(page) {
    if (!page) {
        page = 1;
    }
    $.ajax({
        type: "GET",
        url: baseURL+"/users/page/page:" + page + ".json",
        dataType: "json"
    }).done(function(data) {
        avModel.users(data.users);
        avModel.userPaging(data.paging.User);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

function getSettingsList() {
    $.ajax({
        type: "GET",
        url: baseURL+"/settings.json",
        dataType: "json"
    }).done(function(data) {
        avModel.settings(data.settings);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

$(document).ready(function() {
    avModel = new AdminViewModel();
    getRoleList(0);
    getSiteList();
    getSubjectList();
    getGroupList();
    getUserList();
    getSettingsList();
    ko.applyBindings(avModel);
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
