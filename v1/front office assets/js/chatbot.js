var scriptSrc = document.currentScript.src;
var scriptDirectory = scriptSrc.substring(0, scriptSrc.lastIndexOf("/"));

var websit_url_const = "http://localhost/hireup/v1";

const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");

let userMessage = null; // Variable to store user's message
const inputInitHeight = chatInput.scrollHeight;

let currentSpeech;

function send_data_to_be_written(user_input) {
  // JavaScript code (in your HTML or separate .js file)
  //const newContent = document.getElementById('gg').value;
  const newContent = user_input;

  // Make an AJAX request to your PHP script
  fetch('write_file.php', {
    method: 'POST',
    body: JSON.stringify({ newContent }), // Send data as JSON
  })
    .then(response => response.text())
    .then(result => {
      console.log(result); // Handle the response from PHP
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function stopSpeaking() {
  if (currentSpeech && window.speechSynthesis.speaking) {
    window.speechSynthesis.cancel();
  }
}

function speakText(text) {
  // Stop any ongoing speech
  stopSpeaking();

  const speech = new SpeechSynthesisUtterance();
  speech.text = text;
  speech.volume = 1;
  speech.rate = 1.5;
  speech.pitch = 2;

  // Get the voices available for speech synthesis
  const voices = window.speechSynthesis.getVoices();

  // Find a female voice
  const femaleVoice = voices.find(voice => voice.name.toLowerCase().includes('female'));

  // Set the voice to the female voice
  speech.voice = femaleVoice;

  window.speechSynthesis.speak(speech);

  // Keep track of the current speech
  currentSpeech = speech;
}




async function chat_gpt_rep1(user_msg) {
  try {
    let API_URL = "https://api.openai.com/v1/chat/completions";
    let API_KEY = "sk-proj-GNtEFPHcgO9mccc0yPcaT3BlbkFJzizRVuAuUHKtdTR9ShRj";

    if (user_msg.length > 0) {
      const UserTypedMessage = user_msg;

      const requestOptions = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${API_KEY}`,
        },
        body: JSON.stringify({
          model: "gpt-3.5-turbo",
          messages: [{ role: "user", content: UserTypedMessage }],
        }),
      };

      const response = await fetch(API_URL, requestOptions);
      if (!response.ok) {
        throw new Error("Failed to fetch GPT response");
      }

      const data = await response.json();
      const responseData = `${data.choices[0].message.content}`;
      console.log(responseData);
      return responseData;
    } else {
      throw new Error("Empty message");
    }
  } catch (error) {
    console.error("Error fetching GPT response:", error);
    throw error;
  }
}

async function chat_gpt_rep2(user_msg) {
  //console.log(user_msg)
  try {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            const responseData = xhr.responseText;
            resolve(responseData);
          } else {
            console.error('Error:', xhr.status);
            reject(new Error("Failed to execute Python script"));
          }
        }
      };
      //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/get_ai_respons.php?prompt=`+user_msg, true);
      //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/get_ai_respons.php`, true);
      xhr.open('GET', websit_url_const + `/view/front_office/chatbot/get_ai_respons.php`, true);
      xhr.send();
    });
  } catch (error) {
    console.error("Error executing Python script:", error);
    throw error;
  }
}

async function chat_gpt_rep(user_msg) {
  try {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            const responseData = xhr.responseText;
            resolve(responseData);
          } else {
            console.error('Error:', xhr.status);
            reject(new Error("Failed to execute Python script"));
          }
        }
      };

      // Set up the request
      //xhr.open('POST', scriptDirectory + `/../../view/front_office/chatbot/get_ai_respons.php`, true); // Replace with your PHP script URL
      xhr.open('POST', websit_url_const + `/view/front_office/chatbot/get_ai_respons.php`, true); // Replace with your PHP script URL
      xhr.setRequestHeader('Content-Type', 'application/json'); // Specify JSON content type

      // Prepare the data to send
      savedImageBase64ForHiry = localStorage.getItem('uploadedImageBase64ForHiry');
      if (savedImageBase64ForHiry == null) {
        dataToSend = {
          newContent: user_msg, // Replace with your content
          user_img: null
        };
      } else {
        dataToSend = {
          newContent: user_msg, // Replace with your content
          user_img: savedImageBase64ForHiry
        };
      } 

      // Send the data as JSON
      xhr.send(JSON.stringify(dataToSend));
    });
  } catch (error) {
    console.error("Error executing Python script:", error);
    throw error;
  }
}

const fetchUserNotifications0 = async () => {
  try {
    const response = await fetch(
      "./../../Controller/notification_con.php?action=listNotificationsByReceiverIdOrderedByDateTime&receiver_id=${userId}"
    );
    const notifications = await response.json();
    return notifications;
  } catch (error) {
    console.error("Error fetching user notifications:", error);
    return [];
  }
};

