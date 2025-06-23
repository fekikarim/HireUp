<?php

require_once __DIR__ . '/../../../Controller/profileController.php';


if (session_status() == PHP_SESSION_NONE) {
  session_set_cookie_params(0, '/', '', true, true);
  session_start();
}

$profileController1 = new ProfileC();

$user_id1 = '';
$user_profile_id1 = '';

//get user_profile id
if (isset($_SESSION['user id'])) {
  $user_id1 = htmlspecialchars($_SESSION['user id']);
  $user_profile_id1 = $profileController->getProfileIdByUserId($user_id1);
  $profile1 = $profileController->getProfileById($user_profile_id1);
}

//fetch subscription
$subs_type = array(
  "1-ADVANCED-SUBS" => "advanced",
  "1-BASIC-SUBS" => "basic",
  "1-PREMIUM-SUBS" => "premium",
  "else" => "limited"
);


$current_profile_sub = "";
if (array_key_exists($profile1['profile_subscription'], $subs_type)) {
  // If it exists, return the corresponding value
  $current_profile_sub = $subs_type[$profile1['profile_subscription']];
} else {
  // If not, return 'bb'
  $current_profile_sub = $subs_type['else'];
}



?>

<style>
  .pdf-button {
    border: none;
    display: flex;
    padding: 0.75rem 1.5rem;
    background-color: #488aec;
    color: #ffffff;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
    vertical-align: middle;
    align-items: center;
    border-radius: 0.5rem;
    user-select: none;
    gap: 0.75rem;
    box-shadow: 0 4px 6px -1px #488aec31, 0 2px 4px -1px #488aec17;
    transition: all 0.3s ease;
    /* Adjusted transition duration for a snappier feel */
    cursor: pointer;
  }

  .results-button {
    border: none;
    display: flex;
    padding: 0.75rem 1.5rem;
    background-color: #35a120;
    color: #ffffff;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
    vertical-align: middle;
    align-items: center;
    border-radius: 0.5rem;
    user-select: none;
    gap: 0.75rem;
    box-shadow: 0 4px 6px -1px #48ec6331, 0 2px 4px -1px #488aec17;
    transition: all 0.3s ease;
    /* Adjusted transition duration for a snappier feel */
    cursor: pointer;
    padding: auto;
  }

  .pdf-button:hover {
    background-color: #3578d1;
    /* Darker shade for hover effect */
    box-shadow: 0 10px 15px -3px #488aec4f, 0 4px 6px -2px #488aec17;
    transform: scale(1.05);
    /* Slightly increase size */
  }

  .results-button:hover {
    background-color: #1f9933;
    /* Darker shade for hover effect */
    box-shadow: 0 10px 15px -3px #8aec484f, 0 4px 6px -2px #488aec17;
    transform: scale(1.05);
    /* Slightly increase size */
  }

  .pdf-button:active {
    background-color: #2c6bb3;
    /* Even darker shade for active effect */
    transform: scale(0.95);
    /* Slightly decrease size to simulate pressing */
    opacity: 0.85;
    /* Maintain opacity change */
    box-shadow: none;
    /* Maintain no shadow */
  }

  .results-button:active {
    background-color: #5eb32c;
    /* Even darker shade for active effect */
    transform: scale(0.95);
    /* Slightly decrease size to simulate pressing */
    opacity: 0.85;
    /* Maintain opacity change */
    box-shadow: none;
    /* Maintain no shadow */
  }

  .pdf-button:focus,
  .results-button:focus {
    outline: none;
    /* Remove default outline */
    opacity: 0.85;
    /* Maintain opacity change */
    box-shadow: none;
    /* Maintain no shadow */
  }

  .pdf-button svg,
  .results-button svg {
    width: 1.25rem;
    height: 1.25rem;
  }

  .button-container {
    display: flex;
  }

  .button-container>div {
    margin-right: 10px;
    /* Adjust spacing between buttons */
  }

  .hidden-btn {
    display: none;
  }

  .shown-btn {
    display: block;
  }

  svg {
    width: 1.25rem;
    height: 1.25rem;
  }
