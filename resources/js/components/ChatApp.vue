<!-- resources/js/components/ChatApp.vue -->
<template>
  <div>
    <h1>Чат с OpenAI</h1>
    <form @submit.prevent="sendMessage">
      <textarea v-model="userInput" class="w-full p-2 border"></textarea>
      <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white">Изпрати</button>
    </form>

    <div v-if="messages.length" class="mt-4 space-y-2 max-h-60 overflow-y-auto">
      <div v-for="(message, index) in messages" :key="index" class="p-2 bg-gray-100 rounded">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userInput: '',
      messages: [],
    };
  },
  methods: {
    async sendMessage() {
      if (!this.userInput.trim()) return;

      const message = this.userInput;
      this.messages.push('Вие: ' + message);
      this.userInput = '';

      // Dummy OpenAI simulation
      const response = await fetch('/api/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message }),
      });

      const data = await response.json();
      this.messages.push('AI: ' + data.reply);
    },
  },
};
</script>