const fetchUserNotifications = () => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          const notifications = JSON.parse(xhr.responseText);
          resolve(notifications);
        } else {
          reject(new Error("Failed to fetch user notifications"));
        }
      }
    };

    console.log('baseUrl');
    //console.log(scriptDirectory);
    //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/fetch_notifications.php`, true);
    xhr.open('GET', websit_url_const + `/view/front_office/chatbot/fetch_notifications.php`, true);
    xhr.send();
  });
};

const fetchLatestJobs0 = async () => {
  try {
    const response = await fetch(
      "./../../Controller/JobC.php?action=returnAllJobsChat"
    );
    const jobs = await response.json();
    return jobs;
  } catch (error) {
    console.error("Error fetching latest jobs:", error);
    return [];
  }
};

const fetchLatestJobs = () => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          const notifications = JSON.parse(xhr.responseText);
          resolve(notifications);
        } else {
          reject(new Error("Failed to fetch user notifications"));
        }
      }
    };

    //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/fetch_latest_jobs.php`, true);
    xhr.open('GET', websit_url_const + `/view/front_office/chatbot/fetch_latest_jobs.php`, true);
    xhr.send();
  });
};

const getPostedBy = (id_profile) => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          const notifications = JSON.parse(xhr.responseText);
          resolve(notifications);
        } else {
          reject(new Error("Failed to fetch user notifications"));
        }
      }
    };

    //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/fetch_posted_by.php?profile_id=${id_profile}`, true);
    xhr.open('GET', websit_url_const + `/view/front_office/chatbot/fetch_posted_by.php?profile_id=${id_profile}`, true);
    xhr.send();
  });
};

const getNotfiSender = (id_profile) => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          const notifications = JSON.parse(xhr.responseText);
          resolve(notifications);
        } else {
          reject(new Error("Failed to fetch user notifications"));
        }
      }
    };

    //xhr.open('GET', scriptDirectory + `/../../view/front_office/chatbot/fetch_notif_by.php?profile_id=${id_profile}`, true);
    xhr.open('GET', websit_url_const + `/view/front_office/chatbot/fetch_notif_by.php?profile_id=${id_profile}`, true);
    xhr.send();
  });
};

// Predefined responses stored in JSON format
const responses = {
  hello:
    "Hello! How can I assist you today with finding a job, managing your profile, communicating with others, accessing articles, managing ads, or generating reports?",
  "how are you":
    "I'm just a bot, but thanks for asking! How can I assist you with Hireup?",
  bye: "Goodbye! Have a great day.",
  "job search":
    "Hireup offers a comprehensive job search platform where you can explore thousands of job listings tailored to your skills and preferences. How can I assist you with your job search?",
  "profile management":
    "You can manage your Hireup profile to highlight your skills, experience, and qualifications to potential employers. How can I assist you with profile management?",
  messaging:
    "With Hireup's messaging feature, you can communicate with employers, recruiters, and other users directly. How can I assist you with messaging?",
  articles:
    "Hireup provides access to a wealth of articles, blog posts, and resources to help you navigate your career path, improve your skills, and stay updated on industry trends. How can I assist you with accessing articles?",
  "ads management":
    "If you're interested in advertising your services or job listings on Hireup, our ads management feature allows you to create, target, and track your ads for maximum exposure. How can I assist you with ads management?",
  reporting:
    "Hireup offers robust reporting and analytics capabilities to track your job applications, profile views, message interactions, ad performance, and more. How can I assist you with reporting?",

  notifications: async () => {
    const notifications = await fetchUserNotifications();
    if (notifications.length > 0) {
      let response = `You have ${notifications.length} new notifications. `;
      // Choose a random notification to read
      const randomNotification = chooseRandomString(notifications);
      response += `Here is one of your notifications: ${randomNotification.content}`;
      return response;
    } else {
      return "You have no new notifications.";
    }
  },

  jobs: async () => {
    try {
      const jobs = await fetchLatestJobs();
      if (jobs.length > 0) {
        let response = "Here are the latest job listings:\n";
        jobs.forEach((job, index) => {
          response += `${index + 1}. ${job.title} at ${job.company}\n`;
        });
        return response;
      } else {
        return "There are currently no job listings available.";
      }
    } catch (error) {
      console.error("Error fetching latest jobs:", error);
      return "An error occurred while fetching the latest job listings.";
    }
  },

  default:
    "I'm sorry, I don't understand that. Please let me know how I can assist you with Hireup.",
};