</style>

<style>
  progress {
    display: inline-block;
    position: relative;
    background: none;
    border: 0;
    border-radius: 5px;
    width: 100%;
    text-align: left;
    position: relative;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 0.8em;
  }

  progress::-webkit-progress-bar {
    margin: 0 auto;
    background-color: #CCC;
    border-radius: 5px;

  }

  progress::-webkit-progress-value {
    display: relative;
    margin: 0px -10px 0 0;
    background: #55bce7;
    border-radius: 5px;
  }

  progress:after {
    margin: -36px 0 0 7px;
    padding: 0;
    display: inline-block;
    float: right;
    content: attr(value) '%';
    position: relative;
  }

  .progress-bar-container {
    margin-top: 10px;
    padding: 2% 10%;
  }

  .progress-bar {
    width: 100%;
    background-color: #f3f3f3;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
  }

  .progress-bar-fill {
    height: 20px;
    background-color: #55bce7;
    width: 0;
    text-align: center;
    color: white;
    line-height: 20px;
  }
</style>

<!-- Popup -->
<style>
  .popup-card {
    display: none;
    position: fixed;
    z-index: 99999999999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(245, 245, 245, 0.4);
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
    max-width: 100%;
    max-height: 100%;
    min-height: auto;
    min-width: auto;
    padding: 20px;
    border-radius: 5px;
  }

  .popup-content {
    background-color: #fefefe;
    margin: 5% auto;
    border: 1px solid #888;
    width: 80%;
    height: 82%;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  .popup-content iframe {
    width: 100%;
    height: 82%;
    /* Set the height to adjust based on content */
  }
</style>

<!-- dropdown menu -->
<style>
  .dropdown-container {
    position: relative;
    display: inline-block;
    width: 250px;
  }

  .dropdown-toggle {
    width: 100%;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: left;
    font-size: 16px;
  }

  .dropdown_menu {
    display: none;
    position: absolute;
    width: 100%;
    background-color: #ffffff;
    border: 1px solid #ddd;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    z-index: 1;
    margin-top: 5px;
    overflow: hidden;
  }

  .dropdown_menu .dropdown-item {
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
    transition: background-color 0.3s;
  }

  .dropdown_menu .dropdown-item:last-child {
    border-bottom: none;
  }

  .dropdown_menu .dropdown-item:hover {
    background-color: #f1f1f1;
  }

  .dropdown_menu .dropdown-item span {
    flex-grow: 1;
  }

  .dropdown_menu .dropdown-item button {
    margin-left: 5px;
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: #007bff;
    font-size: 16px;
  }

  .dropdown_menu .dropdown-item button:hover {
    color: #0056b3;
  }
</style>


<?php
$current_website_link = "http://$_SERVER[HTTP_HOST]/hireup/v1";
?>

<a href="javascript:void()" class="chatbot-toggler" style="z-index: 9999999999;">
  <span style="font-size:x-large; color: #fff;
                position: absolute;"><i class="far fa-comment"></i></span>
  <span style="font-size:x-large; color: #fff;
                position: absolute;"><i class="fa fa-close"></i></span>
</a>

<div class="chatbot" style="z-index: 9999999999;">
  <div class="card-header" style="padding: 16px 0;
                position: relative;
                text-align: center;
                color: #fff;
                background: #40A2D8;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                ">
    <h2 class="mt-3 bold" style="font-size: 1.5rem;">HireUp Bot</h2>
    <span class="close-btn material-symbols-outlined" style="position: absolute;
                right: 15px;
                top: 50%;
                display: none;
                cursor: pointer;
                transform: translateY(-50%);">close</span>
  </div>
  <ul class="chatbox" style="overflow-y: auto;
                height: 510px;
                padding: 30px 20px 100px;
                z-index: 1;">
    <li class="chat incoming" style="display: flex;
                list-style: none;">
      <span class="material-symbols-outlined" style="width: 32px;
                height: 32px;
                color: #fff;
                cursor: default;
                text-align: center;
                line-height: 32px;
                align-self: flex-end;
                background: #40A2D8;
                border-radius: 4px;
                margin: 0 10px 7px 0;"><i class="fa fa-robot"></i></span>
      <p style="border-radius: 10px 10px 10px 0;">Hello! I'm HireUp Bot, your career companion. Let's navigate
        employment and recruitment together.</p>
    </li>
  </ul>
  <div class="chat-input" style="display: flex;
                gap: 5px;
                position: absolute;
                bottom: 0;
                width: 100%;
                background: #fff;
                padding: 12px 15px;
                border-top: 1px solid #ddd;">

    <p style="align-self: flex;
              color: #40A2D8;
              cursor: pointer;
              height: 55px;
              display: flex;
              align-items: center;
              font-size: 1.35rem;" id="plus-btn"><i class="fas fa-plus-circle"></i>
    </p>

    <textarea style="height: 55px !important;
                width: 100% !important;
                /*border: none !important;*/
                border: 1px solid rgba(0, 0, 0, 0.3) !important;
                border-radius: 5px; 
                resize: none !important;
                max-height: 100px !important;
                padding: 15px 15px 15px 15px !important;
                font-size: 0.95rem !important;
                text-transform: none;
                    resize: vertical;" id="textarea-bot" placeholder="Enter a message..." spellcheck="true"
      required></textarea>

    <span style="align-self: flex;
                color: #40A2D8;
                cursor: pointer;
                height: 55px;
                display: flex;
                align-items: center;
                font-size: 1.35rem;" class="material-symbols-rounded mb-4"><i class="far fa-paper-plane"></i>
    </span>

    <?php if ($current_profile_sub == "advanced" || $current_profile_sub == "premium") { ?>
      <p style="align-self: flex;
              color: #40A2D8;
              cursor: pointer;
              height: 55px;
              display: flex;
              align-items: center;
              font-size: 1.35rem;" id="mic-btn" onclick="imgIconClicked()"><i class="fas fa-image"
          id="img-bot-add-btn"></i>
      </p>
      <input type="file" id="hiddenFileInputImgBot" name="hiddenFileInputImgBot" style="display: none;" accept="image/*">
      <div class="dropdown_menu" id="dropdownMenuBot">
        <!-- Items will be injected here via JavaScript -->
        <!-- <img id="imgPreview" src="" alt="Image Preview" style="display: none; width: 200px; height: auto;"> -->
      </div>
    <?php } ?>


    <!-- voice to text mic btn -->
    <?php if ($current_profile_sub == "advanced" || $current_profile_sub == "premium") { ?>
      <p style="align-self: flex;
              color: #40A2D8;
              cursor: pointer;
              height: 55px;
              display: flex;
              align-items: center;
              font-size: 1.35rem;" id="mic-btn" onclick="startSpeechRecognition('textarea-bot')"><i
          class="fas fa-microphone"></i>
      </p>
    <?php } ?>

  </div>
</div>

<script src="<?= __DIR__ ?>/../../../front office assets/js/chatbot.js"></script>

<script>
  function startSpeechRecognition(inputId) {
    if ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window) {
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      const recognition = new SpeechRecognition();
      recognition.interimResults = true;

      recognition.addEventListener('result', e => {
        const transcript = Array.from(e.results)
          .map(result => result[0])
          .map(result => result.transcript)
          .join('');

        document.getElementById(inputId).value = transcript;
      });

      recognition.addEventListener('end', () => {
        handleChat();
      });

      recognition.start();
    } else {
      alert("Speech recognition not supported in this browser.");
    }
  }

