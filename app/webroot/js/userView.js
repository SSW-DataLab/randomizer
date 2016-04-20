var uvModel = null; 
function UserViewModel() {
    var self = this;
    self.addError = ko.observable();
    self.externalID = ko.observable();
    self.confirmExternalID = ko.observable();
    self.certify = ko.observable();
    self.siteSelect = ko.observable();
    
    self.siteList = ko.observableArray();    
    
    self.addResult = ko.observable();
    
    self.submitClick = function() {
      if (!self.externalID() || !self.confirmExternalID() ||
              (self.externalID() != self.confirmExternalID()) ) {
          self.addError("You must enter and confirm the "+externalIDLabel+". Please ensure that both fields are filled out and are identical.");
          return;
      } else if (!self.certify()) {
          self.addError("You must certify that this "+externalIDLabel+" meets the inclusionary criteria for this study and is ready for random assignment.");
          return;
      } else if (!self.siteSelect()) {
          self.addError("You must select the site to which this "+externalIDLabel+" belongs.");
          return;
      } else {
          
      }      
      
      addExternalID(self.externalID(),self.siteSelect(), function(result) {
          if (result.message.type == 'error') {
            self.addError(result.message.text);    
          } else {
            $('#addYouthForm').hide();
            var addString = externalIDLabel+" "+result.message.subject.Subject.external_id+" has been assigned to the "+result.message.subject.Group.group_name+" .";
            self.addResult(addString);
            $('#addResult').show();
          }
      });
    };
}

function addExternalID(externalID, site, callback) {
    $.ajax({
        type: "POST",
        data: {'externalID': externalID, 'siteID': site.Site.site_id},
        url: baseURL+"/subjects/add.json",
        dataType: "json"
    }).done(function(data) {
        callback(data);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        uvModel.addResult("Failure");
    });    
}

function getSiteList() {
    $.ajax({
        type: "GET",
        url: baseURL+"/sites/available.json",
        dataType: "json"
    }).done(function(data) {
        uvModel.siteList(data.sites);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        requestFailed(jqXHR, textStatus, errorThrown);
    });
}

$(document).ready(function() {
    uvModel = new UserViewModel();
    getSiteList();
    ko.applyBindings(uvModel);
});
