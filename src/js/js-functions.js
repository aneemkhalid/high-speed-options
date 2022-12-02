let $ = jQuery;

// Functions
const isMobile = (function (a) {
   return /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
       a
     ) ||
     /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw(n|u)|c55\/|capi|ccwa|cdm-|cell|chtm|cldc|cmd-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc-s|devi|dica|dmob|do(c|p)o|ds(12|d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(-|_)|g1 u|g560|gene|gf-5|g-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd-(m|p|t)|hei-|hi(pt|ta)|hp( i|ip)|hs-c|ht(c(-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i-(20|go|ma)|i230|iac( |-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|-[a-w])|libw|lynx|m1-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|-([1-8]|c))|phil|pire|pl(ay|uc)|pn-2|po(ck|rt|se)|prox|psio|pt-g|qa-a|qc(07|12|21|32|60|-[2-7]|i-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h-|oo|p-)|sdk\/|se(c(-|0|1)|47|mc|nd|ri)|sgh|shar|sie(|m)|sk0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h|v|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl|tdg|tel(i|m)|tim|tmo|to(pl|sh)|ts(70|m|m3|m5)|tx9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas|your|zeto|zte/i.test(
       a.substr(0, 4)
     );
})(navigator.userAgent || navigator.vendor || window.opera);

const isValidZip = zipStr => !isNaN(zipStr) && zipStr.length === 5;

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            if(sParameterName[0] == 'gclid') {
                sessionStorage.setItem('gclid', decodeURIComponent(sParameterName[1]));
            }
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    if(sParam == 'gclid' && sessionStorage.getItem('gclid')) {
        return sessionStorage.getItem('gclid');
    }
    return false;
};

function addGclidToInternalLinks(){
    var $gcidPresent = getUrlParameter('gclid');
    if ($gcidPresent && $gcidPresent != null){
        var domainsToDecorate = [
            'highspeedoptions.com', //add or remove domains (without https or trailing slash) 
            'localhost:8888',
            'wpengine.com',
            'hso.test'
        ],
        queryParams = [
            'gclid', //add or remove query parameters you want to transfer
        ]
        // do not edit anything below this line
        var links = document.querySelectorAll('a'); 

        // check if links contain domain from the domainsToDecorate array and then decorates
        for (var linkIndex = 0; linkIndex < links.length; linkIndex++) {
            for (var domainIndex = 0; domainIndex < domainsToDecorate.length; domainIndex++) { 
                if (links[linkIndex].href.indexOf(domainsToDecorate[domainIndex]) > -1 && links[linkIndex].href.indexOf("#") === -1 && links[linkIndex].href.indexOf("gclid") === -1) {
                    links[linkIndex].href = decorateUrl(links[linkIndex].href);
                }
            }
        }
    }
    // decorates the URL with query params
    function decorateUrl(urlToDecorate) {
        urlToDecorate = (urlToDecorate.indexOf('?') === -1) ? urlToDecorate + '?' : urlToDecorate + '&';
        var collectedQueryParams = [];
        for (var queryIndex = 0; queryIndex < queryParams.length; queryIndex++) {
            if (getQueryParam(queryParams[queryIndex])) {
                collectedQueryParams.push(queryParams[queryIndex] + '=' + getQueryParam(queryParams[queryIndex]))
            }
            else if(sessionStorage.getItem('gclid')) {
                collectedQueryParams.push('gclid=' + sessionStorage.getItem('gclid'));
            }
        }
        return urlToDecorate + collectedQueryParams.join('&');
    }
    //retrieves the value of a query parameter
    function getQueryParam(name) {
        if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(window.location.search))
            return decodeURIComponent(name[1]);
    }
}

//adds gclid to search forms
function addGclidToSearch(){
    var gclid = getUrlParameter('gclid');
    if (gclid && $('.zip_search_form .zip_search_input').length){
        $('.zip_search_form .zip_search_input').after('<input type="hidden" name="gclid" value="'+gclid+'">');
    }
}
/**
 * Retrieves gclid param from url and injects it into all
 * href values that contain the {$gclid} substring variable
 */
