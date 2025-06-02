<template>
  <div class="chat-wrapper">
    <div class="chat-header">Chat with us:</div>

    <div v-if="messages.length" class="chat-messages" ref="messagesContainer">
        <div
            v-for="(message, index) in messages"
            :key="index"
            :class="['message', message.from === 'user' ? 'user' : 'ai']"
        >
            {{ message.text }}
        </div>
        <div v-if="isTyping" class="message ai typing-indicator">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <form @submit.prevent="sendMessage" class="chat-form">
      <textarea
        v-model="userInput"
        rows="2"
        class="chat-input"
        placeholder="Ask your question..."
      ></textarea>
      <button type="submit" class="chat-submit">Send</button>
    </form>
  </div>
</template>

<script>
import './ChatApp.css';

export default {
  props: ['csrf'],
  data() {
    return {
      userInput: '',
      messages: [],
    };
  },
  methods: {
    async sendMessage() {
      if (!this.userInput.trim()) return;

      // Add user message to the array.
      this.messages.push({ from: 'user', text: this.userInput });

      const messageToSend = this.userInput;
      this.userInput = '';
      this.isTyping = true;

      // Send the message to the endpoint.
      try {
        const response = await fetch('/send', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrf,
          },
          body: JSON.stringify({ message: messageToSend }),
        });

        const data = await response.json();
        this.messages.push({ from: 'ai', text: data.data.reply });
      } catch (error) {
        this.messages.push({ from: 'ai', text: 'Something went wrong please try again later.' });
      }
      this.isTyping = false;

      // Scroll to the last message.
      this.$nextTick(() => {
        const container = this.$refs.messagesContainer;
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    },
  },
};
</script>
