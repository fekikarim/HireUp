const sendBtn = document.querySelector(".chat-footer button");
const messageBox = document.querySelector(".chat-body");

let API_URL = "https://api.openai.com/v1/chat/completions";
let API_KEY = "sk-proj-qGKo3k26JDkIQ5r4eKa8T3BlbkFJTXfbESWkfLtg3BUSzhTz";

sendBtn.onclick = function () {
  const messageBar = document.querySelector(".chat-footer input");
  if (messageBar.value.length > 0) {
    const UserTypedMessage = messageBar.value;
    messageBar.value = "";

    let message = `<div class="message outgoing">
      <p>${UserTypedMessage}</p>
    </div>`;

    messageBox.insertAdjacentHTML("beforeend", message);

    setTimeout(() => {
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

      fetch(API_URL, requestOptions)
        .then((res) => res.json())
        .then((data) => {
          let response = `<div class="message incoming">
            <p>${data.choices[0].message.content}</p>
          </div>`;
          messageBox.insertAdjacentHTML("beforeend", response);
        })
        .catch((error) => {
          let errorMessage = `<div class="message incoming">
            <p>Oops! An error occurred. Please try again.</p>
          </div>`;
          messageBox.insertAdjacentHTML("beforeend", errorMessage);
        });
    }, 100);
  }
};


