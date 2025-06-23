const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");

let userMessage = null; // Variable to store user's message
const inputInitHeight = chatInput.scrollHeight;

async function chat_gpt_rep(user_msg) {
  try {
    let API_URL = "https://api.openai.com/v1/chat/completions";
    let API_KEY = "sk-proj-qGKo3k26JDkIQ5r4eKa8T3BlbkFJTXfbESWkfLtg3BUSzhTz";

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
      
      xhr.onreadystatechange = function() {
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

      xhr.open('GET', `./../../front_office/chatbot/fetch_notifications.php`, true);
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
      
      xhr.onreadystatechange = function() {
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

      xhr.open('GET', `./../../front_office/chatbot/fetch_latest_jobs.php`, true);
      xhr.send();
  });
};

const getPostedBy = (id_profile) => {
  return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      
      xhr.onreadystatechange = function() {
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

      xhr.open('GET', `./../../front_office/chatbot/fetch_posted_by.php?profile_id=${id_profile}`, true);
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
    if (userMessage.toLowerCase() === "notifications") {
      const notifications = await fetchUserNotifications();
      /*response =
        notifications.length > 0
          ? `You have ${notifications.length} new notifications.`
          : "You have no new notifications.";*/
      
      if (notifications.length > 0){
        response = `You have ${notifications.length} new notifications. \n`
        for (let i = 0; i < notifications.length; i++){
          response += `${i + 1}. ${notifications[i].content}\n`
        }
      } else {
        response = 'You have no new notifications.'
      }
    } else if (userMessage.toLowerCase() === "jobs") {
      const jobs = await fetchLatestJobs();
      /*response =
        notifications.length > 0
          ? `You have ${notifications.length} new notifications.`
          : "You have no new notifications.";*/
      
      if (jobs.length > 0){
        response = `You have ${jobs.length} new jobs. \n`
        for (let i = 0; i < jobs.length; i++){
          response += `${i + 1}. ${jobs[i].title} for ${jobs[i].salary} at ${jobs[i].company} located at ${jobs[i].location}\n`

          const postedBy = await getPostedBy(jobs[i].jobs_profile)
          response += `posted by ${postedBy.posted_by}\n`

        }
      } else {
        response = 'You have no new jobs.'
      }
    } 
    
    else {
      response = generateResponse();
    }

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

sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () =>
  document.body.classList.remove("show-chatbot")
);
chatbotToggler.addEventListener("click", () =>
  document.body.classList.toggle("show-chatbot")
);
