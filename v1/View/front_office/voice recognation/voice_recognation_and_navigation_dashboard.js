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


function goTo(place) {
    var url = '';
    place = place.toLowerCase()
    console.log(place)

    if (place.includes('home') || place.includes('index') || place.includes('main')) {
        url = site_link + 'view/back_office/main dashboard/index.php';
        window.location.href = url;
    } else if (place.includes('user')) {
        url = site_link + 'view/back_office/users managment/users_management.php';
        window.location.href = url;
    } else if (place.includes('profile')) {
        url = site_link + 'view/back_office/profiles_management/profile_management.php';
        window.location.href = url;
    } else if (place.includes('job')) {
        url = site_link + 'view/back_office/jobs_management/job_management.php';
        window.location.href = url;
    } else if (place.includes('report') || place.includes('reclamation')) {
        url = site_link + 'view/back_office/reclamations managment/recs_management.php';
        window.location.href = url;
    } else if (place.includes('ads') || place.includes('ad') || place.includes('advertisement') || place.includes('add')) {
        url = site_link + 'view/back_office/dmd and pub management/pub_management.php';
        window.location.href = url;
    } else if (place.includes('request')) {
        url = site_link + 'view/back_office/dmd and pub management/dmd_management.php';
        window.location.href = url;
    } else if (place.includes('article') || place.includes('post')) {
        url = site_link + 'view/back_office/articals management/articles_management.php';
        window.location.href = url;
    } else if (place.includes('response') || place.includes('answer')) {
        url = site_link + 'view/back_office/reponse management/reps_management.php';
        window.location.href = url;
    } else if (place.includes('dashboard') || place.includes('admin')) {
        url = site_link + 'view/back_office/main dashboard/index.php';
        window.location.href = url;
    }

}

function goBackTo(place) {
    var url = '';
    place = place.toLowerCase()
    console.log(place)

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


document.addEventListener('DOMContentLoaded', function () {

    if (annyang) {
        var commands = {
            'show me :query': function (query) {
                goTo(query);
            },
            'go to :query': function (query) {
                goTo(query);
            },
            'go back to :query': function (query) {
                goBackTo(query);
            },
        };

        annyang.addCommands(commands);

        annyang.start();
    }
});
