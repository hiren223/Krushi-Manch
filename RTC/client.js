let name;
let textarea = document.querySelector('#textarea');
let messageArea = document.querySelector('.message__area');

// Ask for name only once
do {
    name = prompt('Please enter your name: ');
} while(!name);

// Send message when Enter is pressed
textarea.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
        sendMessage(e.target.value);
    }
});

function sendMessage(message) {
    let msg = {
        user: name,
        message: message.trim()
    };

    // Append to the UI immediately (to simulate real-time chat)
    appendMessage(msg, 'outgoing');
    textarea.value = '';
    scrollToBottom();

    // Send message to server via AJAX
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'chat.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Message sent!');
        } else {
            console.error('Failed to send message');
        }
    };
    xhr.send('user=' + encodeURIComponent(msg.user) + '&message=' + encodeURIComponent(msg.message));
}

function appendMessage(msg, type) {
    let mainDiv = document.createElement('div');
    let className = type;
    mainDiv.classList.add(className, 'message');

    let markup = `
        <h4>${msg.user}</h4>
        <p>${msg.message}</p>
    `;
    mainDiv.innerHTML = markup;
    messageArea.appendChild(mainDiv);
}

// Fetch new messages periodically (AJAX polling)
function fetchMessages() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'chat.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            let messages = JSON.parse(xhr.responseText);
            messageArea.innerHTML = ''; // Clear the message area

            // Append all messages to the chat area
            messages.forEach(msg => {
                appendMessage(msg, 'incoming');
            });

            scrollToBottom();
        }
    };
    xhr.send();
}

// Scroll to the bottom of the chat window
function scrollToBottom() {
    messageArea.scrollTop = messageArea.scrollHeight;
}

// Poll every 2 seconds to get new messages
setInterval(fetchMessages, 2000);