</script>

<div id="questionModal" class="modal"
  style="display:none; position: fixed; z-index: 10000000000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4);">
  <div class="modal-content"
    style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 40%;">
    <span class="close" onclick="closeChatModalHere()"
      style="color: #aaa; float: left; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>

    <?php if ($current_profile_sub == "premium") { ?>
      <h3>Resume Analyser</h3>

      <form id="resumeForm"
        action="<?= $current_website_link ?>/View/front_office/jobs management/chatbot_analyse_resume.php" method="post"
        enctype="multipart/form-data">
        <div class="button-container">

          <div id="add-resume-file-div" class="shown-btn">
            <button type="button" id="fileButton" class="pdf-button"
              onclick="document.getElementById('hiddenFileInput').click();">
              <!-- <button type="button" id="fileButton" class="btn btn-primary" onclick="document.getElementById('hiddenFileInput').click();"> -->
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125"
                  stroke="#fffffff" stroke-width="2"></path>
                <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"></path>
              </svg>
              Add Resume
            </button>
            <input type="file" id="hiddenFileInput" name="resumeFile" style="display: none;" accept="application/pdf">
          </div>

          <div id="add-resume-file-agian-div" class="hidden-btn">
            <button type="button" id="fileButton1" class="pdf-button"
              onclick="document.getElementById('hiddenFileInput1').click();">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125"
                  stroke="#fffffff" stroke-width="2"></path>
                <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round"></path>
              </svg>
              Again <span class="refresh" style="display: inline-block; transform: rotate(90deg);">↻</span>
            </button>
            <input type="file" id="hiddenFileInput1" name="resumeFile1" style="display: none;" accept="application/pdf">
          </div>

          <div id="add-resume-file-result-div" class="hidden-btn">
            <button type="button" id="resultButton" class="results-button" onclick="getResumeResult()">
              <svg fill="#FFFFFF" viewBox="0 0 32 32" id="icon" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <style>
                    .cls-1 {
                      fill: none;
                    }
                  </style>
                </defs>
                <rect x="13.9999" y="23" width="8" height="2" />
                <rect x="9.9999" y="23" width="2" height="2" />
                <rect x="13.9999" y="18" width="8" height="2" />
                <rect x="9.9999" y="18" width="2" height="2" />
                <rect x="13.9999" y="13" width="8" height="2" />
                <rect x="9.9999" y="13" width="2" height="2" />
                <path
                  d="M25,5H22V4a2,2,0,0,0-2-2H12a2,2,0,0,0-2,2V5H7A2,2,0,0,0,5,7V28a2,2,0,0,0,2,2H25a2,2,0,0,0,2-2V7A2,2,0,0,0,25,5ZM12,4h8V8H12ZM25,28H7V7h3v3H22V7h3Z"
                  transform="translate(0 0)" />
                <rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" class="cls-1" width="32"
                  height="32" />
              </svg>
              Result
            </button>
          </div>



        </div>
      </form>

      <hr>

    <?php } ?>

    <?php if ($current_profile_sub == "premium") { ?>

      <h3>Document Validator</h3>

      <div class="button-container">

        <div id="open-qr-code-reader-div" class="shown-btn">
          <button type="button" id="fileButton" class="pdf-button" onclick="showQrCodeReaderPopUp()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
              aria-hidden="true">
              <path
                d="M16.8772 15L21 17C21 17 21.5 15 21.5 12C21.5 9 21 7 21 7L16.8772 9M16.8772 15C16.9538 14.0994 17 13.0728 17 12C17 10.9272 16.9538 9.9006 16.8772 9M16.8772 15C16.7318 16.7111 16.477 17.9674 16.2222 18.2222C15.8333 18.6111 13.1111 19 10 19C6.88889 19 4.16667 18.6111 3.77778 18.2222C3.38889 17.8333 3 15.1111 3 12C3 8.88889 3.38889 6.16667 3.77778 5.77778C4.16667 5.38889 6.88889 5 10 5C13.1111 5 15.8333 5.38889 16.2222 5.77778C16.477 6.03256 16.7318 7.28891 16.8772 9"
                stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Verify Doc
          </button>
        </div>

      </div>

      <hr>

    <?php } ?>

    <h3>Costum</h3>
    <ul>
      <li class="question" style="cursor: pointer; color: #40A2D8;">Hello</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">List jobs</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">List notifications</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">Articles</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">Messaging</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">Reporting</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">Bye</li>
    </ul>

    <hr>

    <h3>Suggestion questions</h3>
    <ul id="suggestion-questions">
      <li class="question" style="cursor: pointer; color: #40A2D8;">What job opportunities are available?</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">How do I improve my resume?</li>
      <li class="question" style="cursor: pointer; color: #40A2D8;">What are the latest trends in the job market?</li>
    </ul>
  </div>
