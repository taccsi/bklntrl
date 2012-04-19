alert('lefut');
var xmlUrl = "http://www.pickpackpont.hu/stores/storelist.xml";
var selectedShopId = 0;

$(document).ready(function () {
    $('#content_stores').empty();

    Sys.Net.WebServiceProxy.invoke(xmlUrl, null, true, null, onComplete);

});


function onComplete(results) {
    LoadData(results[0].d);
}


function createXmlDOMObject(xml) {
    var xmlDoc = null;

    if (!window.DOMParser) {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async = false;
        xmlDoc.loadXML(xml);
    }
    else {
        parser = new DOMParser();
        xmlDoc = parser.parseFromString(xml, "text/xml");
    }

    return xmlDoc;
}


function LoadData(xml) {

    var xmlDoc = createXmlDOMObject(xml);

    $(xmlDoc).find('county').each(function (index, item) {
        var content_stores = "<div><span id='county" + index + "' class='extendercounty'>+</span></div><div class='extendercountylabel'>";
        var name = $(this).find('name').text();
        content_stores += name + "</div><div class='clear' ></div>";
        $(this).find('location').each(function (index2, item2) {
            var locationname = $(this).find('locationname').text();
            content_stores += "<div><span id='" + locationname.replace(' ', '').replace('.', '') + "' class='extenderlocation nodisplay " + name.replace(' ', '') + "' onclick='javascript: SetLocation(this)'>+</span></div><div class='extenderlocationlabel nodisplay " + name.replace(' ', '') + "' id='name" + locationname + "'>" + locationname + "</div><div class='clear'></div>"
            content_stores += "<div class='extenderstore nodisplay " + locationname.replace(' ', '').replace('.', '') + "'  id ='store" + locationname.replace(' ', '').replace('.', '') + "'>";
            $(this).find('store').each(function (index3, item3) {
                var title = $(this).find('title').text();
                var address = $(this).find('address').text();
                var type = $(this).find('type').text();
                var id = $(this).find('id').text();
                content_stores += "<div class='extenderstoreitem' id='" + id + "' onclick='javascript: SetSelection(this)'>"
                //with image
                content_stores += title + "<img src='img/" + type + ".jpg' width='100px' height='30px;' alt='" + type + "' style='float:right;' /><br /><br />";
                // no image
                //content_stores += title + "<br />";
                //show boltkód
                content_stores += address + " <br /> Boltkód: " + id + "</div><div class='clear' ></div>";
                //no boltkód inside
                //content_stores += address + " <br /> Boltkód: " + id + "</div><div class='clear' ></div>";
            });
            content_stores += "</div><div class='clear' ></div>"
        });
        $('#content_stores').append($(content_stores));
        $("#county" + index).bind('click', function (event) {
            var actval = ($(this).text());
            if (actval == "+") {
                $.each($('.' + name.replace(' ', '')), function (key, option) {
                    $(this).removeClass("nodisplay");
                });
                $(this).text("-");
            } else {
                $.each($('.' + name.replace(' ', '')), function (key, option) {
                    $(this).addClass("nodisplay");
                    HideItem($(this));
                });
                $(this).text("+");
            }
        });

    });
}

function SetSelection(item) {
    selectedShopId = $(item).attr("id");
};

function SetLocation(item) {
  var id = $(item).attr("id").replace(' ', '').replace('.', '');
  if (id.substr(0, 4) != "name") {
      var actval = $('#' + id.replace(' ', '')).text();
      if (actval == "+") {
          $.each($('.' + id), function (key, option) {
              $(this).removeClass("nodisplay");
          });
          $('#' + id).text("-");
      } else {
          $.each($('.' + id), function (key, option) {
              $(this).addClass("nodisplay");
          });
          $('#' + id).text("+");
      }
  }
};

function HideItem(item) {
    var id = $(item).attr("id").replace(' ', '').replace('.', '');
    if (id.substr(0, 4) != "name") {
        $.each($('.' + id), function (key, option) {
            $(this).addClass("nodisplay");
        });
        $('#' + id).text("+");
    }
}