function populateAffClickID() {
    const urlParams = new URLSearchParams(window.location.search);
    const gclid = getUrlParameter('gclid');

    if(gclid) {
        // select all a tags where the value of href
        // contains the substring "gclid"
        $('a[href*="gclid"]').each((i, el) => {
            // some of the urls have encoded special characters
            const trackingUrl = decodeURI($(el).attr('href'));
            const trackingUrlParams = new URLSearchParams(trackingUrl);
            const affClickId = trackingUrlParams.get('aff_click_id');

            if(affClickId === '{$gclid}') {
                const updatedLink = trackingUrl.replace('{$gclid}', gclid);
                $(el).attr('href', updatedLink);
            }
            if(affClickId === '$gclid') {
                const updatedLink = trackingUrl.replace('$gclid', gclid);
                $(el).attr('href', updatedLink);
            }
        });
    }
    
    const ifLoadedGA =   setInterval(function(){ handleGAParam(ifLoadedGA) }, 500);
}

//function to replace shortcode output with highest download speed number
function displayHighestDownloadSpeed(){
    var highest_download_speed = $('.highest-download-speed').text();
    if (highest_download_speed == ''){
        highest_download_speed = 'N/A';
    }
    $(".highest_download_speed_shortcode").replaceWith(highest_download_speed);

}

//function to replace shortcode output with highest download speed provider name
function displayHighestDownloadSpeedProvider(){
    var highest_download_speed_provider = $('.highest-download-speed-provider').text();
    if (highest_download_speed_provider == ''){
        highest_download_speed_provider = 'N/A';
    }
    $(".highest_download_speed_provider_shortcode").replaceWith(highest_download_speed_provider);

}


//add the client id to the href links

var numOfTries = 0;

const handleGAParam = (ifLoadedGA) => {
    
    numOfTries++;    
    
     //turn off if 8 seconds has passed
    if(numOfTries === 16 ) {  
        clearInterval(ifLoadedGA);
    }
 
    if(window.ga && ga.loaded) {    

        //a check for FF private tab that blocks GA
        if (ga.getAll()[0] !== undefined ) {
            
            const gaparam = ga.getAll()[0].get('clientId');
            clearInterval(ifLoadedGA);

                $('a[href*="clientid"]').each((i, el) => {

                    const trackingUrl = decodeURI($(el).attr('href'));
                    const trackingUrlParams = new URLSearchParams(trackingUrl);
                    const affClickId = trackingUrlParams.get('aff_sub4');

                    if(affClickId === '{$clientid}') {               
                        const updatedLink = trackingUrl.replace('{$clientid}', gaparam);        
                        $(el).attr('href', updatedLink);
                    }
                     if(affClickId === '$clientid') {               
                        const updatedLink = trackingUrl.replace('$clientid', gaparam);        
                        $(el).attr('href', updatedLink);
                    }

                });
        }    
    }
   
}


    //dataLayer info - for when the user clicks the type tab it will send that datalayer
    $("#internet-search-tab" ).on( "click", function(e) {
       if (!$(this).hasClass('dataLayer-sent')) {
           zipSearchDataInternetWrapper();
           $(this).addClass('dataLayer-sent');
       }
    });  
    
    $("#tv-search-tab" ).on( "click", function(e) {
       if (!$(this).hasClass('dataLayer-sent')) {
           zipSearchDataTVWrapper();
           $(this).addClass('dataLayer-sent');
       }
    });  
    
     $("#bundle-search-tab" ).on( "click", function(e) {
       if (!$(this).hasClass('dataLayer-sent')) {
           zipSearchDataBundleWrapper();
           $(this).addClass('dataLayer-sent');
       }
    }); 

    //to pull the telephone numbers
    function getThisPhoneNumber(obj) {
        return obj.getAttribute("href").replace("tel:","");
    }
    
    //to send the dataLayer info by JS since it's best to get the info that way for this
    $(document).on('submit','form.zip_search_form',function(e){
        //e.preventDefault();
        if(typeRadio = $(this).find('input[name=type]:checked').val()) {
            var dataLayerTypeInput = typeRadio;
        }
        else {
            var dataLayerTypeInput = $(this).find('input[name=type]').val();
        }
        //var dataLayerTypeInput = $(this).find('input[name=type]:checked').val();
        //console.log(dataLayerTypeInput);
        var dataLayerZipInput = $(this).find('input[name=zip]').val().toString();
        var dataLayerFormType = $(this).data("form");

        if (dataLayerTypeInput === undefined) {
            dataLayerTypeInput = 'internet';           
        }
       
        
        dataLayer.push({
            'event' : 'zipSearch', 
            'zipCode' : dataLayerZipInput,
            'searchLocation' : dataLayerFormType, 
            'serviceType' :  dataLayerTypeInput
        });
      
       
        
    });