</div>

<div id="popup-card-qr-code-reader" class="popup-card">
  <div class="popup-content">
    <span id="close-popup-qr-code-reader" class="close">&times;</span>
    <h3 id="popup-Name" class="text-capitalize"></h3>
    <iframe id="popup-card-qr-code-reader-iframe" src=""></iframe>
  </div>
</div>



<script>

  function imgIconClicked() {
    dropdownMenuBot = document.getElementById('dropdownMenuBot');
    current_btn = document.getElementById('img-bot-add-btn')
    if (current_btn.className == "fas fa-image") {
      document.getElementById('hiddenFileInputImgBot').click();
    } else {
      dropdownMenuBot.style.display = dropdownMenuBot.style.display === 'block' ? 'none' : 'block';
    }
  }

  document.getElementById('hiddenFileInputImgBot').addEventListener('change', function (event) {

    current_btn = document.getElementById('img-bot-add-btn')

    const file = event.target.files[0];
    if (file) {
      if (current_btn.className == "fas fa-image") {
        current_btn.className = "fa-solid fa-images";
      }
      const reader = new FileReader();
      reader.onload = function (e) {
        showImage();
        const imgPreview = document.getElementById('imgPreview');
        imgPreview.src = e.target.result;

        imgPreview.style.display = 'block';
        //console.log(e.target.result);
        base64String = e.target.result;
        localStorage.setItem('uploadedImageBase64ForHiry', base64String);
      }
      reader.readAsDataURL(file);
    } else {
      const imgPreview = document.getElementById('imgPreview');
      imgPreview.src = null;
      imgPreview.style.display = 'none';
      current_btn.className = "fas fa-image";
      localStorage.removeItem('uploadedImageBase64');
    }
  });

  function showImage() {


    const item = document.createElement('div');
    item.className = 'dropdown-item';

    item.style.padding = "12px 16px";
    item.style.display = "flex";
    item.style.justifyContent = "space-between";
    item.style.alignItems = "center";
    item.style.borderBottom = "1px solid #ddd";
    item.style.transition = "background-color 0.3s";

    item.innerHTML = `
            <img  width='60' height='60' id="imgPreview" >
            <div>
                <button onclick="viewImgBot()">View</button>
                <button onclick="deleteImgBot()">Delete</button>
            </div>
        `;
    dropdownMenuBot = document.getElementById('dropdownMenuBot');
    dropdownMenuBot.appendChild(item);

  }

  dropdownMenuBot = document.getElementById('dropdownMenuBot');
  dropdownToggleBot = document.getElementById('img-bot-add-btn');
  document.addEventListener('click', (event) => {
    if (!dropdownToggleBot.contains(event.target) && !dropdownMenuBot.contains(event.target)) {
      dropdownMenuBot.style.display = 'none';
    }
  });

  function viewImgBot() {

    title = 'Image';
    const imgPreview = document.getElementById('imgPreview');
    const img = new Image();
    img.src = imgPreview.src;
    
    // Extract the image name from the src attribute
    
    // Open a new window
    const w = window.open("");
    
    // Set the document title to the image name
    w.document.title = title;
    
    // Write the image to the new window's document
    w.document.write(`<html><head><title>${title}</title></head><body>${img.outerHTML}</body></html>`);
}


  function deleteImgBot() {
    document.getElementById('hiddenFileInputImgBot').value = null;
    const imgPreview = document.getElementById('imgPreview');
    imgPreview.src = null;
    imgPreview.style.display = 'none';
    current_btn = document.getElementById('img-bot-add-btn')
    current_btn.className = "fas fa-image";
    localStorage.removeItem('uploadedImageBase64');
    dropdownMenuBot = document.getElementById('dropdownMenuBot');
    dropdownMenuBot.style.display = 'none';
  }