const createChatLi = (message, className) => {
  // Create a chat <li> element with passed message and className
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", `${className}`);
  let chatContent =
    className === "outgoing"
      ? `<p></p>`
      : `<span class="material-symbols-outlined"><i class="fa fa-robot"></i></span><p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi; // return chat <li> element
};

const generateResponse = async () => {
  const message = userMessage.toLowerCase().trim();
  try {
    let response;
    if (responses.hasOwnProperty(message)) {
      response = responses[message];
    } else {
      response = await chat_gpt_rep(message);
    }
    console.log(response);
    return response;
  } catch (error) {
    console.error(error);
    return error;
  }
};

const handleChat = async () => {
  userMessage = chatInput.value.trim();
  if (!userMessage) return;

  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;

  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  const incomingChatLi = createChatLi("Thinking...", "incoming");
  chatbox.appendChild(incomingChatLi);
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(async () => {
    chatbox.removeChild(incomingChatLi);

    let response;
    if (userMessage.toLowerCase() === "notifications" || userMessage.toLowerCase() === "notifications." || userMessage.toLowerCase() === "notification" || userMessage.toLowerCase() === "notification." || userMessage.toLowerCase() === "list notifications." || userMessage.toLowerCase() === "list notifications") {
      const notifications = await fetchUserNotifications();
      /*response =
        notifications.length > 0
          ? `You have ${notifications.length} new notifications.`
          : "You have no new notifications.";*/

      if (notifications.length > 0) {
        if (notifications.length > 1) {
          response = `You have ${notifications.length} new notifications. \n`
        } else {
          response = `You have ${notifications.length} new notification. \n`
        }
        for (let i = 0; i < notifications.length; i++) {
          const notifBy = await getNotfiSender(notifications[i].sender_id)
          response += `${i + 1}. ${notifBy.posted_by} `

          response += `${notifications[i].content}\n`
        }
      } else {
        response = 'You have no new notifications.'
      }
    } else if (userMessage.toLowerCase() === "jobs" || userMessage.toLowerCase() === "job" || userMessage.toLowerCase() === "jobs." || userMessage.toLowerCase() === "job." || userMessage.toLowerCase() === "list jobs." || userMessage.toLowerCase() === "list jobs") {
      const jobs = await fetchLatestJobs();
      /*response =
        notifications.length > 0
          ? `You have ${notifications.length} new notifications.`
          : "You have no new notifications.";*/

      if (jobs.length > 0) {
        if (jobs.length > 1) {
          response = `You have ${jobs.length} new jobs. \n`
        } else {
          response = `You have ${jobs.length} new job. \n`
        }
        for (let i = 0; i < jobs.length; i++) {
          response += `${i + 1}. ${jobs[i].title} for ${jobs[i].salary} at ${jobs[i].company} located at ${jobs[i].location}\n`

          const postedBy = await getPostedBy(jobs[i].jobs_profile)
          response += `posted by ${postedBy.posted_by}\n`

        }
      } else {
        response = 'You have no new jobs.'
      }
    }

    else {
      response = await generateResponse();
    }

    speakText(response);

    const responseChatLi = createChatLi(response, "incoming");
    chatbox.appendChild(responseChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }, 600);
};

chatInput.addEventListener("input", () => {
  // Adjust the height of the input textarea based on its content
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
  // If Enter key is pressed without Shift key and the window
  // width is greater than 800px, handle the chat
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});

// Add event listeners
sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () => {
  document.body.classList.remove("show-chatbot");
  document.getElementById('questionModal').style.display = "none";
});
chatbotToggler.addEventListener("click", () => {
  document.body.classList.toggle("show-chatbot");
  if (!document.body.classList.contains("show-chatbot")) {
    document.getElementById('questionModal').style.display = "none";
    stopSpeaking();
  }
});


const handleChatResumeDataGet = async (data) => {

  chatInput.value = "";
  userMessage = "Resume analyzing result";
  chatInput.style.height = `${inputInitHeight}px`;

  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  const incomingChatLi = createChatLi("Thinking...", "incoming");
  chatbox.appendChild(incomingChatLi);
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(async () => {
    chatbox.removeChild(incomingChatLi);

    data_list = JSON.parse(data);

    let response;
    list_length = data_list.length;
    if (list_length > 0) {
    
      i = 0;
      response = 'Your resume scored : \n' ;
      data_list.forEach(function (item) {
        // Access each dictionary item here
        if (parseInt(item.rank) > 0) {
          response += item.rank + '% at ' + item.category_name + ' \n';
          //response += '<progress id="progressBar" max="100" value="' + item.rank + '"></progress>';
          i++
        } 

      });

      if (i < list_length) {
        response += 'and the rests scored 0%\n' ;
      }

    }
    //response = "await chat_gpt_rep1(userMessage)" + typeof data;

    speakText(response);

    const responseChatLi = createChatLi(response, "incoming");
    chatbox.appendChild(responseChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }, 600);

};

const handleChatQrCodeDataGet = async (data) => {

  chatInput.value = "";
  userMessage = "Document validation result";
  chatInput.style.height = `${inputInitHeight}px`;

  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  const incomingChatLi = createChatLi("Thinking...", "incoming");
  chatbox.appendChild(incomingChatLi);
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(async () => {
    chatbox.removeChild(incomingChatLi);

    let response;

    if (data.valid) {
      response = 'This document is valid\n';
      response += 'content: \n' + data.data + '\n';
    } else {
      response = 'This document is invalid\n';
    }
    
    //response = "await chat_gpt_rep1(userMessage)" + typeof data;

    speakText(response);

    const responseChatLi = createChatLi(response, "incoming");
    chatbox.appendChild(responseChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }, 600);

};


