<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="./../../../front office assets/css/chatbot.css" />
</head>

<body>
  <!--
  <div class="chatbox-wrapper">
    <div class="message-box">
      <div class="chat response">
        <img src="img/chatbot.jpg">
        <span>Hello there! <br> 
          How can I help you today.
        </span>
      </div>
    </div>
    <div class="messagebar">
      <div class="bar-wrapper">
        <input type="text" placeholder="Enter your message...">
        <button>
          <span class="material-symbols-rounded">
            send
            </span>
        </button>
      </div>
    </div>
  </div>-->

  <div class="chat-card">
    <div class="chat-header">
      <div class="h2">HireUp Bot
        <i class="fa-solid fa-chevron-up"></i>
        <i class="fa-solid fa-angle-down"></i>
      </div>
    </div>
    <div class="chat-body">
      <!-- Chat body content goes here -->
      <div class="message incoming">
        <p>Hello! I'm HireUp Bot, your career companion. Let's navigate employment and recruitment together.</p>
      </div>
      <div class="message incoming">
        <p>Quick reminder: I'm here for all your career queries. Reach out anytime!</p>
      </div>
    </div>
    <div class="chat-footer">
      <input class="text-lowercase" placeholder="Type your message" type="text">
      <button>Send</button>
    </div>
  </div>

  <!-- <script src="script.js"></script> -->
  <script src="./../../../front office assets/js/chatbot.js"></script>

  <!-- chatbot animation -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chatHeader = document.querySelector(".chat-header");
      const chatBody = document.querySelector(".chat-body");

      chatHeader.addEventListener("click", function() {
        chatBody.classList.toggle("hidden");
        const chevronUp = document.querySelector(".fa-chevron-up");
        const chevronDown = document.querySelector(".fa-angle-down");
        if (chatBody.classList.contains("hidden")) {
          chevronUp.style.display = "none";
          chevronDown.style.display = "inline-block";
        } else {
          chevronUp.style.display = "inline-block";
          chevronDown.style.display = "none";
        }
      });
    });
  </script>

</body>

</html>