</script>

<script>
  // Show the modal when the plus button is clicked
  document.getElementById('plus-btn').onclick = function () {

    <?php if ($current_profile_sub == "premium") { ?>
      initResumeBtns();
    <?php } ?>

    document.getElementById('questionModal').style.display = "block";

    // get some suggestions
    let sug_List = [];
    sug_List.push("How do I improve my resume?");
    sug_List.push("What are the latest trends in the job market?");
    sug_List.push("What are the fastest-growing industries for employment?");
    sug_List.push("How can I effectively network to enhance my job prospects?");
    sug_List.push("What skills are most in-demand by employers right now?");
    sug_List.push("How can I tailor my resume for a specific job application?");
    sug_List.push("Are there any emerging job roles that I should be aware of?");
    sug_List.push("What are some common mistakes to avoid during job interviews?");
    sug_List.push("Are there any certifications or additional training programs that could boost my career?");
    sug_List.push("What strategies can I use to negotiate a higher salary or better benefits?");

    function chooseRandomSuggestions(list, n) {
      let shuffled = list.slice(0), i = list.length, min = i - n, temp, index;
      while (i-- > min) {
        index = Math.floor((i + 1) * Math.random());
        temp = shuffled[index];
        shuffled[index] = shuffled[i];
        shuffled[i] = temp;
      }
      return shuffled.slice(min);
    }


    // Randomly choose 3 suggestions
    let randomSuggestions = chooseRandomSuggestions(sug_List, 3);

    // Get the <ul> element by its id
    let ulElement = document.getElementById("suggestion-questions");

    // Remove all child elements of the <ul> element
    while (ulElement.firstChild) {
      ulElement.removeChild(ulElement.firstChild);
    }

    // Construct the HTML string for the list items
    let htmlString = "";
    randomSuggestions.forEach(suggestion => {
      htmlString += "<li class='question' style='cursor: pointer; color: #40A2D8;' onclick='LineClicked(this)'>" + suggestion + "</li>";
    });

    // Set the innerHTML of the <ul> element to the HTML string
    ulElement.innerHTML = htmlString;





  }

  // Close the modal when the close button is clicked
  document.querySelector('.modal .close').onclick = function () {
    document.getElementById('questionModal').style.display = "none";
  }

  // Add event listeners to the questions to insert them into the chatbot's input
  document.querySelectorAll('.modal .question').forEach(function (question) {
    question.onclick = function () {
      document.getElementById('textarea-bot').value = this.textContent;
      handleChat();
      document.getElementById('questionModal').style.display = "none";
    }
  });

  // Close the modal when clicking outside of the modal content
  window.onclick = function (event) {
    if (event.target == document.getElementById('questionModal')) {
      document.getElementById('questionModal').style.display = "none";
    }
  }

  function LineClicked(qs) {
    console.log(qs);
    document.getElementById('textarea-bot').value = qs.textContent;
    handleChat();
    document.getElementById('questionModal').style.display = "none";
  }


  function closeChatModalHere() {
    //console.log("closeChatModal");
    var modal = document.getElementById("questionModal");
    modal.style.display = "none";
  }

