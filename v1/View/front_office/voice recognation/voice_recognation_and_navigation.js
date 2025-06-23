var site_link = "http://localhost/hireup/v1/";

var scriptSrc = document.currentScript.src;
var scriptDirectory = scriptSrc.substring(0, scriptSrc.lastIndexOf("/"));
console.log(scriptDirectory);

//the html page url
var pageUrl = window.location.href;
var pageDirectory = pageUrl.substr(0, pageUrl.lastIndexOf("/"));
if (pageDirectory.includes('profiles_management')) {
    is_in_profile = true;
}

console.log(scriptDirectory);
function goTo(place) {
    var url = '';
    place = place.toLowerCase()
    console.log(place)

    /*if (place.includes('home') || place.includes('index') || place.includes('main p')) {
        url = scriptDirectory + '/../../../index.php';
        window.location.href = url;
    }
    else if (place.includes('about')) {
        url = scriptDirectory + '/../../../about.php';
        window.location.href = url;
    } else if (place.includes('profile')) {
        url = scriptDirectory + '/../../../view/front_office/profiles_management/profile.php';
        window.location.href = url;
    } else if (place.includes('settings')) {
        url = scriptDirectory + '/../../../view/front_office/profiles_management/settings_privacy/edit_profile.php';
        window.location.href = url;
    } else if (place.includes('job')) {
        url = scriptDirectory + '/../../../view/front_office/jobs management/jobs_list.php';
        window.location.href = url;
    } else if (place.includes('report')) {
        url = scriptDirectory + '/../../../view/front_office/jobs reclamation/rec_list.php';
        window.location.href = url;
    } else if (place.includes('ads') || place.includes('ad') || place.includes('advertisement') || place.includes('add')) {
        if (is_in_profile) {
            url = scriptDirectory + '/../../view/front_office/ads/view_ads.php';
        } else {
            url = scriptDirectory + '/../../../view/front_office/ads/view_ads.php';
        }
        window.location.href = url;
    } else if (place.includes('messages')) {
        url = scriptDirectory + '/../../../view/front_office/messenger/messaging.php';
        window.location.href = url;
    } else if (place.includes('calendar') || place.includes('event') || place.includes('meet') || place.includes('meeting')) {
        url = scriptDirectory + '/../../../view/front_office/calendar/calendar.php';
        window.location.href = url;
    }*/

    if (place.includes('home') || place.includes('index') || place.includes('main')) {
        url = site_link + 'index.php';
        window.location.href = url;
    }
    else if (place.includes('about')) {
        url = site_link + 'about.php';
        window.location.href = url;
    } else if (place.includes('profile')) {
        url = site_link + 'view/front_office/profiles_management/profile.php';
        window.location.href = url;
    } else if (place.includes('settings')) {
        url = site_link + 'view/front_office/profiles_management/settings_privacy/edit-profile.php';
        window.location.href = url;
    } else if (place.includes('job')) {
        url = site_link + 'view/front_office/jobs management/jobs_list.php';
        window.location.href = url;
    } else if (place.includes('report') || place.includes('reclamation')) {
        url = site_link + 'view/front_office/reclamation/rec_list.php';
        window.location.href = url;
    } else if (place.includes('ads') || place.includes('ad') || place.includes('advertisement') || place.includes('add')) {
        url = site_link + 'view/front_office/ads/view_ads.php';
        window.location.href = url;
    } else if (place.includes('messages')) {
        url = site_link + 'view/front_office/messenger/messaging.php';
        window.location.href = url;
    } else if (place.includes('calendar') || place.includes('event') || place.includes('meet') || place.includes('meeting')) {
        url = site_link + 'view/front_office/calendar/calendar.php';
        window.location.href = url;
    } else if (place.includes('dashboard') || place.includes('admin')) {
        url = site_link + 'view/back_office/main dashboard/index.php';
        window.location.href = url;
    }

}


document.addEventListener('DOMContentLoaded', function() {
  
    // Get the chatbot toggler button
    const chatbotToggler = document.querySelector('.chatbot-toggler');

    // Initialize voiceEnabled as true (initially enabled)
    let voiceEnabled = true;

    if (chatbotToggler) {

        // Toggle button click event
        chatbotToggler.addEventListener('click', function () {
            // Toggle the value of voiceEnabled
            voiceEnabled = !voiceEnabled;

            // If voiceEnabled is true, start voice commands; otherwise, abort them
            if (voiceEnabled) {
                annyang.start();
            } else {
                annyang.abort();
            }
        });
    }

    if (annyang) {
    var commands = {
      'show me :query': function(query) {
        goTo(query);
      },
        'go to :query': function(query) {
            goTo(query);
        },
    };

    annyang.addCommands(commands);

    annyang.start();
  }
});