</script>

<script>

  // when the user uploads a pdf file
  document.getElementById('hiddenFileInput').addEventListener('change', function (event) {
    file = event.target.files[0];
    console.log(file);
    if (file) {
      analyzeResume(file, function (res) {
        // This code will be executed when analyzeResume completes
        fileUploadedBtnChange(res);
      });

    }
  });

  document.getElementById('hiddenFileInput1').addEventListener('change', function (event) {
    file1 = event.target.files[0];
    console.log(file1);
    if (file1) {
      analyzeResume(file, function (res) {
        // This code will be executed when analyzeResume completes
        fileUploadedBtnChange(res);
      });

    }
  });

  function initResumeBtns() {
    document.getElementById('add-resume-file-div').classList.add('shown-btn');
    document.getElementById('add-resume-file-div').classList.remove('hidden-btn');
    document.getElementById('add-resume-file-agian-div').classList.add('hidden-btn');
    document.getElementById('add-resume-file-agian-div').classList.remove('shown-btn');
    document.getElementById('add-resume-file-result-div').classList.add('hidden-btn');
    document.getElementById('add-resume-file-result-div').classList.remove('shown-btn');
  }

  function fileUploadedBtnChange(is_file_uploaded) {
    document.getElementById('add-resume-file-div').classList.add('hidden-btn');
    document.getElementById('add-resume-file-div').classList.remove('shown-btn');
    document.getElementById('add-resume-file-agian-div').classList.remove('hidden-btn');
    document.getElementById('add-resume-file-agian-div').classList.add('shown-btn');
    if (is_file_uploaded) {
      document.getElementById('add-resume-file-result-div').classList.remove('hidden-btn');
      document.getElementById('add-resume-file-result-div').classList.add('shown-btn');
    } else {
      document.getElementById('add-resume-file-result-div').classList.add('hidden-btn');
      document.getElementById('add-resume-file-result-div').classList.remove('shown-btn');
    }
  }

  function analyzeResume(file, callback) {
    // Get the form element
    var form = document.getElementById("resumeForm");

    // Create a new FormData object
    var formData = new FormData(form);

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Define the URL and method for the request
    var url = form.action;
    var method = form.method;

    // Open a connection
    xhr.open(method, url, true);

    // Set up a callback function to handle the response
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        // Request was successful, handle the response here

        //console.log(xhr.responseText);

        var data = JSON.parse(xhr.responseText);

        // Save the data to localStorage
        localStorage.setItem('resumeData', JSON.stringify(data));

        console.log('Data saved successfully.');

        form.reset();
        res = true
        callback(res);

      } else {
        // Request failed, handle the error here
        console.error('Request failed with status', xhr.status);
      }
    };

    // Set up a callback function to handle any errors
    xhr.onerror = function () {
      // Handle any errors that occur during the request
      console.error('Request failed');
    };

    // Send the FormData object with the request
    xhr.send(formData);
    var form = document.getElementById('resumeForm');
    form.reset();
    return false;
  }

  function getResumeResult() {

    var savedData = localStorage.getItem('resumeData');
    console.log(savedData);
    handleChatResumeDataGet(savedData);
    closeChatModalHere();
  }

</script>

<!-- Popup Modal -->
<script>
  function showQrCodeReaderPopUp(lng, lat, place) {
    console.log("Map selection popup opened");
    var modal = document.getElementById("popup-card-qr-code-reader");
    var map_iframe = document.getElementById("popup-card-qr-code-reader-iframe");
    map_iframe.src = `<?= $current_website_link ?>/View/front_office/qr code reader/index.html`;
    modal.style.display = "block";

  }

  var modal_qr2 = document.getElementById("popup-card-qr-code-reader");
  var closeButton_qr2 = document.getElementById("close-popup-qr-code-reader");

  closeButton_qr2.onclick = function () {
    modal_qr2.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal_qr2) {
      modal_qr2.style.display = "none";
    }
  };
</script>


<script>
  window.addEventListener('message', receiveMessageFromIframe, false);

  function receiveMessageFromIframe(event) {
    //console.log('Message received from iframe:', event.data);
    if (event.data) {
      console.log(event.data);
      var modal_reader = document.getElementById("popup-card-qr-code-reader");

      if (event.data.message == "the qr is ready :") {

        modal_reader.style.display = "none";
        closeChatModalHere();
        handleChatQrCodeDataGet(event.data.data);

      }
    }
  }
</script>


<script src="https://code.responsivevoice.org/responsivevoice.js?key=TUGpBJay"></script>